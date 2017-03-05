<?php

namespace API\Lib\Interfaces\Models\Invoice;

use API\Lib\Interfaces\Models\IModel;

interface IInvoiceType extends IModel {
    /**
     * @return int
     */
    function getInvoiceTypeid();

    /**
     * @return string
     */
    function getName();

    /**
     *
     * @param int $invoiceTypeid Description
     * @return IInvoiceType Description
     */
    function setInvoiceTypeid($invoiceTypeid);

    /**
     *
     * @param string $name Description
     * @return IInvoiceType Description
     */
    function setName($name);
}