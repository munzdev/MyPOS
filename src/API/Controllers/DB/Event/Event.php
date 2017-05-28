<?php

namespace API\Controllers\DB\Event;

use API\Lib\AdminController;
use API\Lib\Interfaces\Helpers\IJsonToModel;
use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventQuery;
use API\Lib\Interfaces\Models\IConnectionInterface;
use Slim\App;

class Event extends AdminController
{
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->container->get(IConnectionInterface::class);
    }

    protected function get() : void
    {
        $eventQuery = $this->container->get(IEventQuery::class);
        $events = $eventQuery->find();
        $this->withJson($events->toArray());
    }
    
    protected function post() : void {
        $jsonToModel = $this->container->get(IJsonToModel::class);
        $event = $this->container->get(IEvent::class);
        
        $jsonToModel->convert($this->json, $event);
        $event->save();
        
        $this->withJson($event->toArray());
    }
}
