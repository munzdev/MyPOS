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
use API\Lib\Interfaces\Models\Ordering\IOrderDetail;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailCollection;
use API\Lib\Interfaces\Models\Ordering\IOrderDetailQuery;
use API\Lib\Interfaces\Models\Payment\ICouponCollection;
use API\Lib\SecurityController;
use API\Lib\StatusCheck;
use API\Models\ORM\Event\EventBankinformationQuery;
use API\Models\ORM\Event\EventContact;
use API\Models\ORM\Event\EventContactQuery;
use API\Models\ORM\Event\EventTableQuery;
use API\Models\ORM\Invoice\Invoice;
use API\Models\ORM\Invoice\InvoiceItem;
use API\Models\ORM\Ordering\OrderDetail;
use API\Models\ORM\Ordering\OrderDetailQuery;
use API\Models\ORM\Payment\Coupon;
use API\Models\ORM\Payment\CouponQuery;
use API\Models\ORM\Payment\Map\CouponTableMap;
use API\Models\ORM\Payment\Map\PaymentCouponTableMap;
use API\Models\ORM\Payment\PaymentCoupon;
use API\Models\ORM\Payment\PaymentRecieved;
use DateTime;
use Exception;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
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

    protected function get() : void
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

        $return = array('Orderid' => $orderid,
                        'All' => $all,
                        'UnbilledOrderDetails' => $unbilledOrderDetailsArray,
                        'UsedCoupons' => null);

        $this->withJson($return);
    }

    public function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $eventContactQuery = $this->container->get(IEventContactQuery::class);
        $eventBankinformationQuery = $this->container->get(IEventBankinformationQuery::class);
        $config = $this->container->get('settings');

        $user = $auth->getCurrentUser();

        $orderid = intval($this->args['id']);
        $all = filter_var($this->args['all'], FILTER_VALIDATE_BOOLEAN);

        $customerEventContact = null;
        if ($this->json['Customer'] !== null) {
            $customerEventContact = $this->container->get(IEventContact::class);
            $jsonToModel->convert($this->json['Customer'], $customerEventContact);
        }

        $invoiceOrderDetails = $this->container->get(IOrderDetailCollection::class);
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

            foreach ($unbilledOrderDetails as $orderDetail) {
                foreach ($invoiceOrderDetails as $orderDetailJson) {
                    $orderDetailJsonArray = $orderDetailJson->toArray();

                    if (empty($orderDetailJsonArray['AmountSelected'])) {
                        continue;
                    }

                    $orderDetailJsonArray['OrderDetailExtras'] = $orderDetailJson->getOrderDetailExtras()->toArray();
                    $orderDetailJsonArray['OrderDetailMixedWiths'] = $orderDetailJson->getOrderDetailMixedWiths()->toArray();
                    $orderDetailJsonArray['InvoiceItems'] = $orderDetailJson->getInvoiceItems()->toArray();

                    $index = $this->buildIndexFromOrderDetail($orderDetail);
                    $indexJson = $this->buildIndexFromOrderDetail($orderDetailJsonArray);

                    if ($index == $indexJson) {
                        $orderDetailObject = OrderDetailQuery::create()->findPk($orderDetail['OrderDetailid']);

                        if ($orderDetailObject->getMenuid() == null && $orderDetailObject->getMenuGroupid() == null) {
                            continue;
                        }

                        if ($orderDetail['AmountLeft'] >= $orderDetailJsonArray['AmountSelected']) {
                            $amount = $orderDetailJsonArray['AmountSelected'];
                            $orderDetailJson->setVirtualColumn("AmountSelected", 0);
                        } else {
                            $amount = $orderDetail['AmountLeft'];
                            $orderDetailJsonArray['AmountSelected'] -= $orderDetail['AmountLeft'];
                            $orderDetailJson->setVirtualColumn("AmountSelected", $orderDetailJsonArray['AmountSelected']);
                        }

                        $invoiceItem = new InvoiceItem();
                        $invoiceItem->setInvoice($invoice);
                        $invoiceItem->setOrderDetail($orderDetailObject);
                        $invoiceItem->setAmount($amount);
                        $invoiceItem->setPrice($orderDetailObject->getSinglePrice());

                        $payed += $orderDetailObject->getSinglePrice() * $amount;

                        if ($orderDetailObject->getMenuid() == null) {
                            $invoiceItem->setDescription($orderDetailObject->getExtraDetail());
                            $invoiceItem->setTax(
                                $orderDetailObject->getMenuGroup()
                                    ->getMenuType()
                                    ->getTax()
                            );
                        } else {
                            $description = '';

                            if ($orderDetailObject->getMenu()->getMenuPossibleSizes()->count() > 1) {
                                $description = $orderDetailObject->getMenu()->getName() . ", ";
                            }

                            if ($orderDetailObject->getOrderDetailMixedWiths()->count() > 1) {
                                $description .= "Gemischt mit: ";

                                foreach ($orderDetailObject->getOrderDetailMixedWiths() as $orderDetailMixedWith) {
                                    $description .= $orderDetailMixedWith->getMenu()->getName() . " - ";
                                }

                                $description = mb_substr($description, 0, -3);
                                $description .= ', ';
                            }

                            foreach ($orderDetailObject->getOrderDetailExtras() as $orderDetailExtra) {
                                $description .= $orderDetailExtra->getMenuPossibleExtra()->getMenuExtra()->getName() . ', ';
                            }

                            if (!empty($orderDetailObject->getExtraDetail())) {
                                $description .= $orderDetailExtra->getExtraDetail() . ', ';
                            }

                            if (mb_strlen($description) > 0) {
                                $description = mb_substr($description, 0, -2);
                            }

                            $invoiceItem->setDescription($description);
                            $invoiceItem->setTax(
                                $orderDetailObject->getMenu()
                                    ->getMenuGroup()
                                    ->getMenuType()
                                    ->getTax()
                            );
                        }

                        $invoiceItem->save();

                        $orderidsToVerify[] = $orderDetailObject->getOrderid();
                    }
                }
            }

            $invoice->setAmount($payed);

            if ($this->json['PaymentTypeid'] == PAYMENT_TYPE_CASH) {
                $invoice->setPaymentFinished(new DateTime());
                $invoice->setAmountRecieved($payed);

                $paymentRecieved = new PaymentRecieved();
                $paymentRecieved->setInvoice($invoice);
                $paymentRecieved->setPaymentTypeid(PAYMENT_TYPE_CASH);
                $paymentRecieved->setUserid($user->getUserid());
                $paymentRecieved->setDate(new DateTime());
                $paymentRecieved->setAmount($payed);
                $paymentRecieved->save();

                foreach ($usedCoupons as $usedCoupon) {
                    $coupon = CouponQuery::create()
                                            ->leftJoinPaymentCoupon()
                                            ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
                                            ->filterByCouponid($usedCoupon->getCouponid())
                                            ->groupBy(CouponTableMap::COL_COUPONID)
                                            ->find()
                                            ->getFirst();

                    $orgPayed = $payed;
                    $value = $coupon->getVirtualColumn('Value');
                    $payed -= $value;

                    if ($payed < 0) {
                        $payed = 0;
                    }

                    $usedValue = $payed > 0 ? $coupon->getValue() : $orgPayed;

                    $paymentCoupon = new PaymentCoupon();
                    $paymentCoupon->setCoupon($coupon);
                    $paymentCoupon->setPaymentRecieved($paymentRecieved);
                    $paymentCoupon->setValueUsed($usedValue);
                    $paymentCoupon->save();
                }
            } elseif ($this->json['PaymentTypeid'] == PAYMENT_TYPE_BANK_TRANSFER && !empty($usedCoupons)) {
                $paymentRecieved = new PaymentRecieved();
                $paymentRecieved->setInvoice($invoice);
                $paymentRecieved->setPaymentTypeid(PAYMENT_TYPE_CASH);
                $paymentRecieved->setUserid($user->getUserid());
                $paymentRecieved->setDate(new DateTime());
                $paymentRecieved->setAmount(0);
                $paymentRecieved->save();

                $amountPayedViaCoupon = 0;

                foreach ($usedCoupons as $usedCoupon) {
                    $coupon = CouponQuery::create()
                                    ->leftJoinPaymentCoupon()
                                    ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
                                    ->filterByCouponid($usedCoupon->getCouponid())
                                    ->groupBy(CouponTableMap::COL_COUPONID)
                                    ->find()
                                    ->getFirst();

                    $orgPayed = $payed;
                    $value = $coupon->getVirtualColumn('Value');
                    $payed -= $value;

                    if ($payed < 0) {
                        $payed = 0;
                    }

                    $usedValue = $payed > 0 ? $coupon->getValue() : $orgPayed;

                    $amountPayedViaCoupon += $usedValue;

                    $paymentCoupon = new PaymentCoupon();
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

            if ($paymentRecieved) {
                $invoice->setVirtualColumn("PaymentRecievedid", $paymentRecieved->getPaymentRecievedid());
            }

            StatusCheck::verifyInvoice($invoice->getInvoiceid());

            $orderidsToVerify = array_unique($orderidsToVerify);
            foreach ($orderidsToVerify as $orderid) {
                StatusCheck::verifyOrder($orderid);
            }

            $this->withJson($invoice->toArray());

            $connection->commit();
        } catch (Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
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

    private function getUnbilledOrderDetails($orderid, $all) : IOrderDetailCollection
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
}
