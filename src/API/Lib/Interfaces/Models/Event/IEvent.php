<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;
use DateTime;

interface IEvent extends IModel {
    /**
     * @return int
     */
    function getEventid();

    /**
     * @return string
     */
    function getName();

    /**
     * @return DateTime
     */
    function getDate();

    /**
     * @return boolean
     */
    function getActive();

    /**
     * @return DateTime
     */
    function getIsDeleted();

    /**
     * @param int $eventid Description
     * @return IEvent Description
     */
    function setEventid($eventid);

    /**
     * @param string $name Description
     * @return IEvent Description
     */
    function setName($name);

    /**
     * @param DateTime $date
     * @return IEvent Description
     */
    function setDate($date);

    /**
     * @param boolean $active Description
     * @return IEvent Description
     */
    function setActive($active);

    /**
     * @param DateTime $deleted Description
     * @return IEvent Description
     */
    function setIsDeleted($isDeleted);
}