<?php

namespace API\Controllers\DB\Invoice;

use API\Lib\SecurityController;
use API\Models\Invoice\InvoiceTypeQuery;
use Slim\App;

class InvoiceType extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $invoiceTypes = InvoiceTypeQuery::create()->find();

        $this->withJson($invoiceTypes->toArray());
    }
}
