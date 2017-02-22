<?php

namespace API\Lib\Interfaces\Models;

interface ICollection extends \ArrayAccess, \IteratorAggregate, \Countable, \Serializable {
    function toArray();
    function isEmpty();
    function getFirst();
}