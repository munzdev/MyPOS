<?php
namespace API\Lib;

use API\Models\Event\Map\EventUserTableMap;
use API\Models\User\Map\UserTableMap;
use API\Models\User\User;
use API\Models\User\UserQuery;
use Propel\Runtime\Collection\Collection;

class Auth
{
    private $queryClass;

    public function __construct(UserQuery $queryClass)
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
     * @return User|null
     */
    private function findUserObject(string $username) // : ?User
    {
        $user = $this->queryClass->create()->useEventUserQuery()
            ->useEventQuery()
            ->filterByActive(true)
            ->endUse()
            ->endUse()
            ->filterByUsername($username)
            ->filterByActive(true)
            ->with(EventUserTableMap::getTableMap()->getPhpName())
            ->find();

        if (!$user->isEmpty()) {
            return $user->getFirst();
        }

        return $this->queryClass->create()->filterByUsername($username)
            ->filterByIsAdmin(true)
            ->filterByActive(true)
            ->findOne();
    }

    public function setLogin(User $user) : void
    {
        $_SESSION['Auth'][UserTableMap::getTableMap()->getPhpName()] = serialize($user);
        $_SESSION['Auth'][EventUserTableMap::getTableMap()->getPhpName()] = serialize($user->getEventUser());
    }

    /**
     *
     * @return User|null
     */
    public static function getCurrentUser() // : ?User
    {
        static $unserializedUser = null;

        if (isset($_SESSION['Auth'])) {
            if ($unserializedUser) {
                return $unserializedUser;
            }

            $user = unserialize($_SESSION['Auth'][UserTableMap::getTableMap()->getPhpName()]);
            $eventUser = unserialize($_SESSION['Auth'][EventUserTableMap::getTableMap()->getPhpName()]);

            $collection = new Collection([$eventUser]);

            $user->setEventUsers($collection);

            $unserializedUser = $user;

            return $user;
        }

        return null;
    }

    public static function isLoggedIn() : bool
    {
        return isset($_SESSION['Auth']);
    }

    public function logout() : void
    {
        $_SESSION['Auth'] = null;
        unset($_SESSION['Auth']);
    }
}
