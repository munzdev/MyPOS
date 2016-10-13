<?php
namespace Controller;

use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Model;

class Events extends SecurityController
{
    public function GetPrintersAction()
    {
        $o_events = new Model\Events(Database::GetConnection());

        $a_printers = $o_events->GetPrinters(Login::GetCurrentUser()['eventid']);

        return $a_printers;
    }


    public function GetRolesAction()
    {
        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->GetRoles();
    }
}