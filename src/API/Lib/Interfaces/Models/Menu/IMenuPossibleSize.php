<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IModel;

interface IMenuPossibleSize extends IModel {
    /**
     * @return int
     */
    function getMenuPossibleSizeid();

    /**
     * @return int
     */
    function getMenuSizeid();

    /**
     * @return IMenuSize
     */
    function getMenuSize();

    /**
     * @return int
     */
    function getMenuid();

    /**
     * @return IMenu
     */
    function getMenu();

    /**
     * @return float
     */
    function getPrice();

    /**
     * @return \DateTime
     */
    function getIsDeleted();

    /**
     *
     * @param int $menuPossibleSizeid Description
     * @return IMenuPossibleSize Description
     */
    function setMenuPossibleSizeid($menuPossibleSizeid);

    /**
     *
     * @param int $menuSizeid Description
     * @return IMenuPossibleSize Description
     */
    function setMenuSizeid($menuSizeid);

    /**
     *
     * @param IMenuSize $menuSize Description
     * @return IMenuPossibleSize Description
     */
    function setMenuSize($menuSize);

    /**
     *
     * @param int $menuid Description
     * @return IMenuPossibleSize Description
     */
    function setMenuid($menuid);

    /**
     *
     * @param IMenu $menu Description
     * @return IMenuPossibleSize Description
     */
    function setMenu($menu);

    /**
     *
     * @param float $price Description
     * @return IMenuPossibleSize Description
     */
    function setPrice($price);

    /**
     *
     * @param \DateTime $deleted Description
     * @return IMenuPossibleSize Description
     */
    function setIsDeleted($isDeleted);
}