<?php
namespace API\Lib;

use API\Lib\Exceptions\SecurityException;
use API\Lib\Interfaces\IAuth;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class AdminController extends SecurityController
{
    public function __invoke(Request $request, Response $response, $args) : Response
    {        
        $result = parent::__invoke($request, $response, $args);

        $auth = $this->container->get(IAuth::class);
        $user = $auth->getCurrentUser();

        if (!$user->getIsAdmin()) {
            throw new SecurityException("Access Denied!");
        }
        
        return $result;
    }
}
