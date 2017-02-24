<?php

namespace API\Lib\Interfaces\Helpers;

interface IJsonToModel {
    function convert(array $json, $model);
}