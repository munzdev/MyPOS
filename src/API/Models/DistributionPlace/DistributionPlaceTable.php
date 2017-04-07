<?php

namespace API\Models\DistributionPlace;

use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroup;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceTable;
use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Models\Model;
use API\Models\ORM\DistributionPlace\DistributionPlaceTable as DistributionPlaceTableORM;

/**
 * Skeleton subclass for representing a row from the 'distribution_place_table' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class DistributionPlaceTable extends Model implements IDistributionPlaceTable
{
    private $container;
    
    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new DistributionPlaceTableORM());
    }
    
    public function getDistributionPlaceGroup(): IDistributionPlaceGroup {
        $distributionPlaceGroup = $this->model->getDistributionPlaceGroup();
        
        $distributionPlaceGroupModel = $this->container->get(IDistributionPlaceGroup::class);
        $distributionPlaceGroupModel->setModel($distributionPlaceGroup);
        
        return $distributionPlaceGroupModel;
    }

    public function getDistributionPlaceGroupid(): int {
        return $this->model->getDistributionPlaceGroupid();
    }

    public function getEventTable(): IEventTable {
        $eventTable = $this->model->getEventTable();
        
        $eventTableModel = $this->container->get(IEventTable::class);
        $eventTableModel->setModel($eventTable);
        
        return $eventTableModel;
    }

    public function getEventTableid(): int {
        return $this->model->getEventTableid();
    }

    public function setDistributionPlaceGroup($distributionPlaceGroup): IDistributionPlaceTable {
        $this->model->setDistributionPlaceGroup($distributionPlaceGroup);
        return $this;
    }

    public function setDistributionPlaceGroupid($distributionPlaceGroupid): IDistributionPlaceTable {
        $this->model->setDistributionPlaceGroupid($distributionPlaceGroupid);
        return $this;
    }

    public function setEventTable($eventTable): IDistributionPlaceTable {
        $this->model->setEventTable($eventTable);
        return $this;
    }

    public function setEventTableid($eventTableid): IDistributionPlaceTable {
        $this->model->setEventTableid($eventTableid);
        return $this;
    }

}
