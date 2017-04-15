<?php

namespace API\Lib\Interfaces\Models\Menu;

interface IMenuTypeQuery {
     function getMenuTypesForEventid($eventid) : IMenuTypeCollection;
}