<?php

namespace API\Controllers\DB\Invoice;

use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Invoice\IInvoiceTypeQuery;
use API\Lib\SecurityController;
use Slim\App;

class InvoiceType extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $invoiceTypeQuery = $this->container->get(IInvoiceTypeQuery::class);
        $invoiceTypes = $invoiceTypeQuery->find();

        $this->withJson($invoiceTypes->toArray());
    }
}
