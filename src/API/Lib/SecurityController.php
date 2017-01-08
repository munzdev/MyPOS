<?php
namespace API\Lib;

use API\Lib\Exceptions\SecurityException;
use API\Models\Event\Map\EventUserTableMap;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

abstract class SecurityController extends Controller
{
    protected $a_security = array();

    public function __invoke(Request $o_request, Response $o_response, $a_args) : Response {
        if(!Auth::IsLoggedIn())
        {
            throw new SecurityException("Access Denied!");
        }

        $this->CheckAccess($o_request);

        return parent::__invoke($o_request, $o_response, $a_args);
    }

    private function CheckAccess(Request $o_request) {
        if(empty($this->a_security))
            return true;

        $str_method = $o_request->getMethod();

        if(!isset($this->a_security[$str_method]))
            return true;

        $o_user = Auth::GetCurrentUser();
        $i_userRoles = $o_user->getEventUser()->getUserRoles();

        if($i_userRoles & $this->a_security[$str_method])
        {
            return true;
        }

        throw new SecurityException("Access Denied!");
    }
}