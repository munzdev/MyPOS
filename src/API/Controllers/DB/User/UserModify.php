<?php

namespace API\Controllers\DB\User;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\IConnectionInterface;
use API\Lib\Interfaces\Models\User\IUserQuery;
use Respect\Validation\Validator;
use Slim\App;

class UserModify extends AdminController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $validators = array(
            'id' => Validator::alnum()->noWhitespace()->length(1)
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);

        $userQuery = $this->container->get(IUserQuery::class);
        $user = $userQuery->findPk($this->args['id']);

        $this->withJson($user->toArray());
    }
}
