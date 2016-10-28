<?php

namespace API\Models\User;

use API\Models\User\Base\User as BaseUser;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser
{
    use UserTrait;
    
    /**
     * 
     * Only for Auth Lib!
     * 
     * @return int|null
     */
    public function getUserRoles()
    {
        $str_userRoleFieldName = $this->getUserRoleFieldName();
        
        if($this->hasVirtualColumn($str_userRoleFieldName))        
        {
            return $this->getVirtualColumn($str_userRoleFieldName);
        }
        
        return null;
    }
    
    /**
     * 
     * Only for Auth Lib!
     * 
     * @return $this
     */
    public function setUserRoles(int $v)
    {                
        $this->setVirtualColumn($this->getUserRoleFieldName(), $v);

        return $this;
    }    
}
