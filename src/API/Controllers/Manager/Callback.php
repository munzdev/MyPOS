<?php

namespace API\Controllers\Manager;

use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Event\IEventBankinformationQuery;
use API\Lib\Interfaces\Models\Event\IEventContactQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\Invoice\IInvoice;
use API\Lib\Interfaces\Models\Invoice\IInvoiceItem;
use API\Lib\Interfaces\Models\Invoice\IInvoiceQuery;
use API\Lib\Interfaces\Models\User\IUserQuery;
use API\Lib\SecurityController;
use const API\USER_ROLE_MANAGER_CALLBACK;
use DateTime;
use Respect\Validation\Validator as v;
use Slim\App;
use Symfony\Component\Config\Definition\Exception\Exception;
use const API\USER_ROLE_INVOICE_ADD;
use const API\USER_ROLE_INVOICE_OVERVIEW;

class Callback extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->security = ['GET' => USER_ROLE_MANAGER_CALLBACK,
                           'PUT' => USER_ROLE_MANAGER_CALLBACK];

        $this->container->get(IConnectionInterface::class);
    }

    public function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $userQuery = $this->container->get(IUserQuery::class);
        $user = $auth->getCurrentUser();

        $users = $userQuery->getCallbacks($user->getEventUsers()->getFirst()->getEventid());

        $usersArray = $users->toArray();

        foreach ($usersArray as &$user) {
            $callRequest = $user['CallRequest'];
            $user = $this->cleanupUserData($user);
            $user['CallRequest'] = $callRequest;
        }

        $this->withJson($usersArray);
    }

    public function put(): void
    {
        $userQuery = $this->container->get(IUserQuery::class);

        $userid = $this->args['id'];

        $user = $userQuery->findPk($userid);
        $user->setCallRequest(null);
        $user->save();

        $this->withJson($this->cleanupUserData($user->toArray()));
    }

}
