<?php

namespace API\Controllers\DB\Event;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use Respect\Validation\Validator;
use Slim\App;

class EventPrinterModify extends AdminController
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

    protected function get() : void {
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);
        $eventPrinter = $eventPrinterQuery->findPk($this->args['id']);

        $this->withJson($eventPrinter->toArray());
    }
    
    protected function put() : void {
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);
        $eventPrinter = $eventPrinterQuery->findPk($this->args['id']);
        
        $jsonToModel->convert($this->json, $eventPrinter);
        $eventPrinter->save();
        
        $this->withJson($eventPrinter->toArray());
    }

    protected function delete() : void {
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);
        $eventPrinter = $eventPrinterQuery->findPk($this->args['id']);

        $this->withJson($eventPrinter->delete());
    }
}
