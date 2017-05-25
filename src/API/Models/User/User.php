<?php

namespace API\Models\User;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEventUserCollection;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\User\User as UserORM;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class User extends Model implements IUser
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new UserORM());
    }

    public function getActive(): bool
    {
        return $this->model->getActive();
    }

    public function getAutologinHash(): string
    {
        return $this->model->getAutologinHash();
    }

    public function getCallRequest()
    {
        return $this->model->getCallRequest();
    }

    public function getEventUsers(): IEventUserCollection
    {
        $eventUsers = $this->model->getEventUsers();

        $eventUserCollection = $this->container->get(IEventUserCollection::class);
        $eventUserCollection->setCollection($eventUsers);

        return $eventUserCollection;
    }

    public function getFirstname(): string
    {
        return $this->model->getFirstname();
    }

    public function getIsAdmin(): bool
    {
        return $this->model->getIsAdmin();
    }

    public function getLastname(): string
    {
        return $this->model->getLastname();
    }

    public function getPassword(): string
    {
        return $this->model->getPassword();
    }

    public function getPhonenumber(): string
    {
        return $this->model->getPhonenumber();
    }

    public function getUserid(): int
    {
        return $this->model->getUserid();
    }

    public function getUsername(): string
    {
        return $this->model->getUsername();
    }

    public function setActive($active): IUser
    {
        $this->model->setActive($active);
        return $this;
    }

    public function setAutologinHash($autologinHash): IUser
    {
        $this->model->setAutologinHash($autologinHash);
        return $this;
    }

    public function setCallRequest($callRequest): IUser
    {
        $this->model->setCallRequest($callRequest);
        return $this;
    }

    public function setFirstname($firstname): IUser
    {
        $this->model->setFirstname($firstname);
        return $this;
    }

    public function setIsAdmin($isAdmin): IUser
    {
        $this->model->setIsAdmin($isAdmin);
        return $this;
    }

    public function setLastname($lastname): IUser
    {
        $this->model->setLastname($lastname);
        return $this;
    }

    public function setPassword($password): IUser
    {
        $this->model->setPassword($password);
        return $this;
    }

    public function setPhonenumber($phonenumber): IUser
    {
        $this->model->setPhonenumber($phonenumber);
        return $this;
    }

    public function setUserid($userid): IUser
    {
        $this->model->setUserid($userid);
        return $this;
    }

    public function setUsername($username): IUser
    {
        $this->model->setUsername($username);
        return $this;
    }
}
