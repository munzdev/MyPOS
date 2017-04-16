<?php

namespace API\Lib\Interfaces\Models\Menu;

use API\Lib\Interfaces\Models\IModel;

interface IMenuGroup extends IModel {
    /**
     * @return int
     */
    function getMenuGroupid();

    /**
     * @return int
     */
    function getMenuTypeid();

    /**
     * @return IMenuType
     */
    function getMenuType();

    /**
     * @return string
     */
    function getName();

    /**
     * @return IMenuCollection 
     */
    function getMenus();

    /**
     *
     * @param int $menuGroupid Description
     * @return IMenuGroup Description
     */
    function setMenuGroupid($menuGroupid);

    /**
     *
     * @param int $menuTypeid Description
     * @return IMenuGroup Description
     */
    function setMenuTypeid($menuTypeid);

    /**
     *
     * @param IMenuType $menuType Description
     * @return IMenuGroup Description
     */
    function setMenuType($menuType);

     /**
     *
     * @param string $name Description
     * @return IMenuGroup Description
     */
    function setName($name);
}