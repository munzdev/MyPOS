<?php

namespace API\Lib\Interfaces\Models\DistributionPlace;

use API\Lib\Interfaces\Models\IModel;
use API\Lib\Interfaces\Models\Menu\IMenuGroup;

interface IDistributionPlaceGroup extends IModel {
    /**
     * @return int
     */
    function getDistributionPlaceGroupid();

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
    function getMenuGroupid();

    /**
     * @return IMenuGroup
     */
    function getMenuGroup();

    /**
     * @param int $distributionPlaceGroupid Description
     * @return IDistributionPlaceGroup Description
     */
    function setDistributionPlaceGroupid($distributionPlaceGroupid);

    /**
     * @param int $distributionPlaceid Description
     * @return IDistributionPlaceGroup Description
     */
    function setDistributionPlaceid($distributionPlaceid);

    /**
     * @param IDistributionPlace $distributionPlace Description
     * @return IDistributionPlaceGroup Description
     */
    function setDistributionPlace($distributionPlace);

    /**
     * @param IMenuGroup $menuGroupid Description
     * @return IDistributionPlaceGroup Description
     */
    function setMenuGroupid($menuGroupid);

    /**
     * @param int $menuGroup Description
     * @return IDistributionPlaceGroup Description
     */
    function setMenuGroup($menuGroup);
}