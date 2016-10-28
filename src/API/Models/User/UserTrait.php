<?php

namespace API\Models\User;

use API\Models\Event\Map\EventUserTableMap;

trait UserTrait
{   
    public static function getUserRoleFieldName()
    {
        return EventUserTableMap::translateFieldName(EventUserTableMap::COL_USER_ROLES, 
                                                     EventUserTableMap::TYPE_COLNAME, 
                                                     EventUserTableMap::TYPE_PHPNAME);
    }
}
