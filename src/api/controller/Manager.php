<?php
namespace Controller;

use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Lib\Request;
use Model;
use MyPOS;

class Manager extends SecurityController
{
    public function __construct()
    {
        parent::__construct();

        $this->a_security = array('GetCallbackList' => MyPOS\USER_ROLE_MANAGER,
                                  'ResetCallback' => MyPOS\USER_ROLE_MANAGER);
    }

    public function GetCallbackListAction()
    {
        $o_users = new Model\Users(Database::GetConnection());

        $a_user = Login::GetCurrentUser();

        return $o_users->GetCallRequestList($a_user['eventid']);
    }

    public function ResetCallbackAction()
    {
        $a_params = Request::ValidateParams(array('userid' => 'numeric'));

        $o_users = new Model\Users(Database::GetConnection());

        return $o_users->SetCallRequest($a_params['userid'], 'true');
    }
}