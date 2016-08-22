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
                                  'ResetCallback' => MyPOS\USER_ROLE_MANAGER,
                                  'GetCheckList' => MyPOS\USER_ROLE_MANAGER,
                                  'SetCheckListItem' => MyPOS\USER_ROLE_MANAGER);
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

    public function GetCheckListAction()
    {
        $a_params = Request::ValidateParams(array('verified' => 'numeric'));

        $a_user = Login::GetCurrentUser();

        $o_orders = new Model\Orders(Database::GetConnection());

        return $o_orders->GetSpecialExtraList($a_user['eventid'], $a_params['verified']);
    }

    public function SetCheckListItemAction()
    {
        $a_params = Request::ValidateParams(array('orders_details_special_extraid' => 'numeric',
                                                  'single_price' => 'numeric',
                                                  'menu_groupid' => 'numeric',
                                                  'availability' => 'string',
                                                  'availability_amount' => 'string'));

        $a_user = Login::GetCurrentUser();

        $o_orders = new Model\Orders(Database::GetConnection());

        return $o_orders->SetSpexialExtraDetails($a_params['orders_details_special_extraid'],
                                                 $a_params['single_price'],
                                                 $a_params['menu_groupid'],
                                                 $a_params['availability'],
                                                 $a_params['availability_amount'],
                                                 $a_user['userid']);
    }
}