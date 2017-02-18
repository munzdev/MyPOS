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
    public function __construct(App $app)
    {
        parent::__construct($app);

        $app->getContainer()['db'];
    }

    public function any() : void
    {
        $validators = array(
            'name' => v::alnum()->length(1),
        );

        $this->validate($validators, $this->args);
    }

    protected function get() : void
    {
        $auth = $this->app->getContainer()->get('Auth');
        $user = $auth->getCurrentUser();

        $eventContacts = EventContactQuery::create()
                                            ->filterByEventid($user->getEventUser()->getEventid())
                                            ->filterByActive(true)
                                            ->filterByName('%'.$this->args['name'].'%', Criteria::LIKE)
                                            ->find();

        $this->withJson($eventContacts->toArray());
    }
}
