<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\IModel;

interface IMenuExtra extends IModel {
    /**
     * @return int
     */
    function getMenuExtraid();

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
     * @return int
     */
    function getAvailabilityid();

    /**
     * @return IAvailability
     */
    function getAvailability();

    /**
     * @return int
     */
    function getAvailabilityAmount();

    /**
     *
     * @param int $menuExtraid Description
     * @return IMenuExtra Description
     */
    function setMenuExtraid($menuExtraid);

    /**
     *
     * @param int $eventid Description
     * @return IMenuExtra Description
     */
    function setEventid($eventid);

    /**
     *
     * @param IEvent $event Description
     * @return IMenuExtra Description
     */
    function setEvent($event);

    /**
     *
     * @param string $name Description
     * @return IMenuExtra Description
     */
    function setName($name);

    /**
     *
     * @param int $availabilityid Description
     * @return IMenuExtra Description
     */
    function setAvailabilityid($availabilityid);

    /**
     *
     * @param IAvailability $availability Description
     * @return IMenuExtra Description
     */
    function setAvailability($availability);

    /**
     *
     * @param int $availabilityAmount Description
     * @return IMenuExtra Description
     */
    function setAvailabilityAmount($availabilityAmount);
}