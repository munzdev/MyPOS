<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Event\IEventContactQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use Respect\Validation\Validator as v;
use Slim\App;

class CustomerSearch extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    public function any() : void
    {
        $validators = array(
            'name' => v::alnum()->length(1),
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $eventContactQuery = $this->container->get(IEventContactQuery::class);

        $user = $auth->getCurrentUser();

        $eventContacts = $eventContactQuery->findActiveByNameAndEvent($user->getEventUsers()->getFirst()->getEventid(), $this->args['name']);

        $this->withJson($eventContacts->toArray());
    }
}
