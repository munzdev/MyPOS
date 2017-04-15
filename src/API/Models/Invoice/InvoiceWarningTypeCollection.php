<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningType;
use API\Lib\Interfaces\Models\Invoice\IInvoiceWarningTypeCollection;
use API\Models\Collection;

class InvoiceWarningTypeCollection extends Collection implements IInvoiceWarningTypeCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IInvoiceWarningType::class);
    }
}