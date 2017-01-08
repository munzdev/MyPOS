<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventContact;
use Slim\App;

class Customer extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    protected function POST() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_event_contact = new EventContact();
        $o_event_contact->fromArray($this->a_json);
        $o_event_contact->setActive(true)
                        ->setEventid($o_user->getEventUser()->getEventid())
                        ->save();

        $this->withJson($o_event_contact->toArray());
    }

}