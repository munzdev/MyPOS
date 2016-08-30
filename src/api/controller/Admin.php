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
}