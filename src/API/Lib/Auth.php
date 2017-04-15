<?php
namespace API\Lib;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\IUserQuery;

class Auth implements IAuth
{
    private $queryClass;

    public function __construct(IUserQuery $queryClass)
    {
        $this->queryClass = $queryClass;
    }

    public function doLogin(string $username) : bool
    {
        $user = $this->findUserObject($username);

        if ($user) {
            $user->setAutologinHash(null);
            $user->setPassword(null);

            $this->setLogin($user);

            return true;
        } else {
            return false;
        }
    }

    public function checkLogin(string $username, string $password) : bool
    {
        $user = $this->findUserObject($username);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                return $this->doLogin($username);
            }
        }

        return false;
    }

    /**
     *
     * @param string $username
     * @return IUser|null
     */
    private function findUserObject(string $username) // : ?User
    {
        $user = $this->queryClass->getActiveEventUserByUsername($username);

        if (!$user->isEmpty()) {
            return $user->getFirst();
        }

        return $this->queryClass->getActiveAdminUserByUsername($username);
    }

    public function setLogin(IUser $user) : void
    {
        $_SESSION['Auth']['IUser'] = serialize($user);
        $_SESSION['Auth']['IEventUser'] = serialize($user->getEventUsers()->getFirst());
    }

    /**
     *
     * @return IUser|null
     */
    public function getCurrentUser() : ?IUser
    {
        static $unserializedUser = null;

        if (isset($_SESSION['Auth'])) {
            if ($unserializedUser) {
                return $unserializedUser;
            }

            $user = unserialize($_SESSION['Auth']['IUser']);
            $eventUser = unserialize($_SESSION['Auth']['IEventUser']);

            $user->getEventUsers()->clear(); // avoid permisson reftching -> user needs to logout and relogin to get new permissions for date integrety
            $user->getEventUsers()->append($eventUser);

            $unserializedUser = $user;

            return $user;
        }

        return null;
    }

    public function isLoggedIn() : bool
    {
        return isset($_SESSION['Auth']);
    }

    public function logout() : void
    {
        $_SESSION['Auth'] = null;
        unset($_SESSION['Auth']);
    }
}
