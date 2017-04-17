<?php

namespace API\Controllers\Invoice;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Models\ORM\Event\EventContact;
use Slim\App;

class Customer extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function post() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $eventContact = new EventContact();
        $eventContact->fromArray($this->json);
        $eventContact->setActive(true)
            ->setEventid($user->getEventUsers()->getFirst()->getEventid())
            ->save();

        $this->withJson($eventContact->toArray());
    }
}
