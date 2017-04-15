<?php

namespace API\Models\DistributionPlace;

use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlace;
use API\Lib\Interfaces\Models\DistributionPlace\IDistributionPlaceUser;
use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Lib\Interfaces\Models\User\IUser;
use API\Models\Model;
use \API\Models\ORM\DistributionPlace\DistributionPlaceUser as DistributionPlaceUserORM;

/**
 * Skeleton subclass for representing a row from the 'distribution_place_user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class DistributionPlaceUser extends Model implements IDistributionPlaceUser
{
    function __construct(Container $container) {
        parent::__construct($container);
        $this->setModel(new DistributionPlaceUserORM());
    }

    public function getDistributionPlace(): IDistributionPlace {
        $distribtionPlace = $this->model->getDistributionPlace();

        $distribtionPlaceModel = $this->container->get(IDistributionPlace::class);
        $distribtionPlaceModel->setModel($distribtionPlace);

        return $distribtionPlaceModel;
    }

    public function getDistributionPlaceid(): int {
        return $this->model->getDistributionPlaceid();
    }

    public function getEventPrinter(): IEventPrinter {
        $eventPrinter = $this->model->getEventPrinter();

        $eventPrinterModel = $this->container->get(IEventPrinter::class);
        $eventPrinterModel->setModel($eventPrinter);

        return $eventPrinterModel;
    }

    public function getEventPrinterid(): int {
        return $this->model->getEventPrinterid();
    }

    public function getUser(): IUser {
        $user = $this->model->getUser();

        $userModel = $this->container->get(IUser::class);
        $userModel->setModel($user);

        return $userModel;
    }

    public function getUserid(): int {
        return $this->model->getUserid();
    }

    public function setDistributionPlace($distributionPlace): IDistributionPlaceUser {
        $this->model->setDistributionPlace($distributionPlace);
        return $this;
    }

    public function setDistributionPlaceid($distributionPlaceid): IDistributionPlaceUser {
        $this->model->setDistributionPlaceid($distributionPlaceid);
        return $this;
    }

    public function setEventPrinter($eventPrinter): IDistributionPlaceUser {
        $this->model->setEventPrinter($eventPrinter);
        return $this;
    }

    public function setEventPrinterid($eventPrinterid): IDistributionPlaceUser {
        $this->model->setEventPrinterid($eventPrinterid);
        return $this;
    }

    public function setUser($user): IDistributionPlaceUser {
        $this->model->setUser($user);
        return $this;
    }

    public function setUserid($userid): IDistributionPlaceUser {
        $this->model->setUserid($userid);
        return $this;
    }

}
