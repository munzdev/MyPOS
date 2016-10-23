<?php

namespace API\Controllers\Users;

use Slim\App;
use API\Lib\Controller;

class Users extends Controller
{    
    protected $o_usersQuery;
    
    public function __construct(App $o_app) {
        parent::__construct($o_app);
        
        $o_app->getContainer()['db'];
    }
    
    protected function GET() : void {
    }
}