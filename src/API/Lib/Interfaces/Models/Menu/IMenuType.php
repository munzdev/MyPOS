<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\IModel;

interface IMenuType extends IModel {
    /**
     * @return int
     */
    function getMenuTypeid();

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
    function getTax();

    /**
     * @return boolean
     */
    function getAllowMixing();

    /**
     * @return IMenuGroupCollection 
     */
    function getMenuGroups();

    /**
     *
     * @param int $menuTypeid Description
     * @return IMenuType Description
     */
    function setMenuTypeid($menuTypeid);

    /**
     *
     * @param int $eventid Description
     * @return IMenuType Description
     */
    function setEventid($eventid);

    /**
     *
     * @param IEvent $event Description
     * @return IMenuType Description
     */
    function setEvent($event);

     /**
     *
     * @param string $name Description
     * @return IMenuType Description
     */
    function setName($name);

    /**
     *
     * @param int $tax Description
     * @return IMenuType Description
     */
    function setTax($tax);

    /**
     *
     * @param boolean $allowMixing Description
     * @return IMenuType Description
     */
    function setAllowMixing($allowMixing);
}