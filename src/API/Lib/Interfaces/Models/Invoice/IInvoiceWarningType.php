<?php

namespace API\Lib\Interfaces\Models\Invoice;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\IModel;

interface IInvoiceWarningType extends IModel {
    /**
     * @return int
     */
    function getInvoiceWarningTypeid();

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
    function getExtraPrice();

    /**
     *
     * @param int $invoiceWarningTypeid Description
     * @return IInvoiceWarningType Description
     */
    function setInvoiceWarningTypeid($invoiceWarningTypeid);

    /**
     *
     * @param int $eventid Description
     * @return IInvoiceWarningType Description
     */
    function setEventid($eventid);

    /**
     *
     * @param IEvent $event Description
     * @return IInvoiceWarningType Description
     */
    function setEvent($event);

    /**
     *
     * @param string $name Description
     * @return IInvoiceWarningType Description
     */
    function setName($name);

    /**
     *
     * @param float $extraPrice Description
     * @return IInvoiceWarningType Description
     */
    function setExtraPrice($extraPrice);
}