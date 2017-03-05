<?php

namespace API\Lib\Interfaces\Models\DistributionPlace;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\IModel;

interface IDistributionPlace extends IModel {
    /**
     * @return int
     */
    function getDistributionPlaceid();

    /**
     * @return int
     */
    function getEventid();

    /**
     * @return IEvent
     */
    function getEvent();

    /**
     * @return string
     */
    function getName();

    /**
     * @param int $distributionPlaceid Description
     * @return IDistributionPlace Description
     */
    function setDistributionPlaceid($distributionPlaceid);

    /**
     * @param int $eventid Description
     * @return IDistributionPlace Description
     */
    function setEventid($eventid);

    /**
     * @param IEvent $event Description
     * @return IDistributionPlace Description
     */
    function setEvent($event);

    /**
     * @param string $name Description
     * @return IDistributionPlace Description
     */
    function setName($name);
}