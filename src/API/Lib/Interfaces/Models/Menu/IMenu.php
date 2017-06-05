<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IModel;

interface IMenu extends IModel {
    /**
     * @return int
     */
    function getMenuid();

    /**
     * @return int
     */
    function getMenuGroupid();

    /**
     * @return IMenuGroup
     */
    function getMenuGroup();

    /**
     * @return string
     */
    function getName();

    /**
     * @return float
     */
    function getPrice();

    /**
     * @return int
     */
    function getAvailabilityid();

    /**
     * @return IAvailability
     */
    function getAvailability();

    /**
     * @return int
     */
    function getAvailabilityAmount();

    /**
     * @return IMenuPossibleExtraCollection
     */
    function getMenuPossibleExtras();

    /**
     * @return IMenuPossibleSizeCollection
     */
    function getMenuPossibleSizes();

    /**
     * @return \DateTime
     */
    function getIsDeleted();

    /**
     *
     * @param int $menuid Description
     * @return IMenu Description
     */
    function setMenuid($menuid);

    /**
     *
     * @param int $menuGroupid Description
     * @return IMenu Description
     */
    function setMenuGroupid($menuGroupid);

    /**
     *
     * @param IMenuGroup $menuGroup Description
     * @return IMenu Description
     */
    function setMenuGroup($menuGroup);

    /**
     *
     * @param string $name Description
     * @return IMenu Description
     */
    function setName($name);

    /**
     *
     * @param float $price Description
     * @return IMenu Description
     */
    function setPrice($price);

    /**
     *
     * @param int $availabilityid Description
     * @return IMenu Description
     */
    function setAvailabilityid($availabilityid);

    /**
     *
     * @param IAvailability $availability Description
     * @return IMenu Description
     */
    function setAvailability($availability);

    /**
     *
     * @param int $availabilityAmount Description
     * @return IMenu Description
     */
    function setAvailabilityAmount($availabilityAmount);

    /**
     *
     * @param \DateTime $deleted Description
     * @return IMenu Description
     */
    function setIsDeleted($isDeleted);
}