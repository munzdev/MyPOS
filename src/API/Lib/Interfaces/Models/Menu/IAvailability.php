<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IModel;

interface IAvailability extends IModel {
    /**
     * @return int
     */
    function getAvailabilityid();

    /**
     * @return string
     */
    function getName();

    /**
     *
     * @param int $availabilityid Description
     * @return IAvailability Description
     */
    function setAvailabilityid($availabilityid);

     /**
     *
     * @param string $name Description
     * @return IAvailability Description
     */
    function setName($name);
}