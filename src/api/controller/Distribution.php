<?php
namespace Controller;

use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Model;
use MyPOS;

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
        $a_config = $GLOBALS['a_config'];

        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);

        try
        {
            $o_db->beginTransaction();

            $a_order = $o_distribution->GetOrder($a_user['eventid'],
                                                 $a_user['userid'],
                                                 $a_config['App']['Distribution']['OnStandbyAssistOtherDistributionPlaces']);

            $o_db->commit();

            return $a_order;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }
}