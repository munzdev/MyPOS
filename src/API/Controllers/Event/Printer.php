<?php

namespace API\Controllers\Event;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventPrinterQuery;
use Slim\App;

class Printer extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    protected function GET() : void
    {
        $o_user = Auth::GetCurrentUser();

        $o_printer = EventPrinterQuery::create()
                                        ->findByEventid($o_user->getEventUser()->getEventid());

        $this->withJson($o_printer->toArray());
    }
}