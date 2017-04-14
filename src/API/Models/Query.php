<?php

namespace API\Models;

use API\Lib\Container;
use API\Lib\Interfaces\Models\IModel;

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