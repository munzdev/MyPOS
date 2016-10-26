<?php
namespace API\Lib;

use Model;
use API\Models\User\UsersQuery;
use API\Models\User\Base\Users;
use API\Lib\RememberMe;

class Auth
{
    private $o_usersQuery;

    public function __construct(string $str_usersQuery)
    {
        $this->o_usersQuery = $str_usersQuery::create();
    }

    public function DoLogin(string $str_username) : bool
    {
        $o_user = $this->FindUserObject($str_username);

        if($o_user)
        {
            $o_user->setAutologinHash(null);
            $o_user->setPassword(null);
        
            $this->SetLogin($o_user);

            return true;
         }
         else
         {
            return false;
         }
    }

    public function CheckLogin(string $str_username, string $str_password) : bool
    {
        $o_user = $this->FindUserObject($str_username);
        
        if($o_user)
        {
            if(password_verify($str_password, $o_user->getPassword()))
            {
                return $this->DoLogin($str_username);
            }
        }
        
        return false;
    }
    
    private function FindUserObject($str_username) // : ?Base\Users
    {
        $o_user = $this->o_usersQuery->joinEventsUser()
                                     ->useEventsUserQuery()
                                        ->joinEvents()
                                        ->useEventsQuery()
                                            ->filterByActive(true)
                                        ->endUse()
                                     ->endUse()                                        
                                     ->filterByUsername($str_username)
                                     ->filterByActive(true)
                                     ->findOne();
        
        if(!$o_user)
        {
            $o_user = $this->o_usersQuery->filterByUsername($str_username)
                                         ->filterByIsAdmin(true)
                                         ->filterByActive(true)
                                         ->findOne();
        }
        
        return $o_user;
    }

    public function SetLogin(Users $o_user) : void
    {
        $_SESSION['Auth'] = serialize($o_user);
    }    

    public static function GetCurrentUser() // : ?Users
    {
        if(isset($_SESSION['Auth']))
        {
            return unserialize($_SESSION['Auth']);
        }
        
        return null;
    }

    public static function IsLoggedIn() : bool
    {
        return isset($_SESSION['Auth']);
    }

    public function Logout() : void
    {        
        $_SESSION['Auth'] = null;
        unset($_SESSION['Auth']);
    }
}