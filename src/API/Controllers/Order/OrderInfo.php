<?php

namespace API\Controllers\Order;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Models\ORM\Invoice\Map\InvoiceItemTableMap;
use API\Models\ORM\OIP\Map\OrderInProgressRecievedTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
use API\Models\ORM\Ordering\OrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_ORDER_OVERVIEW;

class OrderInfo extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_ORDER_OVERVIEW];

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'id' => v::intVal()->positive()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {
        $orderInfo = OrderQuery::create()
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
                        ->findByOrderid($this->args['id'])
                        ->getFirst();

        $orderDetailInfo = OrderQuery::create()
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
                                ->findByOrderid($this->args['id'])
                                ->getFirst();

        $orderDetailInfo['price'] = $orderInfo->getVirtualColumn('price');
        $orderDetailInfo['open'] = $orderInfo->getVirtualColumn('open');
        $orderDetailInfo['amountBilled'] = $orderInfo->getVirtualColumn('amountBilled');

        // Dont send secure critical datas
        $orderDetailInfo['User']['Password'] = null;
        $orderDetailInfo['User']['AutologinHash'] = null;
        $orderDetailInfo['User']['IsAdmin'] = null;
        $orderDetailInfo['User']['CallRequest'] = null;

        $this->withJson($orderDetailInfo);
    }
}
