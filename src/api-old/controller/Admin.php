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

    public function GetTableListAction()
    {
        $o_tables = new Model\Tables(Database::GetConnection());

        return $o_tables->GetAll();
    }

    public function AddTableAction()
    {
        $a_params = Request::ValidateParams(array('name' => 'string',
                                                  'data' => 'string'));

        $o_tables = new Model\Tables(Database::GetConnection());

        return $o_tables->AddTable($a_params['name'], $a_params['data']);

    }

    public function GetTableAction()
    {
        $a_params = Request::ValidateParams(array('tableid' => 'numeric'));

        $o_tables = new Model\Tables(Database::GetConnection());

        return $o_tables->GetTable($a_params['tableid']);
    }

    public function SetTableAction()
    {
        $a_params = Request::ValidateParams(array('tableid' => 'numeric',
                                                  'name' => 'string',
                                                  'data' => 'string'));

        $o_tables = new Model\Tables(Database::GetConnection());

        return $o_tables->SetTable($a_params['tableid'],
                                   $a_params['name'],
                                   $a_params['data']);
    }

    public function TableDeleteAction()
    {
        $a_params = Request::ValidateParams(array('tableid' => 'numeric'));

        $o_tables = new Model\Tables(Database::GetConnection());

        return $o_tables->Delete($a_params['tableid']);
    }

    public function GetEventMenuListAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->GetList($a_params['eventid']);
    }

    public function GetEventExtrasAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->GetExtras($a_params['eventid']);
    }

    public function GetMenuSizesAction()
    {
        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->GetSizes();
    }

    public function AddMenuAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric',
                                                  'groupid' => 'numeric',
                                                  'name' => 'string',
                                                  'price' => 'numeric',
                                                  'availability' => 'string',
                                                  'availabilityAmount' => 'numeric',
                                                  'extras' => 'array',
                                                  'sizes' => 'array'));

        $o_db = Database::GetConnection();

        $o_products = new Model\Products($o_db);

        try
        {
            $o_db->beginTransaction();

            $i_menuid = $o_products->AddMenu($a_params['eventid'],
                                             $a_params['groupid'],
                                             $a_params['name'],
                                             $a_params['price'],
                                             $a_params['availability'],
                                             $a_params['availabilityAmount']);

            foreach($a_params['extras'] as $i_extraid => $i_price)
            {
                $o_products->AddMenuExtra($i_menuid, $i_extraid, $i_price);
            }

            foreach($a_params['sizes'] as $i_sizeid => $i_price)
            {
                $o_products->AddMenuSize($i_menuid, $i_sizeid, $i_price);
            }

            $o_db->commit();

            return $i_menuid;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function GetMenuAction()
    {
        $a_params = Request::ValidateParams(array('menuid' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        $a_menu = $o_products->GetMenu($a_params['menuid']);
        $a_menu['sizes'] = array();
        $a_menu['extras'] = array();

        $a_sizes = $o_products->GetMenuSizes($a_params['menuid']);
        $a_extras = $o_products->GetMenuExtras($a_params['menuid']);

        foreach ($a_sizes as $a_sizes)
        {
            $a_menu['sizes'][$a_sizes['menu_sizeid']] = $a_sizes['price'];
        }

        foreach ($a_extras as $a_extra)
        {
            $a_menu['extras'][$a_extra['menu_extraid']] = $a_extra['price'];
        }

        return $a_menu;
    }

    public function SetMenuAction()
    {
        $a_params = Request::ValidateParams(array('menuid' => 'numeric',
                                                  'name' => 'string',
                                                  'price' => 'numeric',
                                                  'availability' => 'string',
                                                  'availabilityAmount' => 'numeric',
                                                  'extras' => 'array',
                                                  'sizes' => 'array'));

        $o_db = Database::GetConnection();

        $o_products = new Model\Products($o_db);

        try
        {
            $o_db->beginTransaction();

            $o_products->SetMenu($a_params['menuid'],
                                 $a_params['name'],
                                 $a_params['price'],
                                 $a_params['availability'],
                                 $a_params['availabilityAmount']);

            foreach($a_params['extras'] as $i_extraid => $i_price)
            {
                $a_size = $o_products->GetMenuExtra($a_params['menuid'], $i_extraid);

                if(empty($a_size))
                {
                    $o_products->AddMenuExtra($a_params['menuid'], $i_extraid, $i_price);
                }
                else
                {
                    $o_products->SetMenuExtraPrice($a_size['menues_possible_extraid'], $i_price);
                }
            }

            $o_products->DeleteMenuExtrasWhereExtraNotIn($a_params['menuid'], array_keys($a_params['extras']));

            foreach($a_params['sizes'] as $i_sizeid => $i_price)
            {
                $a_size = $o_products->GetMenuSize($a_params['menuid'], $i_sizeid);

                if(empty($a_size))
                {
                    $o_products->AddMenuSize($a_params['menuid'], $i_sizeid, $i_price);
                }
                else
                {
                    $o_products->SetMenuSizePrice($a_size['menues_possible_sizeid'], $i_price);
                }
            }

            $o_products->DeleteMenuSizesWhereSizeNotIn($a_params['menuid'], array_keys($a_params['sizes']));

            $o_db->commit();
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function DeleteMenuAction()
    {
        $a_params = Request::ValidateParams(array('id' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->DeleteMenu($a_params['id']);
    }

    public function GetEventUserListAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->GetUserList($a_params['eventid']);
    }

    public function GetEventUserAction()
    {
        $a_params = Request::ValidateParams(array('events_userid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->GetUser($a_params['events_userid']);
    }

    public function SetEventUserAction()
    {
        $a_params = Request::ValidateParams(array('events_userid' => 'numeric',
                                                  'user_roles' => 'numeric',
                                                  'begin_money' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->SetUser($a_params['events_userid'], $a_params['user_roles'], $a_params['begin_money']);
    }

    public function AddEventUserAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric',
                                                  'userid' => 'numeric',
                                                  'user_roles' => 'numeric',
                                                  'begin_money' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->AddUser($a_params['eventid'], $a_params['userid'], $a_params['user_roles'], $a_params['begin_money']);
    }

    public function DeleteEventUserAction()
    {
        $a_params = Request::ValidateParams(array('events_userid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->DeleteUser($a_params['events_userid']);
    }

    public function GetEventPrinterListAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->GetPrinters($a_params['eventid']);
    }

    public function GetEventPrinterAction()
    {
        $a_params = Request::ValidateParams(array('events_printerid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->GetPrinter($a_params['events_printerid']);
    }

    public function SetEventPrinterAction()
    {
        $a_params = Request::ValidateParams(array('events_printerid' => 'numeric',
                                                  'name' => 'string',
                                                  'ip' => 'String',
                                                  'port' => 'numeric',
                                                  'characters_per_row' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->SetPrinter($a_params['events_printerid'], $a_params['name'], $a_params['ip'], $a_params['port'], $a_params['characters_per_row']);
    }

    public function SetEventPrinterDefaultAction()
    {
        $a_params = Request::ValidateParams(array('events_printerid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->SetPrinterDefault($a_params['events_printerid']);
    }

    public function AddEventPrinterAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric',
                                                  'name' => 'string',
                                                  'ip' => 'String',
                                                  'port' => 'numeric',
                                                  'characters_per_row' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->AddPrinter($a_params['eventid'], $a_params['name'], $a_params['ip'], $a_params['port'], $a_params['characters_per_row']);
    }

    public function DeleteEventPrinterAction()
    {
        $a_params = Request::ValidateParams(array('events_printerid' => 'numeric'));

        $o_events = new Model\Events(Database::GetConnection());

        return $o_events->DeletePrinter($a_params['events_printerid']);
    }

    public function GetEventDistributionListAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric'));

        $o_distribution = new Model\Distribution(Database::GetConnection());

        return $o_distribution->GetDistributions($a_params['eventid']);
    }

    public function GetEventDistributionAction()
    {
        $a_params = Request::ValidateParams(array('distributions_placeid' => 'numeric'));

        $o_distribution = new Model\Distribution(Database::GetConnection());

        $str_name = $o_distribution->GetDistribution($a_params['distributions_placeid']);
        $a_menues = $o_distribution->GetDistributionMenuGroupes($a_params['distributions_placeid']);
        $a_users = $o_distribution->GetDistributionUsers($a_params['distributions_placeid']);
        $a_tables = $o_distribution->GetDistributionTables($a_params['distributions_placeid']);
        
        return array('name' => $str_name,
                     'menues' => $a_menues,
                     'users' => $a_users,
                     'tables' => $a_tables);
    }

    public function SetEventDistributionAction()
    {
        $a_params = Request::ValidateParams(array('distributions_placeid' => 'numeric',
                                                  'name' => 'string',
                                                  'menues' => 'array',
                                                  'users' => 'array',
                                                  'tablesList' => 'array'));
        
        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);

        try
        {
            $o_db->beginTransaction();

            $o_distribution->SetDistribution($a_params['distributions_placeid'], 
                                             $a_params['name']);

            $o_distribution->SetDistributionMenuGroupes($a_params['distributions_placeid'], $a_params['menues']);
            $o_distribution->SetDistributionUsers($a_params['distributions_placeid'], $a_params['users']);

            foreach($a_params['tablesList'] as $i_menu_groupid => $a_tables)
            {
                if(!empty($a_tables))
                    $o_distribution->SetDistributionTables($a_params['distributions_placeid'], $i_menu_groupid, $a_tables);
            }            
            
            $o_db->commit();
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function AddEventDistributionAction()
    {
        $a_params = Request::ValidateParams(array('eventid' => 'numeric',
                                                  'name' => 'string',
                                                  'menues' => 'array',
                                                  'users' => 'array',
                                                  'tablesList' => 'array'));
        
        $o_db = Database::GetConnection();

        $o_distribution = new Model\Distribution($o_db);

        try
        {
            $o_db->beginTransaction();

            $i_distributionid = $o_distribution->AddDistribution($a_params['eventid'], 
                                                                 $a_params['name']);

            $o_distribution->SetDistributionMenuGroupes($i_distributionid, $a_params['menues']);
            $o_distribution->SetDistributionUsers($i_distributionid, $a_params['users']);

            foreach($a_params['tablesList'] as $i_menu_groupid => $a_tables)
            {
                $o_distribution->SetDistributionTables($i_distributionid, $i_menu_groupid, $a_tables);
            }            
            
            $o_db->commit();
            
            return $i_distributionid;
        }
        catch (Exception $o_exception)
        {
            $o_db->rollBack();
            throw $o_exception;
        }
    }

    public function DeleteEventDistributionAction()
    {
        $a_params = Request::ValidateParams(array('distributions_placeid' => 'numeric'));

        $o_distribution = new Model\Distribution(Database::GetConnection());

        return $o_distribution->DeleteDistribution($a_params['distributions_placeid']);
    }
    
    public function AddSizeAction()
    {
        $a_params = Request::ValidateParams(array('name' => 'string',
                                                  'factor' => 'string'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->AddSize($a_params['name'], $a_params['factor']);

    }

    public function GetSizeAction()
    {
        $a_params = Request::ValidateParams(array('menu_sizeid' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->GetSize($a_params['menu_sizeid']);
    }

    public function SetSizeAction()
    {
        $a_params = Request::ValidateParams(array('menu_sizeid' => 'numeric',
                                                  'name' => 'string',
                                                  'factor' => 'string'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->SetSize($a_params['menu_sizeid'],
                                    $a_params['name'],
                                    $a_params['factor']);
    }

    public function DeleteSizeAction()
    {
        $a_params = Request::ValidateParams(array('menu_sizeid' => 'numeric'));

        $o_products = new Model\Products(Database::GetConnection());

        return $o_products->DeleteSize($a_params['menu_sizeid']);
    }
}
