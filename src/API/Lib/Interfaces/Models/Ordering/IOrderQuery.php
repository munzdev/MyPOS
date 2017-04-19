<?php

namespace API\Lib\Interfaces\Models\Ordering;

use API\Lib\Interfaces\Models\IQuery;

interface IOrderQuery extends IQuery {
    function findPKWithAllOrderDetails($orderid): ?IOrder;
    function getNextForDistribution(int $userid, int $eventid, bool $onlyUserTables) : ?IOrder;
    function getInfosForDistribution(int $orderid, array $menuGroupids) : ?IOrder;
    function getNextForTodoList(int $userid, int $eventid, bool $onlyUserTables, int $listAmount = 1, int $orderidToIgnore = 0) : IOrderCollection;
    function getOpenOrdersCount(array $menuGroupids, array $eventTableids, int $minutes = 0) : int;
}