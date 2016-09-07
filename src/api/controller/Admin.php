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

    public function GetMenuListAction()
    {
        $o_products = new Model\Products(Database::GetConnection());

        $a_types = $o_products->GetTypesList();
        $a_groupes = $o_products->GetGroupesList();


        $a_return = array();

        foreach($a_types as $a_type)
        {
            $a_return[$a_type['menu_typeid']] = $a_type;
            $a_return[$a_type['menu_typeid']]['groupes'] = array();

            foreach($a_groupes as $a_group)
            {
                if($a_type['menu_typeid'] != $a_group['menu_typeid'])
                    continue;

                $a_return[$a_type['menu_typeid']]['groupes'][] = $a_group;
            }
        }

        return array_values($a_return);
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
                                 $a_params['isAdmin'] == 'true' ? 1 : 0,
                                 $a_params['active'] == 'true' ? 1 : 0);

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

    public function GetMenuTypeAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->GetType($a_params['id']);
    }

    public function GetMenuGroupAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->GetGroup($a_params['id']);
    }

    public function AddMenuTypeAction()
    {
        $a_params = Request::ValidateParams(array('name' => 'string',
                                                  'tax' => 'numeric',
                                                  'allowMixing' => 'bool'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->AddType($a_params['name'],
                                    $a_params['tax'],
                                    $a_params['allowMixing'] == 'true' ? 1 : 0);
    }

    public function AddMenuGroupAction()
    {
        $a_params = Request::ValidateParams(array('name' => 'string',
                                                  'menu_typeid' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->AddGroup($a_params['menu_typeid'], $a_params['name']);
    }

    public function SetMenuTypeAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric',
                                                  'name' => 'string',
                                                  'tax' => 'numeric',
                                                  'allowMixing' => 'bool'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->SetType($a_params['id'],
                                    $a_params['name'],
                                    $a_params['tax'],
                                    $a_params['allowMixing'] == 'true' ? 1 : 0);
    }

    public function SetMenuGroupAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric',
                                                  'name' => 'string'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->SetGroup($a_params['id'], $a_params['name']);
    }

    public function DeleteMenuGroupAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->DeleteGroup($a_params['id']);
    }

    public function DeleteMenuTypeAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->DeleteType($a_params['id']);
    }
}
