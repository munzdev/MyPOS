<?php

namespace API\Controllers\DB\Invoice;

use API\Lib\SecurityController;
use API\Models\Invoice\InvoiceTypeQuery;
use Slim\App;

class InvoiceType extends SecurityController
{
    protected $o_auth;

    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    protected function GET() : void {
        $o_invoiceTypes = InvoiceTypeQuery::create()->find();

        $this->o_response->withJson($o_invoiceTypes->toArray());
    }

}