<?php
namespace Controller;

use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Model;

class Distribution extends SecurityController
{
    public function __construct()
    {
        parent::__construct();

        $this->a_security = array('GetNextOrder' => MyPOS\USER_ROLE_DISTRIBUTION);
    }

    public function GetNextOrderAction()
    {

    }
}