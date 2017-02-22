<?php

namespace API\Models;

use API\Lib\Interfaces\Models\ICollection;
use Propel\Runtime\Collection\Collection as PropelCollection;

class Collection extends PropelCollection implements ICollection {    

    public function toArray() {
        $this->toArray();
    }

}