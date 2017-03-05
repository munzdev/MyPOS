<?php

namespace API\Lib\Interfaces\Models\OIP;

use API\Lib\Interfaces\Models\IModel;
use DateTime;

interface IDistributionGivingOut extends IModel {
    /**
     * @return int
     */
    function getDistributionGivingOutid();

    /**
     * @return DateTime
     */
    function getDate();

    /**
     * @param int $distributionGivingOutid Description
     * @return IDistributionGivingOut Description
     */
    function setDistributionGivingOutid($distributionGivingOutid);

    /**
     * @param DateTime $date Description
     * @return IDistributionGivingOut Description
     */
    function setDate($date);
}