<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;

interface IEventBankinformation extends IModel {
    /**
     * @return int
     */
    function getEventBankinformationid();

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
    function getIban();

    /**
     * @return string
     */
    function getBic();

    /**
     * @return boolean
     */
    function getActive();

    /**
     * @param int $eventBankinformationid Description
     * @return IEventBankinformation Description
     */
    function setEventBankinformationid($eventBankinformationid);

    /**
     * @param int $eventid Description
     * @return IEventBankinformation Description
     */
    function setEventid($eventid);

    /**
     * @param IEvent $event Description
     * @return IEventBankinformation Description
     */
    function setEvent($event);

    /**
     * @param string $name Description
     * @return IEventBankinformation Description
     */
    function setName($name);

    /**
     * @param string $iban
     * @return IEventBankinformation Description
     */
    function setIban($iban);

    /**
     * @param string $bic Description
     * @return IEventBankinformation Description
     */
    function setBic($bic);

    /**
     * @param boolean $active Description
     * @return IEventBankinformation Description
     */
    function setActive($active);
}