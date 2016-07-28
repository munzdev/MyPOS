<?php
namespace Lib;

use Exception;

class SecurityController extends Controller
{
    protected $a_security = array();

    public function __construct()
    {
        parent::__construct();

        if(!Login::IsLoggedIn())
        {
            throw new Exception("Zugriff verweigert!");
        }
    }

    public function CheckAccess($str_action)
    {
        if(empty($this->a_security))
            return true;

        if(!isset($this->a_security[$str_action]))
            return true;

        $a_user = Login::GetCurrentUser();

        if($a_user['user_roles'] & $this->a_security[$str_action])
        {
            return true;
        }

        throw new Exception("Zugriff verweigert!");
    }
}