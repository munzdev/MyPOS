<?php
namespace API\Lib;

use API\Lib\Exceptions\SecurityException;
use API\Lib\Interfaces\IAuth;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class SecurityController extends Controller
{
    protected $security = array();

    public function __invoke(Request $request, Response $response, $args) : Response
    {
        $auth = $this->container->get(IAuth::class);

        if (!$auth->isLoggedIn()) {
            throw new SecurityException("Access Denied!");
        }

        $this->checkAccess($request);

        return parent::__invoke($request, $response, $args);
    }

    private function checkAccess(Request $request)
    {
        if (empty($this->security)) {
            return true;
        }

        $method = $request->getMethod();

        if (!isset($this->security[$method])) {
            return true;
        }

        $auth = $this->container->get(IAuth::class);

        $user = $auth->getCurrentUser();
        $userRoles = $user->getEventUsers()->getFirst()->getUserRoles();

        if ($userRoles & $this->security[$method]) {
            return true;
        }

        throw new SecurityException("Access Denied!");
    }
}
