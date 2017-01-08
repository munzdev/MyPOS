<?php

namespace API\Controllers\Order;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Invoice\Map\InvoiceItemTableMap;
use API\Models\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\Ordering\Map\OrderDetailTableMap;
use API\Models\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_OVERVIEW;

class OrderInfo extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $this->a_security = ['GET' => USER_ROLE_ORDER_OVERVIEW];

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators = array(
            'id' => v::intVal()->positive()
        );

        $this->validate($a_validators, $this->a_args);
    }

    protected function GET() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_order_info = OrderQuery::create()
                        ->useOrderDetailQuery()
                            ->useInvoiceItemQuery(null, ModelCriteria::LEFT_JOIN)
                                ->useInvoiceQuery(null, ModelCriteria::LEFT_JOIN)
                                    ->filterByCanceled(null)
                                ->endUse()
                            ->endUse()
                        ->endUse()
                        ->withColumn("SUM(" . OrderDetailTableMap::COL_AMOUNT . " * " . OrderDetailTableMap::COL_SINGLE_PRICE . ")", "price")
                        ->withColumn("COUNT(" . OrderDetailTableMap::COL_ORDER_DETAILID . ") - COUNT(" . InvoiceItemTableMap::COL_INVOICE_ITEMID . ")", "open")
                        ->withColumn("SUM(" . InvoiceItemTableMap::COL_AMOUNT . " * " . InvoiceItemTableMap::COL_PRICE . ")", "amountBilled")
                        ->groupByOrderid()
                        ->findByOrderid($this->a_args['id'])
                        ->getFirst();

        $o_order_detail_info = OrderQuery::create()
                                ->joinWithEventTable()
                                ->joinWithOrderDetail()
                                ->joinWithUser()
                                ->leftJoinWithOrderInProgress()
                                ->useOrderDetailQuery()
                                    ->useOrderInProgressRecievedQuery(null, Criteria::LEFT_JOIN)
                                        ->leftJoinWithDistributionGivingOut()
                                    ->endUse()
                                    ->leftJoinWithMenuSize()
                                    ->leftJoinWithOrderDetailExtra()
                                    ->leftJoinWithOrderDetailMixedWith()
                                    ->with(OrderInProgressRecievedTableMap::getTableMap()->getPhpName())
                                ->endUse()
                                ->setFormatter(ModelCriteria::FORMAT_ARRAY)
                                ->findByOrderid($this->a_args['id'])
                                ->getFirst();

        $o_order_detail_info['price'] = $o_order_info->getVirtualColumn('price');
        $o_order_detail_info['open'] = $o_order_info->getVirtualColumn('open');
        $o_order_detail_info['amountBilled'] = $o_order_info->getVirtualColumn('amountBilled');

        // Dont send secure critical datas
        $o_order_detail_info['User']['Password'] = null;
        $o_order_detail_info['User']['AutologinHash'] = null;
        $o_order_detail_info['User']['IsAdmin'] = null;
        $o_order_detail_info['User']['CallRequest'] = null;

        $this->withJson($o_order_detail_info);
    }

}