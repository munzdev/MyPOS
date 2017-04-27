<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Event\IEventBankinformationQuery;
use API\Lib\Interfaces\Models\Event\IEventContact;
use API\Lib\Interfaces\Models\Event\IEventContactQuery;
use API\Lib\Interfaces\Models\Event\IEventTableQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailUnbilled;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailUnbilledCollection;
use API\Lib\Interfaces\Models\Payment\ICouponCollection;
use API\Lib\Interfaces\Models\Payment\ICouponQuery;
use API\Lib\Interfaces\Models\Payment\IPaymentCoupon;
use API\Lib\Interfaces\Models\Payment\IPaymentRecieved;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use DateTime;
use Exception;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\INVOICE_TYPE_INVOICE;
use const API\PAYMENT_TYPE_BANK_TRANSFER;
use const API\PAYMENT_TYPE_CASH;
use const API\USER_ROLE_ORDER_ADD;
use function mb_strlen;
use function mb_substr;

class OrderUnbilled extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_ORDER_ADD];

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'id' => v::intVal()->positive(),
            'all' => v::boolVal()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    public function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $eventContactQuery = $this->container->get(IEventContactQuery::class);
        $eventBankinformationQuery = $this->container->get(IEventBankinformationQuery::class);
        $couponQuery = $this->container->get(ICouponQuery::class);
        $config = $this->container->get('settings');

        $user = $auth->getCurrentUser();

        $orderid = intval($this->args['id']);
        $all = filter_var($this->args['all'], FILTER_VALIDATE_BOOLEAN);

        $customerEventContact = null;
        if ($this->json['Customer'] !== null) {
            $customerEventContact = $this->container->get(IEventContact::class);
            $jsonToModel->convert($this->json['Customer'], $customerEventContact);
        }

        $invoiceOrderDetails = $this->container->get(IOrderDetailUnbilledCollection::class);
        $usedCoupons = $this->container->get(ICouponCollection::class);

        $jsonToModel->convert($this->json['UnbilledOrderDetails'], $invoiceOrderDetails);
        $jsonToModel->convert($this->json['UsedCoupons'], $usedCoupons);

        $unbilledOrderDetails = $this->getUnbilledOrderDetails($orderid, $all);

        $connection = $this->container->get(IConnectionInterface::class);
        $connection->beginTransaction();

        try {
            $eventContact = $eventContactQuery->getDefaultForEventid($user->getEventUsers()->getFirst()->getEventid());
            $eventBankinformation = $eventBankinformationQuery->getDefaultForEventid($user->getEventUsers()->getFirst()->getEventid());

            $invoice = $this->container->get(IInvoice::class);
            $invoice->setInvoiceTypeid(INVOICE_TYPE_INVOICE);
            $invoice->setEventContactid($eventContact->getEventContactid());
            $invoice->setUserid($user->getUserid());
            $invoice->setEventBankinformation($eventBankinformation);
            $invoice->setDate(new DateTime());
            $invoice->setMaturityDate(new DateTime($config['Invoice']['MaturityDate']));
            $invoice->setAmount(0);

            if ($customerEventContact) {
                $invoice->setCustomerEventContactid($customerEventContact->getEventContactid());
            }

            $invoice->save();

            $payed = 0;
            $orderidsToVerify = [];

            /** @var $orderDetail IOrderDetailUnbilled */
            foreach ($unbilledOrderDetails as $orderDetail) {
                /** @var $orderDetailJson IOrderDetailUnbilled */
                foreach ($invoiceOrderDetails as $orderDetailJson) {
                    if (empty($orderDetailJson->getAmountSelected())) {
                        continue;
                    }

                    $index = $this->buildIndexFromOrderDetail($orderDetail);
                    $indexJson = $this->buildIndexFromOrderDetail($orderDetailJson);

                    if ($index == $indexJson) {
                        if ($orderDetail->getMenuid() == null && $orderDetail->getMenuGroupid() == null) {
                            continue;
                        }

                        if ($orderDetail->getAmountLeft() >= $orderDetailJson->getAmountSelected()) {
                            $amount = $orderDetailJson->getAmountSelected();
                            $orderDetailJson->setAmountSelected(0);
                        } else {
                            $amount = $orderDetail->getAmountLeft();
                            $orderDetailJson->setAmountSelected($orderDetailJson->getAmountSelected() - $amount);
                        }

                        $invoiceItem = $this->container->get(IInvoiceItem::class);
                        $invoiceItem->setInvoice($invoice);
                        $invoiceItem->setOrderDetail($orderDetail);
                        $invoiceItem->setAmount($amount);
                        $invoiceItem->setPrice($orderDetail->getSinglePrice());

                        $payed += $orderDetail->getSinglePrice() * $amount;

                        if ($orderDetail->getMenuid() == null) {
                            $invoiceItem->setDescription($orderDetail->getExtraDetail());
                            $invoiceItem->setTax(
                                $orderDetail->getMenuGroup()
                                    ->getMenuType()
                                    ->getTax()
                            );
                        } else {
                            $description = '';

                            if ($orderDetail->getMenu()->getMenuPossibleSizes()->count() > 1) {
                                $description = $orderDetail->getMenu()->getName() . ", ";
                            }

                            if ($orderDetail->getOrderDetailMixedWiths()->count() > 1) {
                                $description .= "Gemischt mit: ";

                                foreach ($orderDetail->getOrderDetailMixedWiths() as $orderDetailMixedWith) {
                                    $description .= $orderDetailMixedWith->getMenu()->getName() . " - ";
                                }

                                $description = mb_substr($description, 0, -3);
                                $description .= ', ';
                            }

                            foreach ($orderDetail->getOrderDetailExtras() as $orderDetailExtra) {
                                $description .= $orderDetailExtra->getMenuPossibleExtra()->getMenuExtra()->getName() . ', ';
                            }

                            if (!empty($orderDetail->getExtraDetail())) {
                                $description .= $orderDetailExtra->getExtraDetail() . ', ';
                            }

                            if (mb_strlen($description) > 0) {
                                $description = mb_substr($description, 0, -2);
                            }

                            $invoiceItem->setDescription($description);
                            $invoiceItem->setTax(
                                $orderDetail->getMenu()
                                    ->getMenuGroup()
                                    ->getMenuType()
                                    ->getTax()
                            );
                        }

                        $invoiceItem->save();

                        $orderidsToVerify[] = $orderDetail->getOrderid();
                    }
                }
            }

            $invoice->setAmount($payed);

            if ($this->json['PaymentTypeid'] == PAYMENT_TYPE_CASH) {
                $invoice->setPaymentFinished(new DateTime());
                $invoice->setAmountRecieved($payed);

                $paymentRecieved = $this->container->get(IPaymentRecieved::class);
                $paymentRecieved->setInvoice($invoice);
                $paymentRecieved->setPaymentTypeid(PAYMENT_TYPE_CASH);
                $paymentRecieved->setUserid($user->getUserid());
                $paymentRecieved->setDate(new DateTime());
                $paymentRecieved->setAmount($payed);
                $paymentRecieved->save();

                foreach ($usedCoupons as $usedCoupon) {
                    $coupon = $couponQuery->getValidCoupon($user->getEventUsers()->getFirst()->getEventid(),
                        $usedCoupon->getCode());

                    $orgPayed = $payed;
                    $value = $coupon->getUsedValue();
                    $payed -= $value;

                    if ($payed < 0) {
                        $payed = 0;
                    }

                    $usedValue = $payed > 0 ? $coupon->getValue() : $orgPayed;

                    $paymentCoupon = $this->container->get(IPaymentCoupon::class);
                    $paymentCoupon->setCoupon($coupon);
                    $paymentCoupon->setPaymentRecieved($paymentRecieved);
                    $paymentCoupon->setValueUsed($usedValue);
                    $paymentCoupon->save();
                }
            } elseif ($this->json['PaymentTypeid'] == PAYMENT_TYPE_BANK_TRANSFER && !empty($usedCoupons)) {
                $paymentRecieved = $this->container->get(IPaymentRecieved::class);
                $paymentRecieved->setInvoice($invoice);
                $paymentRecieved->setPaymentTypeid(PAYMENT_TYPE_CASH);
                $paymentRecieved->setUserid($user->getUserid());
                $paymentRecieved->setDate(new DateTime());
                $paymentRecieved->setAmount(0);
                $paymentRecieved->save();

                $amountPayedViaCoupon = 0;

                foreach ($usedCoupons as $usedCoupon) {
                    $coupon = $couponQuery->getValidCoupon($user->getEventUsers()->getFirst()->getEventid(),
                        $usedCoupon->getCode());

                    $orgPayed = $payed;
                    $value = $coupon->getUsedValue();
                    $payed -= $value;

                    if ($payed < 0) {
                        $payed = 0;
                    }

                    $usedValue = $payed > 0 ? $coupon->getValue() : $orgPayed;

                    $amountPayedViaCoupon += $usedValue;

                    $paymentCoupon = $this->container->get(IPaymentCoupon::class);
                    $paymentCoupon->setCoupon($coupon);
                    $paymentCoupon->setPaymentRecieved($paymentRecieved);
                    $paymentCoupon->setValueUsed($usedValue);
                    $paymentCoupon->save();
                }

                $paymentRecieved->setAmount($amountPayedViaCoupon);
                $paymentRecieved->save();

                $invoice->setAmountRecieved($amountPayedViaCoupon);

                if ($amountPayedViaCoupon == $payed) {
                    $invoice->setPaymentFinished(new DateTime());
                }
            }

            $invoice->save();

            $paymentRecievedid = null;
            if ($paymentRecieved) {
                $paymentRecievedid = $paymentRecieved->getPaymentRecievedid();
            }

            StatusCheck::verifyInvoice($invoice->getInvoiceid());

            $orderidsToVerify = array_unique($orderidsToVerify);
            foreach ($orderidsToVerify as $orderid) {
                StatusCheck::verifyOrder($orderid);
            }

            $this->withJson([
                "Invoice" => $invoice->toArray(),
                "PaymentRecievedid" => $paymentRecievedid
            ]);

            $connection->commit();
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    private function getUnbilledOrderDetails($orderid, $all): IOrderDetailUnbilledCollection
    {
        $eventTableQuery = $this->container->get(IEventTableQuery::class);
        $orderDetailQuery = $this->container->get(IOrderDetailQuery::class);

        $eventTable = null;
        if ($all) {
            $eventTable = $eventTableQuery->findByOrderid($orderid);
        }

        $unbilledOrderDetails = $orderDetailQuery->findUnbilled($orderid, $eventTable);

        return $unbilledOrderDetails;
    }

    private function buildIndexFromOrderDetail(IOrderDetail $orderDetail) : string
    {
        $index = $orderDetail->getMenuid() . '-' .
                $orderDetail->getSinglePrice() . '-' .
                $orderDetail->getExtraDetail() . '-' .
                $orderDetail->getMenuSizeid() . '-';

        foreach ($orderDetail->getOrderDetailExtras() as $orderDetailExtra) {
            $index .= $orderDetailExtra->getMenuPossibleExtraid();
        }

        $index .= '-';

        foreach ($orderDetail->getOrderDetailMixedWiths() as $orderDetailMixedWith) {
            $index .= $orderDetailMixedWith->getMenuid();
        }

        return $index;
    }

    protected function get(): void
    {
        $orderid = intval($this->args['id']);
        $all = filter_var($this->args['all'], FILTER_VALIDATE_BOOLEAN);

        $unbilledOrderDetails = $this->getUnbilledOrderDetails($orderid, $all);
        $unbilledOrderDetailsArray = array();

        // if all order from table are returned, merge same order types
        if ($unbilledOrderDetails->count() > 0) {
            foreach ($unbilledOrderDetails as $orderDetail) {

                $index = $this->buildIndexFromOrderDetail($orderDetail);

                $allreadyInInvoice = 0;

                foreach ($orderDetail->getInvoiceItems() as $invoiceItem) {
                    $allreadyInInvoice += $invoiceItem->getAmount();
                }

                $amountLeft = $orderDetail->getAmount() - $allreadyInInvoice;

                if ($amountLeft == 0) {
                    continue;
                }

                if (!isset($unbilledOrderDetailsArray[$index])) {
                    $unbilledOrderDetailsArray[$index] = $orderDetail->toArray();
                    $unbilledOrderDetailsArray[$index]['AmountLeft'] = $amountLeft;
                } else {
                    $unbilledOrderDetailsArray[$index]['Amount'] += $orderDetail->getAmount();
                    $unbilledOrderDetailsArray[$index]['AmountLeft'] += $amountLeft;
                }
            }

            $unbilledOrderDetailsArray = array_values($unbilledOrderDetailsArray);
        }

        $return = array(
            'Orderid' => $orderid,
            'All' => $all,
            'UnbilledOrderDetails' => $unbilledOrderDetailsArray,
            'UsedCoupons' => null
        );

        $this->withJson($return);
    }
}
