<?php

namespace API\Lib\Interfaces\Models\Event;

use API\Lib\Interfaces\Models\IModel;

interface IEventUser extends IModel {    
    /**
     * @return float Description
     */
    function getBeginMoney();
    
    /**
     * @return int Description
     */
    function getEventUserid();
    
    /**
     * @return int
     */
    function getEventid();
    
    /**
     * @return int
     */
    function getUserid();
    
    /**
     * @return IUserRoleCollection
     */
    function getUserRoles();
    
    /**
     *
     * @param float $beginMoney Description
     * @return IEventUser Description
     */
    function setBeginMoney($beginMoney);
    
    /**
     * @param int $eventUserid Description
     * @return IEventUser Description
     */
    function setEventUserid($eventUserid);
    
    /**
     * @param int getEventid Description
     * @return IEventUser Description
     */
    function setEventid($eventid);
    
    /**
     * @param int $userid Description
     * @return IEventUser Description
     */
    function setUserid($userid);
    
    /**
     * @param int $userRoles Description
     * @return IEventUser Description
     */
    function setUserRoles($userRoles);              
}