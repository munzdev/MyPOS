<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\ORM\Ordering\OrderDetailQuery as OrderDetailQueryORM;

interface IOrderDetailQuery extends IQuery {
    function getDistributionUnfinishedByMenuid($menuid) : IOrderDetailCollection;
    function getDistributionUnfinishedByMenuExtraid($menuExtraid) : IOrderDetailCollection;
    function setAvailabilityidByOrderDetailIds(int $availabilityid, array $ids) : int;
    function getVerifiedDistributionUnfinishedWithSpecialExtras(): IOrderDetailCollection;
    public function getByEventid(int $orderDetailid, int $eventid): ?IOrderDetail;
    public function getUnrecievedOfOrder(int $orderid) : IOrderDetailCollection;
    public function findUnbilled($orderid, $eventTable = null) : IOrderDetailCollection;
}