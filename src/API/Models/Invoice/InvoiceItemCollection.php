<?php

namespace API\Models\Invoice;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItemCollection;
use API\Models\Collection;

class InvoiceItemCollection extends Collection implements IInvoiceItemCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IInvoiceItem::class);
    }
}