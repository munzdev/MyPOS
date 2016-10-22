<?php
namespace API\Lib;

use Model;
use API\Models\User\UsersQuery;
use API\Lib\RememberMe;

class Auth
{
    private $o_usersQuery;

    public function __construct(UsersQuery $o_usersQuery)
    {
        $this->o_usersQuery = $o_usersQuery;
    }

    public function DoLogin(string $str_username, bool $b_remember_me = false)
    {
        $a_user = $this->o_usersQuery->GetUserDetailsByUsername($str_username);

        if(empty($a_user))
        {
            $a_user = $this->o_usersQuery->GetAdminDetailsByUsername($str_username);
        }

        if($a_user)
        {
            $this->SetLogin($a_user);

            if($b_remember_me)
            {
                    $o_rememberMe = new RememberMe($this->o_usersQuery, $GLOBALS['a_config']['Auth']['RememberMe_PrivateKey']);
                    $o_rememberMe->remember($a_user['userid']);
            }

            return true;
         }
         else
         {
            return false;
         }
    }

    public function CheckLogin(string $str_username, string $str_password, bool $b_rememberMe)
    {
        $a_user = $this->o_usersQuery->GetUserDetailsByUsername($str_username);

        if(empty($a_user))
        {
            $a_user = $this->o_usersQuery->GetAdminDetailsByUsername($str_username);
        }

        if($a_user)
        {
            if(md5($str_password) == $a_user['password'])
            {
                $this->DoLogin($str_username, $b_rememberMe);

                return true;
            }
            else
            {
                return false;
            }
        }

    }

    public function SetLogin(array $a_user)
    {
        $_SESSION['Login'] = $a_user;
    }

    public static function GetCurrentUser()
    {
        return isset($_SESSION['Login']) ? $_SESSION['Login'] : null;
    }

    public static function IsLoggedIn()
    {
        return isset($_SESSION['Login']);
    }

    public function Logout()
    {
        RememberMe::Destroy();
        $_SESSION['Login'] = null;
        unset($_SESSION['Login']);
    }
}