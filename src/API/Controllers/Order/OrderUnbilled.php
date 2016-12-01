<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventTableQuery;
use API\Models\Invoice\Customer;
use API\Models\Invoice\Invoice;
use API\Models\Invoice\InvoiceItem;
use API\Models\Ordering\OrderDetail;
use API\Models\Ordering\OrderDetailQuery;
use API\Models\Payment\Base\PaymentQuery;
use API\Models\Payment\Coupon;
use API\Models\Payment\CouponQuery;
use API\Models\Payment\Map\CouponTableMap;
use API\Models\Payment\Map\PaymentCouponTableMap;
use API\Models\Payment\Payment;
use API\Models\Payment\PaymentCoupon;
use DateTime;
use Exception;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Propel;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\PAYMENT_TYPE_CASH;
use const API\USER_ROLE_ORDER_ADD;
use function mb_strlen;
use function mb_substr;

class OrderUnbilled extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $this->a_security = ['GET' => USER_ROLE_ORDER_ADD];
        
        $o_app->getContainer()['db'];
    }
    
    function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive(),
            'all' => v::boolVal()
        );
        
        $this->validate($a_validators, $this->a_args);       
    }
    
    protected function GET() : void  {                
        $i_orderid = intval($this->a_args['id']);
        $b_all = filter_var($this->a_args['all'], FILTER_VALIDATE_BOOLEAN);
        
        $o_unbilledOrderDetails = $this->getUnbilledOrderDetails($i_orderid, $b_all);
        $a_unbilledOrderDetails = array();
        
        // if all order from table are returned, merge same order types
        if($o_unbilledOrderDetails->count() > 0) {
            foreach($o_unbilledOrderDetails as $a_order_detail) {
                $str_index = $this->buildIndexFromOrderDetail($a_order_detail);                                

                if(!isset($a_unbilledOrderDetails[$str_index]))
                {
                    $a_unbilledOrderDetails[$str_index] = $a_order_detail;
                }
                else
                {
                    $a_unbilledOrderDetails[$str_index]['Amount'] += $a_order_detail['Amount'];
                    $a_unbilledOrderDetails[$str_index]['AmountLeft'] += $a_order_detail['AmountLeft'];
                }
            }
            
            $a_unbilledOrderDetails = array_values($a_unbilledOrderDetails);
        }
        
        $a_return = array('Orderid' => $i_orderid,
                          'All' => $b_all,
                          'UnbilledOrderDetails' => $a_unbilledOrderDetails,
                          'UsedCoupons' => null);
        
        $this->o_response->withJson($a_return);
    }
    
    function POST() : void{
        $o_user = Auth::GetCurrentUser();      
        
        $i_orderid = intval($this->a_args['id']);
        $b_all = filter_var($this->a_args['all'], FILTER_VALIDATE_BOOLEAN);
        
        $o_customer = null;
        if($this->a_json['Customer'] !== null) {
            $o_customer = (new Customer)->fromArray($this->a_json['Customer']);
        }
        
        $o_invoiceOrderDetails = new ObjectCollection();
        $o_invoiceOrderDetails->setModel(OrderDetail::class);
        $this->jsonToPropel($this->a_json['UnbilledOrderDetails'], $o_invoiceOrderDetails);
        
        $o_usedCoupons = new ObjectCollection();
        $o_usedCoupons->setModel(Coupon::class);
        $this->jsonToPropel($this->a_json['UsedCoupons'], $o_usedCoupons);
        
        $o_unbilledOrderDetails = $this->getUnbilledOrderDetails($i_orderid, $b_all);
        
        $o_connection = Propel::getConnection();
        $o_connection->beginTransaction();        
        
        try {        
            $o_invoice = new Invoice();
            $o_invoice->setCashierUserid($o_user->getUserid());
            $o_invoice->setDate(new DateTime());
            
            if($o_customer)
                $o_invoice->setCustomer($o_customer);
                      
            $o_invoice->save();
            
            $i_payed = 0;

            foreach($o_unbilledOrderDetails as $a_order_detail) {
                foreach($o_invoiceOrderDetails as $o_order_detail_json) {                    
                    $a_order_detail_json = $o_order_detail_json->toArray();          
                    
                    if(!isset($a_order_detail_json['AmountSelected']))
                        $a_order_detail_json['AmountSelected'] = 0;
                    
                    if($a_order_detail_json == 0)
                        continue;
                    
                    $a_order_detail_json['OrderDetailExtras'] = $o_order_detail_json->getOrderDetailExtras()->toArray();
                    $a_order_detail_json['OrderDetailMixedWiths'] = $o_order_detail_json->getOrderDetailMixedWiths()->toArray();
                    $a_order_detail_json['InvoiceItems'] = $o_order_detail_json->getInvoiceItems()->toArray();
                                       
                    $str_index = $this->buildIndexFromOrderDetail($a_order_detail);
                    $str_index_json = $this->buildIndexFromOrderDetail($a_order_detail_json);

                    if($str_index == $str_index_json) {
                        $o_order_detail = OrderDetailQuery::create()->findPk($a_order_detail['OrderDetailid']);
                        
                        if($o_order_detail->getMenuid() == null && $o_order_detail->getMenuGroupid() == null) 
                            continue;
                        
                        if($a_order_detail['AmountLeft'] >= $a_order_detail_json['AmountSelected']) {
                            $i_amount = $a_order_detail_json['AmountSelected'];
                        } else {
                            $i_amount = $a_order_detail['AmountLeft'];
                            $a_order_detail_json['AmountSelected'] -= $a_order_detail['AmountLeft'];
                        }
                        
                        $o_invoiceItem = new InvoiceItem();
                        $o_invoiceItem->setInvoice($o_invoice);
                        $o_invoiceItem->setOrderDetail($o_order_detail);
                        $o_invoiceItem->setAmount($i_amount);
                        $o_invoiceItem->setPrice($o_order_detail->getSinglePrice());
                        
                        $i_payed += $o_order_detail->getSinglePrice() * $i_amount;

                        if($o_order_detail->getMenuid() == null) {
                            var_dump();
                            $o_invoiceItem->setDescription($o_order_detail->getExtraDetail());
                            $o_invoiceItem->setTax($o_order_detail->getMenuGroup()
                                                                  ->getMenuType()
                                                                  ->getTax());
                        } else {
                            $str_description = '';
                            
                            if($o_order_detail->getMenu()->getMenuPossibleSizes()->count() > 1)
                                $str_description = $o_order_detail->getMenu()->getName() . ", ";

                            if($o_order_detail->getOrderDetailMixedWiths()->count() > 1) {
                                $str_description .= "Gemischt mit: ";

                                foreach($o_order_detail->getOrderDetailMixedWiths() as $o_orderDetailMixedWith) {
                                    $str_description .= $o_orderDetailMixedWith->getMenu()->getName() . " - ";
                                }

                                $str_description = mb_substr($str_description, 0, -3);
                                $str_description .= ', ';
                            }

                            foreach($o_order_detail->getOrderDetailExtras() as $o_orderDetailExtra) {
                                $str_description .= $o_orderDetailExtra->getMenuPossibleExtra()->getMenuExtra()->getName() . ', ';
                            }

                            if(!empty($o_order_detail->getExtraDetail())) 
                                $str_description .= $o_orderDetailExtra->getExtraDetail() . ', ';

                            if(mb_strlen($str_description) > 0) {
                                $str_description = mb_substr($str_description, 0, -2);
                            }

                            $o_invoiceItem->setDescription($str_description);
                            $o_invoiceItem->setTax($o_order_detail->getMenu()
                                                                  ->getMenuGroup()
                                                                  ->getMenuType()
                                                                  ->getTax());
                        }

                        $o_invoiceItem->save();
                    }
                }
            }
            
            $o_payment = new Payment();
            $o_payment->setPaymentTypeid($this->a_json['PaymentTypeid']);
            $o_payment->setInvoice($o_invoice);
            $o_payment->setCreated(new DateTime());
            $o_payment->setAmount($i_payed);
            
            if($o_payment->getPaymentTypeid() == PAYMENT_TYPE_CASH) {
                $o_payment->setRecieved(new DateTime());
                $o_payment->setAmountRecieved($i_payed);
            }
            
            $o_payment->save();
            
            // somehow $o_payment doesn't get updated with the correct id
            // quick & dirty fix: fetch created entry
            $o_payment = PaymentQuery::create()->findPk($o_connection->lastInsertId());
            
            foreach($o_usedCoupons as $o_usedCoupon) {
                
                $o_coupon = CouponQuery::create()
                                ->leftJoinPaymentCoupon()
                                ->withColumn(CouponTableMap::COL_VALUE . ' - SUM(IFNULL(' . PaymentCouponTableMap::COL_VALUE_USED . ', 0))', 'Value')
                                ->filterByCouponid($o_usedCoupon->getCouponid())
                                ->groupBy(CouponTableMap::COL_COUPONID)
                                ->find()
                                ->getFirst();

                $i_orgPayed = $i_payed;
                $i_value = $o_coupon->getVirtualColumn('Value');
                $i_payed -= $i_value;

                if($i_payed < 0)
                    $i_payed;

                $i_usedValue = $i_payed > 0 ? $o_coupon->getValue() : $i_orgPayed;

                $o_paymentCoupon = new PaymentCoupon();
                $o_paymentCoupon->setCoupon($o_coupon);
                $o_paymentCoupon->setPayment($o_payment);
                $o_paymentCoupon->setValueUsed($i_usedValue);                
                $o_paymentCoupon->save();                
            }
            
            $this->o_response->withJson($o_invoice->getInvoiceid());

            $o_connection->commit();
        } catch(Exception $o_exception) {
            $o_connection->rollBack();
            throw $o_exception;
        }
    }
    
    private function buildIndexFromOrderDetail(&$a_order_detail) : string {
        $str_index = $a_order_detail['Menuid'] . '-' .
                     $a_order_detail['SinglePrice'] . '-' .
                     $a_order_detail['ExtraDetail'] . '-' .
                     $a_order_detail['MenuSizeid'] . '-';

        foreach($a_order_detail['OrderDetailExtras'] as $i_key => $a_order_detail_extra) {
            if(empty($a_order_detail_extra)) {
                unset($a_order_detail['OrderDetailExtras'][$i_key]);
                continue;
            }

            $str_index .= $a_order_detail_extra['MenuPossibleExtraid'];
        }

        $str_index .= '-';

        foreach($a_order_detail['OrderDetailMixedWiths'] as $i_key => $a_order_detail_mixed_with) {
            if(empty($a_order_detail_mixed_with)) {
                unset($a_order_detail['OrderDetailMixedWiths'][$i_key]);
                continue;
            }
            $str_index .= $a_order_detail_mixed_with['Menuid'];
        }
        
        $i_allready_in_invoice = 0;
                
        foreach($a_order_detail['InvoiceItems'] as $i_key => $a_invoice_item) {
            if(empty($a_invoice_item)) {
                unset($a_order_detail['InvoiceItems'][$i_key]);
                continue;
            }
            $i_allready_in_invoice += $a_invoice_item['Amount'];
        }

        $a_order_detail['AmountLeft'] = $a_order_detail['Amount'] - $i_allready_in_invoice;
        
        return $str_index;
    }
    
    private function getUnbilledOrderDetails($i_orderid, $b_all)
    {
        $o_eventTable = null;
        if($b_all) {
            $o_eventTable = EventTableQuery::create()
                                            ->useOrderQuery()
                                               ->filterByOrderid($i_orderid)
                                            ->endUse()
                                            ->findOne();
        }
        
        $o_unbilledOrderDetails = OrderDetailQuery::create()
                                                    ->_if($b_all)
                                                        ->useOrderQuery()
                                                            ->filterByEventTable($o_eventTable)
                                                        ->endUse()
                                                    ->_else()
                                                        ->filterByOrderid($i_orderid)
                                                    ->_endIf()
                                                    ->leftJoinWithMenuSize()
                                                    ->leftJoinWithOrderDetailExtra()                                        
                                                    ->leftJoinWithOrderDetailMixedWith()                                       
                                                    ->leftJoinWithInvoiceItem()
                                                    ->filterByInvoiceFinished(null)        
                                                    ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                                    ->find();
        
        return $o_unbilledOrderDetails;
    }
    
}