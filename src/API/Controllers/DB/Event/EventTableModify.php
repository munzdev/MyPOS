<?php

namespace API\Controllers\DB\Event;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\Event\IEventTableQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use Respect\Validation\Validator;
use Slim\App;

class EventTableModify extends AdminController
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
        $eventTableQuery = $this->container->get(IEventTableQuery::class);
        $eventTable = $eventTableQuery->findPk($this->args['id']);

        $this->withJson($eventTable->toArray());
    }       
    
    protected function put() : void {
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $eventTableQuery = $this->container->get(IEventTableQuery::class);
        $eventTable = $eventTableQuery->findPk($this->args['id']);
        
        $jsonToModel->convert($this->json, $eventTable);
        $eventTable->save();
        
        $this->withJson($eventTable->toArray());
    }    
}
