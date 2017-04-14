<?php

namespace API\Models\User\Message;

use API\Lib\Container;
use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\Message\IUserMessage;
use API\Models\Model;
use API\Models\ORM\User\Message\UserMessage as UserMessageORM;
use DateTime;

/**
 * Skeleton subclass for representing a row from the 'user_message' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserMessage extends Model implements IUserMessage
{
    private $container;

    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new UserMessageORM());
    }

    public function getFromEventUser(): IUser
    {
        $user = $this->model->getEventUserRelatedByFromEventUserid();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getToEventUser(): IUser
    {
        $user = $this->model->getEventUserRelatedByToEventUserid();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function setFromEventUser($user): IUserMessage
    {
        $this->model->setEventUserRelatedByFromEventUserid($user);
        return $this;
    }

    public function setToEventUser($user): IUserMessage
    {
        $this->model->setEventUserRelatedByToEventUserid($user);
        return $this;
    }

    public function getDate(): DateTime
    {
        return $this->model->getDate();
    }

    public function getFromEventUserid(): int
    {
        return $this->model->getFromEventUserid();
    }

    public function getMessage(): string
    {
        return $this->model->getMessage();
    }

    public function getReaded(): boolean
    {
        return $this->model->getReaded();
    }

    public function getToEventUserid(): int
    {
        return $this->model->getToEventUserid();
    }

    public function getUserMessageid(): int
    {
        return $this->model->getUserMessageid();
    }

    public function setDate($date): IUserMessage
    {
        $this->model->setDate($date);
        return $this;
    }

    public function setFromEventUserid($userid): IUserMessage
    {
        $this->model->setFromEventUserid($userid);
        return $this;
    }

    public function setMessage($message): IUserMessage
    {
        $this->model->setMessage($message);
        return $this;
    }

    public function setReaded($readed): IUserMessage
    {
        $this->model->setReaded($readed);
        return $this;
    }

    public function setToEventUserid($userid): IUserMessage
    {
        $this->model->setToEventUserid($userid);
        return $this;
    }

    public function setUserMessageid($userMessageid): IUserMessage
    {
        $this->model->setUserMessageid($userMessageid);
        return $this;
    }

}
