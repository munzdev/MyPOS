<?php

namespace API\Models\User\Message;

use API\Lib\Interfaces\Models\User\IUser;
use API\Lib\Interfaces\Models\User\Message\IUserMessage;
use API\Models\User\Message\Base\UserMessage as BaseUserMessage;

/**
 * Skeleton subclass for representing a row from the 'user_message' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class UserMessage extends BaseUserMessage implements IUserMessage
{
    public function getFromEventUser(): IUser
    {
        return $this->getEventUserRelatedByFromEventUserid();
    }

    public function getToEventUser(): IUser
    {
        return $this->getEventUserRelatedByToEventUserid();
    }

    public function setFromEventUser($user): IUserMessage
    {
        $this->setEventUserRelatedByFromEventUserid($user);
        return $this;
    }

    public function setToEventUser($user): IUserMessage
    {
        $this->setEventUserRelatedByToEventUserid($user);
        return $this;
    }

}
