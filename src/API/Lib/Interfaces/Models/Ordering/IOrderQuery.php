<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IQuery;

interface IOrderQuery extends IQuery {
    function findPKWithAllOrderDetails($orderid): IOrder;
    function getNextForDistribution(int $userid, int $eventid, bool $onlyUserTables) : IOrder;
    function getInfosForDistribution(int $orderid, array $menuGroupids) : IOrder;
    function getNextForTodoList(int $orderidToIgnore, int $userid, int $eventid, bool $onlyUserTables, int $listAmount) : IOrderCollection;
    function getOpenOrdersCount(array $menuGroupids, array $eventTableids, ?int $minutes = null) : int;
}