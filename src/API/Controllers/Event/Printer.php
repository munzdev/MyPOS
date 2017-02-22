<?php

namespace API\Controllers\Event;

use API\Lib\Interfaces\IAuth;
use API\Lib\SecurityController;
use API\Models\Event\EventPrinterQuery;
use Slim\App;

class Printer extends SecurityController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get(IAuth::class);
        $user = $auth->getCurrentUser();

        $printer = EventPrinterQuery::create()
                                        ->findByEventid($user->getEventUser()->getEventid());

        $this->withJson($printer->toArray());
    }
}
