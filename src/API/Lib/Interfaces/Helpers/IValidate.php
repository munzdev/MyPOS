<?php

namespace API\Lib\Interfaces\Helpers;

interface IValidate {
    function assert(array $validators, array $data) : void;
}