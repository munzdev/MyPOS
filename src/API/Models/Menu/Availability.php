<?php

namespace API\Models\Menu;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Menu\IAvailability;
use API\Models\Model;
use API\Models\ORM\Menu\Availability as AvailabilityORM;

/**
 * Skeleton subclass for representing a row from the 'availability' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Availability extends Model implements IAvailability
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new AvailabilityORM());
    }

    public function getAvailabilityid(): int
    {
        return $this->model->getAvailabilityid();
    }

    public function getName(): string
    {
        return $this->model->getName();
    }

    public function setAvailabilityid($availabilityid): IAvailability
    {
        $this->model->setAvailabilityid($availabilityid);
        return $this;
    }

    public function setName($name): IAvailability
    {
        $this->model->setName($name);
        return $this;
    }

}
