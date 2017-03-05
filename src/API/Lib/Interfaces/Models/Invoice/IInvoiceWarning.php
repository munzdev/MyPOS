<?php

namespace API\Lib\Interfaces\Models\Invoice;

use API\Lib\Interfaces\Models\IModel;
use DateTime;

interface IInvoiceWarning extends IModel {
    /**
     * @return int
     */
    function getInvoiceWarningid();

    /**
     * @return int
     */
    function getInvoiceid();

    /**
     * @return IInvoice
     */
    function getInvoice();

    /**
     * @return int
     */
    function getInvoiceWarningTypeid();

    /**
     * @return IInvoiceWarningType
     */
    function getInvoiceWarningType();

    /**
     * @return DateTime
     */
    function getWarningDate();

    /**
     * @return DateTime
     */
    function getMaturityDate();

    /**
     * @return float
     */
    function getWarningValue();

    /**
     *
     * @param int $invoiceWarningid Description
     * @return IInvoiceWarning Description
     */
    function setInvoiceWarningid($invoiceWarningid);

    /**
     *
     * @param int $invoiceid Description
     * @return IInvoiceWarning Description
     */
    function setInvoiceid($invoiceid);

    /**
     *
     * @param IInvoice $invoice Description
     * @return IInvoiceWarning Description
     */
    function setInvoice($invoice);

    /**
     *
     * @param int $invoiceWarningTypeid Description
     * @return IInvoiceWarning Description
     */
    function setInvoiceWarningTypeid($invoiceWarningTypeid);

    /**
     *
     * @param IInvoiceWarningType $invoiceWarningType Description
     * @return IInvoiceWarning Description
     */
    function setInvoiceWarningType($invoiceWarningType);

    /**
     *
     * @param DateTime $warningDate Description
     * @return IInvoiceWarning Description
     */
    function setWarningDate($warningDate);

    /**
     *
     * @param DateTime $maturityDate Description
     * @return IInvoiceWarning Description
     */
    function setMaturityDate($maturityDate);

    /**
     *
     * @param float $extraPrice Description
     * @return IInvoiceWarning Description
     */
    function setExtraPrice($extraPrice);
}