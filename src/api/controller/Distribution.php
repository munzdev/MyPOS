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

    public function GetOrderAction()
    {
        $a_user = Login::GetCurrentUser();

        $o_distribution = new Model\Distribution(Database::GetConnection());

        $a_order = $o_distribution->GetOrder($a_user['eventid'], $a_user['userid']);
    }
}