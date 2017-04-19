<?php

namespace API\Models\User\Message;

use API\Lib\Interfaces\Models\User\Message\IUserMessage;
use API\Lib\Interfaces\Models\User\Message\IUserMessageCollection;
use API\Lib\Interfaces\Models\User\Message\IUserMessageQuery;
use API\Models\ORM\User\UserMessageQuery as UserMessageQueryORM;
use API\Models\Query;

class UserMessageQuery extends Query implements IUserMessageQuery
{
    public function find(): IUserMessageCollection
    {
        $userMessages = UserMessageQueryORM::create()->find();

        $userMessageCollection = $this->container->get(IUserMessageCollection::class);
        $userMessageCollection->setCollection($userMessages);

        return $userMessageCollection;
    }

    public function findPk($id): ?IUserMessage
    {
        $userMessage = UserMessageQueryORM::create()->findPk($id);

        if(!$userMessage) {
            return null;
        }

        $userMessageModel = $this->container->get(IUserMessage::class);
        $userMessageModel->setModel($userMessage);

        return $userMessageModel;
    }
}
