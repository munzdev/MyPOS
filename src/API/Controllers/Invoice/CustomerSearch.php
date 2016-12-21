<?php

namespace API\Controllers\Invoice;

use API\Lib\Auth;
use API\Lib\SecurityController;
use API\Models\Event\EventContactQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Respect\Validation\Validator as v;
use Slim\App;

class CustomerSearch extends SecurityController
{
    public function __construct(App $o_app) {
        parent::__construct($o_app);

        $o_app->getContainer()['db'];
    }

    function ANY() : void {
        $a_validators = array(
            'name' => v::alnum()->length(1),
        );

        $this->validate($a_validators, $this->a_args);
    }

    protected function GET() : void  {
        $o_user = Auth::GetCurrentUser();

        $o_event_contacts = EventContactQuery::create()
                                                ->filterByEventid($o_user->getEventUser()->getEventid())
                                                ->filterByActive(true)
                                                ->filterByName('%'.$this->a_args['name'].'%', Criteria::LIKE)
                                                ->find();

        $this->o_response->withJson($o_event_contacts->toArray());
    }

}