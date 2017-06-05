<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\IModel;

interface IMenuSize extends IModel {
    /**
     * @return int
     */
    function getMenuSizeid();

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
     * @return float
     */
    function getFactor();

    /**
     * @return \DateTime
     */
    function getIsDeleted();

    /**
     *
     * @param int $menuSizeid Description
     * @return IMenuSize Description
     */
    function setMenuSizeid($menuSizeid);

    /**
     *
     * @param int $eventid Description
     * @return IMenuSize Description
     */
    function setEventid($eventid);

    /**
     *
     * @param IEvent $event Description
     * @return IMenuSize Description
     */
    function setEvent($event);

     /**
     *
     * @param string $name Description
     * @return IMenuSize Description
     */
    function setName($name);

    /**
     *
     * @param float $factor Description
     * @return IMenuSize Description
     */
    function setFactor($factor);

    /**
     *
     * @param \DateTime $deleted Description
     * @return IMenuSize Description
     */
    function setIsDeleted($isDeleted);
}