<?php

namespace API\Lib\Interfaces\Models\OIP;

use API\Lib\Interfaces\Models\IQuery;
use API\Lib\Interfaces\Models\Ordering\IOrder;
use API\Lib\Interfaces\Models\User\IUser;

interface IOrderInProgressQuery extends IQuery {
    function getActiveByUserAndOrder(IUser $user, IOrder $order) : IOrderInProgressCollection;
    function getOpenOrderInProgress(int $userid, int $eventid): ?IOrderInProgress;
}