<?php

namespace API\Lib\Interfaces\Models\OIP;

use API\Lib\Interfaces\Models\IQuery;

interface IDistributionGivingOutQuery extends IQuery {
    public function getWithOrderDetails(int $distributionGivingOutid) : ?IDistributionGivingOut;
}