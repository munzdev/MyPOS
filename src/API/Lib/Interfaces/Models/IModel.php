<?php

namespace API\Lib\Interfaces\Models;

interface IModel {
    function save();
    function isNew();
    function delete();
    function clear();
    function toArray();
}