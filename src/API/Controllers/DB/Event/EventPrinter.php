<?php

namespace API\Controllers\DB\Event;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Helpers\IValidate;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Lib\Interfaces\Models\Event\IEventPrinterQuery;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\Event\IEventTableQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use Respect\Validation\Validator;
use Slim\App;

class EventPrinter extends AdminController
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
        $eventPrinterQuery = $this->container->get(IEventPrinterQuery::class);
        $eventPrinters = $eventPrinterQuery->findByEventid($this->args['id']);
        $this->withJson($eventPrinters->toArray());
    }
    
    protected function post() : void {
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $eventPrinter = $this->container->get(IEventPrinter::class);
        
        $jsonToModel->convert($this->json, $eventPrinter);
        $eventPrinter->setEventid($this->args['id']);
        $eventPrinter->save();
        
        $this->withJson($eventPrinter->toArray());
    }
}
