<?php
namespace API\Lib;

use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\User;

class Auth
{
    private $str_queryClass;

    public function __construct(string $str_queryClass)
    {
        $this->str_queryClass = $str_queryClass;
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
    
    /**
     * 
     * @param string $str_username
     * @return User|null
     */
    private function FindUserObject(string $str_username) // : ?User
    {            
        $str_userRoleFieldName = $this->str_queryClass::getUserRoleFieldName();
        
        $o_user = $this->str_queryClass::create()->withColumn(EventUserTableMap::COL_USER_ROLES, $str_userRoleFieldName)
                                                 ->joinEventUser()
                                                 ->useEventUserQuery()
                                                    ->joinEvent()
                                                    ->useEventQuery()
                                                        ->filterByActive(true)
                                                    ->endUse()
                                                 ->endUse()                                        
                                                 ->filterByUsername($str_username)
                                                 ->filterByActive(true)
                                                 ->findOne();
        
        if(!$o_user)
        {
            $o_user = $this->str_queryClass::create()->withColumn("'0'", $str_userRoleFieldName)
                                                     ->filterByUsername($str_username)
                                                     ->filterByIsAdmin(true)
                                                     ->filterByActive(true)
                                                     ->findOne();
        }
        
        return $o_user;
    }

    public function SetLogin(User $o_user) : void
    {
        $_SESSION['Auth'] = serialize($o_user);
    }    

    /**
     * 
     * @return User|null
     */
    public static function GetCurrentUser() // : ?User
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