<?php

namespace API\Models\OIP;

use API\Lib\Container;
use API\Lib\Interfaces\Models\OIP\IDistributionGivingOut;
use API\Models\Model;
use API\Models\ORM\OIP\DistributionGivingOut as DistributionGivingOutORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'distribution_giving_out' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class DistributionGivingOut extends Model implements IDistributionGivingOut
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new DistributionGivingOutORM());
    }

    public function getDate(): DateTime
    {
        return $this->model->getDate();
    }

    public function getDistributionGivingOutid(): int
    {
        return $this->model->getDistributionGivingOutid();
    }

    public function setDate($date): IDistributionGivingOut
    {
        $this->model->setDate($date);
        return $this;
    }

    public function setDistributionGivingOutid($distributionGivingOutid): IDistributionGivingOut
    {
        $this->model->setDistributionGivingOutid($distributionGivingOutid);
        return $this;
    }

}
