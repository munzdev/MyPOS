<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IModel;

interface IMenuPossibleExtra extends IModel {
    /**
     * @return int
     */
    function getMenuPossibleExtraid();

    /**
     * @return int
     */
    function getMenuExtraid();

    /**
     * @return IMenuExtra
     */
    function getMenuExtra();

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
     *
     * @param int $menuPossibleExtraid Description
     * @return IMenuPossibleExtra Description
     */
    function setMenuPossibleExtraid($menuPossibleExtraid);

    /**
     *
     * @param int $menuExtraid Description
     * @return IMenuPossibleExtra Description
     */
    function setMenuExtraid($menuExtraid);

    /**
     *
     * @param IMenuExtra $menuExtra Description
     * @return IMenuPossibleExtra Description
     */
    function setMenuExtra($menuExtra);

    /**
     *
     * @param int $menuid Description
     * @return IMenuPossibleExtra Description
     */
    function setMenuid($menuid);

    /**
     *
     * @param IMenu $menu Description
     * @return IMenuPossibleExtra Description
     */
    function setMenu($menu);

    /**
     *
     * @param float $price Description
     * @return IMenuPossibleExtra Description
     */
    function setPrice($price);
}