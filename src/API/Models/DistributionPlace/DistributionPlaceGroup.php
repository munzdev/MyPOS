<?php

namespace API\Models\DistributionPlace;

use API\Lib\Container;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlace;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceGroup;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;
use API\Models\DistributionPlace\Base\DistributionPlaceGroup as DistributionPlaceGroupORM;
use API\Models\Model;

/**
 * Skeleton subclass for representing a row from the 'distribution_place_group' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class DistributionPlaceGroup extends Model implements IDistributionPlaceGroup
{
    private $container;
    
    function __construct(Container $container) {
        $this->container = $container;
        $this->setModel(new DistributionPlaceGroupORM());
    }
    
    public function getDistributionPlace(): IDistributionPlace {
        $distributionPlace = $this->model->getDistributionPlace();
        
        $distributionPlaceModel = $this->container->get(IDistributionPlace::class);
        $distributionPlaceModel->setModel($distributionPlace);
        
        return $distributionPlaceModel;
    }

    public function getDistributionPlaceGroupid(): int {
        return $this->model->getDistributionPlaceGroupid();
    }

    public function getDistributionPlaceid(): int {
        return $this->model->getDistributionPlaceid();
    }

    public function getMenuGroup(): IMenuGroup {
        $distributionPlace = $this->model->getDistributionPlace();
        
        $distributionPlaceModel = $this->container->get(IDistributionPlace::class);
        $distributionPlaceModel->setModel($distributionPlace);
        
        return $distributionPlaceModel;
    }

    public function getMenuGroupid(): int {
        return $this->model->getMenuGroupid();
    }

    public function setDistributionPlace($distributionPlace): IDistributionPlaceGroup {
        $this->model->setDistributionPlace($distributionPlace);
        return $this;
    }

    public function setDistributionPlaceGroupid($distributionPlaceGroupid): IDistributionPlaceGroup {
        $this->model->setDistributionPlaceGroupid($distributionPlaceGroupid);
        return $this;
    }

    public function setDistributionPlaceid($distributionPlaceid): IDistributionPlaceGroup {
        $this->model->setDistributionPlaceid($distributionPlaceid);
        return $this;
    }

    public function setMenuGroup($menuGroup): IDistributionPlaceGroup {
        $this->model->setMenuGroup($menuGroup);
        return $this;
    }

    public function setMenuGroupid($menuGroupid): IDistributionPlaceGroup {
        $this->model->setMenuGroupid($menuGroupid);
        return $this;
    }

}
