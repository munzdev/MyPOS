<?php

namespace API\Lib;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class Controller
{
    protected $o_app;
    protected $o_logger;
    
    public function __construct(\Slim\App $o_app, LoggerInterface $o_logger)
    {
        $this->o_app = $o_app;        
        $this->o_logger = $o_logger;
    }
    
    public abstract function __invoke(Request $request, Response $response, $args);
}