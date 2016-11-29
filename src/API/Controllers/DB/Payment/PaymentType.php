<?php

namespace API\Controllers\DB\Payment;

use API\Lib\SecurityController;
use API\Models\Payment\PaymentTypeQuery;
use Slim\App;

class PaymentType extends SecurityController
{    
    protected $o_auth;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void {
        $o_paymentTypes = PaymentTypeQuery::create()->find();
        
        $this->o_response->withJson($o_paymentTypes->toArray());
    }
    
}