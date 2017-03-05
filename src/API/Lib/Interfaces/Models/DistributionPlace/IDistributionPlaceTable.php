<?php

namespace API\Lib\Interfaces\Models\DistributionPlace;

use API\Lib\Interfaces\Models\Event\IEventTable;
use API\Lib\Interfaces\Models\IModel;

interface IDistributionPlaceTable extends IModel {
    /**
     * @return int
     */
    function getEventTableid();

    /**
     * @return IEventTable
     */
    function getEventTable();

    /**
     * @return int
     */
    function getDistributionPlaceGroupid();

    /**
     * @return IDistributionPlaceGroup
     */
    function getDistributionPlaceGroup();

    /**
     * @param int $eventTableid Description
     * @return IDistributionPlaceTable Description
     */
    function setEventTableid($eventTableid);

    /**
     * @param IEventTable $eventTable Description
     * @return IDistributionPlaceTable Description
     */
    function setEventTable($eventTable);

    /**
     * @param int $distributionPlaceGroupid Description
     * @return IDistributionPlaceTable Description
     */
    function setDistributionPlaceGroupid($distributionPlaceGroupid);

    /**
     * @param IDistributionPlaceGroup $distributionPlaceGroup Description
     * @return IDistributionPlaceTable Description
     */
    function setDistributionPlaceGroup($distributionPlaceGroup);
}