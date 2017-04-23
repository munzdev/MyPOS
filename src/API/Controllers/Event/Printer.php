<?php

namespace API\Controllers\Event;

use API\Lib\Interfaces\IAuth;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\SecurityController;
use API\Models\ORM\Event\EventPrinterQuery;
use Slim\App;

class Printer extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $auth = $this->container->get(IAuth::class);
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);

        $user = $auth->getCurrentUser();

        $printers = $eventPrinterQuery->findByEventid($user->getEventUsers()->getFirst()->getEventid());

        $this->withJson($printers->toArray());
    }
}
