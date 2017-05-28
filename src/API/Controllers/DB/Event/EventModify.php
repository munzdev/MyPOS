<?php

namespace API\Controllers\DB\Event;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\Event\IEventQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use Respect\Validation\Validator;
use Slim\App;

class EventModify extends AdminController
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
        $eventQuery = $this->container->get(IEventQuery::class);
        $event = $eventQuery->findPk($this->args['id']);

        $this->withJson($event->toArray());
    }       
    
    protected function put() : void {
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $eventQuery = $this->container->get(IEventQuery::class);
        $event = $eventQuery->findPk($this->args['id']);
        
        $jsonToModel->convert($this->json, $event);
        $event->save();
        
        $this->withJson($event->toArray());
    }    
}
