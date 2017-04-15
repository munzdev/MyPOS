<?php

namespace API\Models\User\Message;

use API\Lib\Container;
use API\Lib\Interfaces\Models\User\Message\IUserMessage;
use API\Lib\Interfaces\Models\User\Message\IUserMessageCollection;
use API\Models\Collection;

class UserMessageCollection extends Collection implements IUserMessageCollection {
    function __construct(Container $container)
    {
        parent::__construct($container);
        $this->setModelServiceName(IUserMessage::class);
    }
}