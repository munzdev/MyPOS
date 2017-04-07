<?php

namespace API\Models\Event;

use API\Lib\Interfaces\Models\Event\IEvent;
use API\Lib\Interfaces\Models\Event\IEventUser;
use API\Models\Event\Base\EventUser as BaseEventUser;

/**
 * Skeleton subclass for representing a row from the 'event_user' table.
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class EventUser extends BaseEventUser implements IEventUser
{
    public function setEvent(IEvent $v = null) {
        parent::setEvent($v);
    }
}
