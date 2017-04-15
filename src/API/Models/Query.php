<?php

namespace API\Models;

use API\Lib\Container;

abstract class Query {

    /**
     *
     * @var Container
     */
    protected $container;
    
    function __construct(Container $container) {
        $this->container = $container;
    }
}