<?php

namespace API\Lib\Interfaces\Models\User;

use API\Lib\Interfaces\Models\IModel;

interface IUserRole extends IModel {
    /**
     * @return int Description
     */
    function getUserRoleid();

    /**
     * @return string Description
     */
    function getName();

    /**
     *
     * @param int $userRoleid Description
     * @return IUserRole Description
     */
    function setUserRoleid($userRoleid);

    /**
     *
     * @param string $name Description
     * @return IUserRole Description
     */
    function setName($name);
}