<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarning;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningCollection;
use API\Models\Collection;

class InvoiceWarningCollection extends Collection implements IInvoiceWarningCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IInvoiceWarning::class);
    }
}