<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;

interface IEventTable extends IModel {
    /**
     * @return int
     */
    function getEventTableid();

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
     * @return string
     */
    function getData();

    /**
     * @param int $eventTableid Description
     * @return IEventTable Description
     */
    function setEventTableid($eventTableid);

    /**
     * @param int $eventid Description
     * @return IEventTable Description
     */
    function setEventid($eventid);

    /**
     * @param IEvent $event Description
     * @return IEventTable Description
     */
    function setEvent($event);

    /**
     * @param string $name Description
     * @return IEventTable Description
     */
    function setName($name);

    /**
     * @param string $data
     * @return IEventTable Description
     */
    function setData($data);
}