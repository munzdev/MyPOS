<?php

namespace API\Lib\Interfaces\Models\DistributionPlace;

use API\Lib\Interfaces\Models\Event\IEventPrinter;
use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\User\IUser;

interface IDistributionPlaceUser extends IModel {
    /**
     * @return int
     */
    function getDistributionPlaceid();

    /**
     * @return IDistributionPlace
     */
    function getDistributionPlace();

    /**
     * @return int
     */
    function getUserid();

    /**
     * @return IUser
     */
    function getUser();

    /**
     * @return int
     */
    function getEventPrinterid();

    /**
     * @return IEventPrinter
     */
    function getEventPrinter();


    /**
     * @param int $distributionPlaceid Description
     * @return IDistributionPlaceUser Description
     */
    function setDistributionPlaceid($distributionPlaceid);

    /**
     * @param IDistributionPlace $distributionPlace Description
     * @return IDistributionPlaceUser Description
     */
    function setDistributionPlace($distributionPlace);

    /**
     * @param int $userid Description
     * @return IDistributionPlaceUser Description
     */
    function setUserid($userid);

    /**
     * @param IUser $user Description
     * @return IDistributionPlaceUser Description
     */
    function setUser($user);

    /**
     * @param int $eventPrinterid Description
     * @return IDistributionPlaceUser Description
     */
    function setEventPrinterid($eventPrinterid);

    /**
     * @param IEventPrinter $eventPrinter Description
     * @return IDistributionPlaceUser Description
     */
    function setEventPrinter($eventPrinter);
}