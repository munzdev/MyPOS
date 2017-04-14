<?php

namespace API\Lib\Interfaces\Models;

interface ICollection extends \ArrayAccess, \IteratorAggregate, \Countable, \Serializable {
    function toArray();
    function isEmpty();
    function getFirst();
    function getLast();
    function get($key);
    function set($key, $value);
    function remove($key);
    function pop();
    function shift();
    function push($value);
    function prepend($value);
    function clear();
    function contains($element);
    function search($element);
    function append($value);
    function exchangeArray($input);
    function setData($data);
    function getData();
    function getArrayCopy();
}