<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;

interface IEventPrinter extends IModel {
    /**
     * @return int
     */
    function getEventPrinterid();

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
    function getType();

    /**
     * @return string
     */
    function getAttr1();

    /**
     * @return string
     */
    function getAttr2();

    /**
     * @return boolean
     */
    function getDefault();

    /**
     * @return int
     */
    function getCharactersPerRow();

    /**
     * @param int $eventPrinterid Description
     * @return IEventPrinter Description
     */
    function setEventPrinterid($eventPrinterid);

    /**
     * @param int $eventid Description
     * @return IEventPrinter Description
     */
    function setEventid($eventid);

    /**
     * @param IEvent $event Description
     * @return IEventPrinter Description
     */
    function setEvent($event);

    /**
     * @param string $name Description
     * @return IEventPrinter Description
     */
    function setName($name);

    /**
     * @param int $type Description
     * @return IEventPrinter Description
     */
    function setType($type);

    /**
     * @param string $attr1 Description
     * @return IEventPrinter Description
     */
    function setAttr1($attr1);

    /**
     * @param string $attr2 Description
     * @return IEventPrinter Description
     */
    function setAttr2($attr2);

    /**
     * @param boolean $default Description
     * @return IEventPrinter Description
     */
    function setDefault($default);

    /**
     * @param int $charactersPerRow Description
     * @return IEventPrinter Description
     */
    function setCharactersPerRow($charactersPerRow);
}