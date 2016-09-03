<?php
namespace Controller;

use Lib\AdminController;
use Lib\Database;
use Lib\Request;
use MyPOS;
use Model;

class Admin extends AdminController
{
    public function GetEventsListAction()
    {
        $o_events = new Model\Events(Database::GetConnection());

        $a_events = $o_events->GetEventsList();

        return $a_events;
    }

    public function AddEventAction()
    {
        $a_params = Request::ValidateParams(array('name' => 'string',
                                                  'date' => 'string'));

        $o_events = new Model\Events(Database::GetConnection());

        $d_date = date(MyPOS\DATE_MYSQL_TIMEFORMAT, strtotime($a_params['date']));

        return $o_events->AddEvent($a_params['name'], $d_date, 0);
    }

    public function EventSetActiveAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->SetActive($a_params['eventid']);
    }

    public function EventDeleteAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->Delete($a_params['eventid']);
    }

    public function GetEventAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->GetEvent($a_params['eventid']);
    }

    public function SetEventAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric',
                                                  'name' => 'string',
                                                  'date' => 'string'));

        $o_events = new Model\Events(Database::GetConnection());

        $d_date = date(MyPOS\DATE_MYSQL_TIMEFORMAT, strtotime($a_params['date']));

        return $o_events->SetEvent($a_params['eventid'], $a_params['name'], $d_date);
    }

    public function GetUsersListAction()
    {
        $o_users = new Model\Users(Database::GetConnection());

        return $o_users->GetAllUsers();
    }

    public function AddUserAction()
    {
        $a_params = Request::ValidateParams(array('username' => 'string',
                                                  'password' => 'string',
                                                  'firstname' => 'string',
                                                  'lastname' => 'string',
                                                  'phonenumber' => 'string',
                                                  'isAdmin' => 'bool',
                                                  'active' => 'bool'));

        $o_users = new Model\Users(Database::GetConnection());

        return $o_users->AddUser($a_params['username'],
                                 $a_params['password'],
                                 $a_params['firstname'],
                                 $a_params['lastname'],
                                 $a_params['phonenumber'],
                                 $a_params['isAdmin'] == true ? 1 : 0,
                                 $a_params['active'] == true ? 1 : 0);

    }

    public function GetUserAction()
    {
        $a_params = Request::ValidateParams(array('userid' => 'numeric'));

        $o_users = new Model\Users(Database::GetConnection());

        return $o_users->GetUser($a_params['userid']);
    }

    public function SetUserAction()
    {
        $a_params = Request::ValidateParams(array('userid' => 'numeric',
                                                  'username' => 'string',
                                                  'password' => 'string',
                                                  'firstname' => 'string',
                                                  'lastname' => 'string',
                                                  'phonenumber' => 'string',
                                                  'isAdmin' => 'bool',
                                                  'active' => 'bool'));

        $o_users = new Model\Users(Database::GetConnection());

        return $o_users->SetUser($a_params['userid'],
                                 $a_params['username'],
                                 $a_params['password'],
                                 $a_params['firstname'],
                                 $a_params['lastname'],
                                 $a_params['phonenumber'],
                                 $a_params['isAdmin'] == 'true' ? 1 : 0,
                                 $a_params['active'] == 'true' ? 1 : 0);
    }

    public function UserDeleteAction()
    {
        $a_params = Request::ValidateParams(array('userid' => 'numeric'));

        $o_users = new Model\Users(Database::GetConnection());

        return $o_users->Delete($a_params['userid']);
    }
}