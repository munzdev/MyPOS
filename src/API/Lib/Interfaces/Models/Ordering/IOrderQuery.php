<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IQuery;
use API\Models\ORM\Ordering\Map\OrderTableMap;
use API\Models\ORM\Ordering\OrderQuery as OrderQueryORM;

interface IOrderQuery extends IQuery {
    function findPKWithAllOrderDetails($orderid): ?IOrder;
    function getNextForDistribution(int $userid, int $eventid, bool $onlyUserTables) : ?IOrder;
    function getInfosForDistribution(int $orderid, array $menuGroupids) : ?IOrder;
    function getNextForTodoList(int $userid, int $eventid, bool $onlyUserTables, int $listAmount = 1, int $orderidToIgnore = 0) : IOrderCollection;
    function getOpenOrdersCount(array $menuGroupids, array $eventTableids, int $minutes = 0) : int;
    public function getWithEventTableAndUser(int $orderid) : ?IOrder;
    public function findWithPagingAndSearch($offset, $limit, $eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo) : IOrderCollection;
    public function getOrderCountBySearch($eventid, $status, $orderid, $tablenr, $userid, $dateFrom, $dateTo) : int;
    public function getMaxPriority(int $eventid): int;
    public function getDetails($orderid) : \stdClass;
    public function getOrderDetails($orderid) : ?IOrder;
    public function getWithModifyDetails(int $orderid) : ?IOrder;
    public function setFirstPriority(int $eventid, int $orderid) : IOrder;
}