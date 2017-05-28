<?php

namespace API\Controllers\DB\User;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IJsonToModel;
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
    
    protected function any(): void {
        $validators = array(
            'id' => Validator::alnum()->noWhitespace()->length(1)
        );

        $validate = $this->container->get(IValidate::class);
        $validate->assert($validators, $this->args);
    }

    protected function get() : void
    {        
        $userQuery = $this->container->get(IUserQuery::class);
        $user = $userQuery->findPk($this->args['id']);
        $userArray = $user->toArray();
        
        $isAdmin = $userArray['IsAdmin'];
        $userArray = $this->cleanupUserData($userArray);
        $userArray['IsAdmin'] = $isAdmin;

        $this->withJson($userArray);
    }       
    
    protected function put() : void {
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $userQuery = $this->container->get(IUserQuery::class);
        $user = $userQuery->findPk($this->args['id']);
        
        $jsonToModel->convert($this->json, $user);
        $user->save();
        
        $this->withJson($user->toArray());
    }    
}
