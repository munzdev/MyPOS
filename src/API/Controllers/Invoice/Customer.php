<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventContact;
use Slim\App;

class Customer extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    protected function post() : void
    {
        $auth = $this->app->getContainer()->get('Auth');
        $user = $auth->getCurrentUser();

        $eventContact = new EventContact();
        $eventContact->fromArray($this->json);
        $eventContact->setActive(true)
            ->setEventid($user->getEventUser()->getEventid())
            ->save();

        $this->withJson($eventContact->toArray());
    }
}
