<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceCollection;
use API\Models\Collection;

class InvoiceCollection extends Collection implements IInvoiceCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IInvoice::class);
    }
}