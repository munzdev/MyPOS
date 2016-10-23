<?php
namespace API\Lib;

use Model;
use API\Models\User\UsersQuery;
use API\Lib\RememberMe;

class Auth
{
    private $o_usersQuery;
    private $str_privateKey;

    public function __construct(UsersQuery $o_usersQuery, $str_privateKey)
    {
        $this->o_usersQuery = $o_usersQuery;
        $this->str_privateKey = $str_privateKey;
    }

    public function DoLogin(string $str_username, bool $b_rememberMe = false) : bool
    {
        $o_user = $this->FindUserObject($str_username);

        if($o_user)
        {
            $a_user = $o_user->toArray();            
            unset($a_user['Password']);
            unset($a_user['AutologinHash']);
        
            $this->SetLogin($a_user);

            if($b_rememberMe)
            {
                $o_rememberMe = new RememberMe($this->str_privateKey);
                $str_hash = $o_rememberMe->remember($o_user->getUserid());                
                $o_user->setAutologinHash($str_hash);
                $o_user->save();
            }

            return true;
         }
         else
         {
            return false;
         }
    }

    public function CheckLogin(string $str_username, string $str_password, bool $b_rememberMe) : bool
    {
        $o_user = $this->FindUserObject($str_username);
        
        if($o_user)
        {
            if(password_verify($str_password, $o_user->getPassword()))
            {
                return $this->DoLogin($str_username, $b_rememberMe);
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

    public function SetLogin(array $a_user) : void
    {
        $_SESSION['Auth'] = $a_user;
    }
    
    public function GetPrivateKey()
    {
        return $this->str_privateKey;
    }

    public static function GetCurrentUser() // : ?array
    {
        return isset($_SESSION['Auth']) ? $_SESSION['Auth'] : null;
    }

    public static function IsLoggedIn() : bool
    {
        return isset($_SESSION['Auth']);
    }

    public function Logout() : void
    {
        RememberMe::Destroy();
        $_SESSION['Auth'] = null;
        unset($_SESSION['Auth']);
    }
}