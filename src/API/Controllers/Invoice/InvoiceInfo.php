<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Lib\SecurityController;
use Respect\Validation\Validator as v;
use Slim\App;
use const API\USER_ROLE_INVOICE_OVERVIEW;

class InvoiceInfo extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_INVOICE_OVERVIEW];

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'id' => v::intVal()->positive()
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $invoiceQuery = $this->container->get(IInvoiceQuery::class);
        $user = $auth->getCurrentUser();

        $invoice = $invoiceQuery->getWithDetails($user->getEventUsers()->getFirst()->getEventid(), $this->args['id']);

        $this->withJson($invoice);
    }
}
