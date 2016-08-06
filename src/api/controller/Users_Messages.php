<?php
namespace Controller;

use Lib\SecurityController;
use Lib\Database;
use Lib\Login;
use Lib\Request;
use Model;

class Users_Messages extends SecurityController
{
    public function GetUsersMessagesAction()
    {
        $o_users_messages = new Model\Users_Messages(Database::GetConnection());

        $a_user = Login::GetCurrentUser();

        return $o_users_messages->GetMessages($a_user['events_userid']);
    }

    public function MarkReadedAction()
    {
        $a_params = Request::ValidateParams(array('channel' => 'string'));

        $o_users_messages = new Model\Users_Messages(Database::GetConnection());

        $a_user = Login::GetCurrentUser();

        return $o_users_messages->MarkReaded($a_user['events_userid'], $a_params['channel']);
    }
}