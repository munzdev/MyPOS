<?php
namespace API\Lib;

use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\Map\UserTableMap;
use API\Models\User\User;
use API\Models\User\UserQuery;
use Propel\Runtime\Collection\Collection;

class Auth
{
    private $o_queryClass;

    public function __construct(UserQuery $o_queryClass)
    {
        $this->o_queryClass = $o_queryClass;
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
        $o_user = $this->o_queryClass->create()->useEventUserQuery()
                                                  ->useEventQuery()
                                                      ->filterByActive(true)
                                                  ->endUse()
                                               ->endUse()         
                                               ->filterByUsername($str_username)
                                               ->filterByActive(true)
                                               ->with(EventUserTableMap::getTableMap()->getPhpName())
                                               ->find();
        
        if(!$o_user->isEmpty())
            return $o_user->getFirst();
                        
        return $this->o_queryClass->create()->filterByUsername($str_username)
                                            ->filterByIsAdmin(true)
                                            ->filterByActive(true)
                                            ->findOne();        
    }

    public function SetLogin(User $o_user) : void
    {
        $_SESSION['Auth'][UserTableMap::getTableMap()->getPhpName()] = serialize($o_user);
        $_SESSION['Auth'][EventUserTableMap::getTableMap()->getPhpName()] = serialize($o_user->getEventUser());
    }    

    /**
     * 
     * @return User|null
     */
    public static function GetCurrentUser() // : ?User
    {
        if(isset($_SESSION['Auth']))
        {
            $o_user = unserialize($_SESSION['Auth'][UserTableMap::getTableMap()->getPhpName()]);
            $o_eventUser = unserialize($_SESSION['Auth'][EventUserTableMap::getTableMap()->getPhpName()]);
            
            $o_collection = new Collection([$o_eventUser]);
            
            $o_user->setEventUsers($o_collection);
            
            return $o_user;
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