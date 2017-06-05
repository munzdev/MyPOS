<?php

namespace API\Models\Event;

use API\Lib\Container;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventUser;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use API\Models\ORM\Event\EventUser as EventUserORM;

/**
 * Skeleton subclass for representing a row from the 'event_user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class EventUser extends Model implements IEventUser
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new EventUserORM());
    }

    public function getBeginMoney(): float
    {
        return $this->model->getBeginMoney();
    }

    public function getEvent(): IEvent
    {
        $event = $this->model->getEvent();

        $eventModel = $this->container->get(IEvent::class);
        $eventModel->setModel($event);

        return $eventModel;
    }

    public function getEventUserid(): int
    {
        return $this->model->getEventUserid();
    }

    public function getEventid(): int
    {
        return $this->model->getEventid();
    }

    public function getUser(): IUser
    {
        $user = $this->model->getUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getUserRoles(): int
    {
        return $this->model->getUserRoles();
    }

    public function getUserid(): int
    {
        return $this->model->getUserid();
    }

    public function getIsDeleted() : ?\DateTime
    {
        return $this->model->getIsDeleted();
    }

    public function setBeginMoney($beginMoney): IEventUser
    {
        $this->model->setBeginMoney($beginMoney);
        return $this;
    }

    public function setEventUserid($eventUserid): IEventUser
    {
        $this->model->setEventUserid($eventUserid);
        return $this;
    }

    public function setEvent(IEvent $event = null): IEventUser
    {
        $this->model->setEvent($event);
        return $this;
    }

    public function setEventid($eventid): IEventUser
    {
        $this->model->setEventid($eventid);
        return $this;
    }

    public function setUser($user): IEventUser
    {
        $this->model->setUser($user);
        return $this;
    }

    public function setUserRoles($userRoles): IEventUser
    {
        $this->model->setUserRoles($userRoles);
        return $this;
    }

    public function setUserid($userid): IEventUser
    {
        $this->model->setUserid($userid);
        return $this;
    }

    public function setIsDeleted($isDeleted) : IEventUser
    {
        $this->model->setIsDeleted($isDeleted);
        return $this;
    }
}
