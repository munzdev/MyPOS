<?php
namespace Controller;

use Lib\Controller;
use Lib\Login;
use Lib\Database;
use Lib\Request;
use Model;

class Users extends Controller
{
    private $o_login;

    public function __construct()
    {
        parent::__construct();

        $this->o_login = new Login(new Model\Users(Database::GetConnection()));
    }

    public function IsLoggedInAction()
    {
        return $this->o_login->IsLoggedIn();
    }

    public function LoginAction()
    {
        $a_params = Request::ValidateParams(array('username' => 'string',
                                                  'password' => 'string')
        );

        $str_username = $a_params['username'];
        $str_password = $a_params['password'];

        $b_remember_me = isset($_POST['rememberMe']);

        return $this->o_login->CheckLogin($str_username, $str_password, $b_remember_me);
    }

    public function LogoutAction()
    {
        $this->o_login->Logout();

        return true;
    }

    public function GetCurrentUserAction()
    {
        $a_user = $this->o_login->GetCurrentUser();

        unset($a_user['password']);
        unset($a_user['autologin_hash']);

        return $a_user;
    }

    public function GetUsersListAction()
    {
        $o_users = new Model\Users(Database::GetConnection());

        $a_user = $this->o_login->GetCurrentUser();

        return $o_users->GetUsers($a_user['eventid']);
    }

    public function CallRequestAction()
    {
        $a_params = Request::ValidateParams(array('reset' => 'bool'));

        $o_users = new Model\Users(Database::GetConnection());

        $a_user = $this->o_login->GetCurrentUser();

        return $o_users->SetCallRequest($a_user['userid'], $a_params['reset']);
    }
}