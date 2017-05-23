<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\Ordering\OrderInProgressRecievedTableMap;
use API\Models\ORM\Invoice\Map\InvoiceItemTableMap;
use API\Models\ORM\Ordering\Map\OrderDetailTableMap;
use API\Models\ORM\Ordering\OrderDetailQuery as OrderDetailQueryORM;
use Propel\Runtime\ActiveQuery\Criteria;
use stdClass;

interface IOrderDetailQuery extends IQuery {
    function getDistributionUnfinishedByMenuid($menuid) : IOrderDetailCollection;
    function getDistributionUnfinishedByMenuExtraid($menuExtraid) : IOrderDetailCollection;
    function setAvailabilityidByOrderDetailIds(int $availabilityid, array $ids) : int;
    function getVerifiedDistributionUnfinishedWithSpecialExtras(): IOrderDetailCollection;
    public function getByEventid(int $orderDetailid, int $eventid): ?IOrderDetail;
    public function getUnrecievedOfOrder(int $orderid) : IOrderDetailCollection;
    public function findUnbilled($orderid, $eventTable = null): IOrderDetailUnbilledCollection;
    public function getWithDetails(int $orderDetailid): ?IOrderDetail;
    public function getDetailsSum(int $orderDetailid): ?stdClass;
    public function getMenuDetails(int $orderDetailid) : ?IOrderDetail;
    public function getUnverifiedOrders(int $verified, int $eventid): IOrderDetailCollection;
}