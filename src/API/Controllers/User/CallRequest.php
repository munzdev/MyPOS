<?php

namespace API\Controllers\User;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Lib\SecurityController;
use DateTime;

class CallRequest extends SecurityController
{
    protected function post() : void
    {
        $auth = $this->container->get(IAuth::class);
        $userQuery = $this->container->get(IUserQuery::class);

        $loggedInUser = $auth->getCurrentUser();

        $user = $userQuery->findPk($loggedInUser->getUserid());
        $user->setCallRequest(new DateTime());
        $user->save();

        $this->withJson(true);
    }
}
