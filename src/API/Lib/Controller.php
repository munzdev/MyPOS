<?php

namespace API\Lib;

use Slim\App;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use API\Lib\Exceptions\InvalidRequestException;

abstract class Controller
{
    protected $o_app;
    protected $o_logger;
    
    public function __construct(App $o_app)
    {
        $this->o_app = $o_app;        
        $this->o_logger = $o_app->getContainer()->get('logger');
    }
    
    public function __invoke(Request $o_request, Response $o_response, $a_args)
    {
        try
        {                    
            $this->ANY($o_request, $o_response, $a_args);

            if($o_request->isGet())
                $this->GET($o_request, $o_response, $a_args);
            elseif($o_request->isPost())
                $this->POST($o_request, $o_response, $a_args);
            elseif($o_request->isPut())
                $this->PUT($o_request, $o_response, $a_args);
            elseif($o_request->isDelete())
                $this->DELETE($o_request, $o_response, $a_args);
            elseif($o_request->isHead())
                $this->HEAD($o_request, $o_response, $a_args);
            elseif($o_request->isPatch())
                $this->PATCH($o_request, $o_response, $a_args);
            elseif($o_request->isOptions())
                $this->OPTIONS($o_request, $o_response, $a_args);        
        } catch (InvalidRequestException $o_exception) {                  
            $this->GenerateJSONErrorFromException($o_response, $o_exception, 400);
        } catch (\Exception $o_exception) {
            $this->GenerateJSONErrorFromException($o_response, $o_exception, 500);
        }
    }
    
    private function GenerateJSONErrorFromException(Response $o_response, \Exception $o_exception, int $i_statusCode)
    {
        $o_response->withStatus($i_statusCode);
        $o_response->withJson(array(
            'status' => $i_statusCode,
            'code' => $o_exception->getCode(),
            'title' => $o_exception->getMessage(),
            'detail' => $o_exception->__toString()          
        ));
    }
    
    protected function ANY(Request $o_request, Response $o_response, $a_args) {}    
    protected function POST(Request $o_request, Response $o_response, $a_args) {}
    protected function GET(Request $o_request, Response $o_response, $a_args) {}
    protected function PUT(Request $o_request, Response $o_response, $a_args) {}
    protected function DELETE(Request $o_request, Response $o_response, $a_args) {}
    protected function HEAD(Request $o_request, Response $o_response, $a_args) {}
    protected function PATCH(Request $o_request, Response $o_response, $a_args) {}
    protected function OPTIONS(Request $o_request, Response $o_response, $a_args) {}
}