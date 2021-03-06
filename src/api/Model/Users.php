<?php
namespace Model;

use PDO;

class Users
{
    private $o_db;

    private $str_select_user_details = "SELECT u.*,
                                               eu.events_userid,
                                                eu.user_roles,
                                                e.eventid,
                                                e.name,
                                                e.date
                                   FROM users u
                                   INNER JOIN events_user eu ON eu.userid = u.userid
                                   INNER JOIN events e ON e.eventid = eu.eventid AND e.active = 1
                                       WHERE {WHERE}
                                   LIMIT 1";

    private $str_select_admin_details = "SELECT *,
                                                NULL AS events_userid,
                                                (SELECT SUM(events_user_roleid) FROM events_user_role) AS user_roles,
                                                0 AS eventid,
                                                'Admin Login' AS name,
                                                NOW() AS date
                                          FROM users
                                          WHERE {WHERE}
                                                AND is_admin = 1
                                          LIMIT 1";

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function GetUserDetailsByUsername($str_username)
    {
        $str_query = str_replace("{WHERE}", "u.username=:username", $this->str_select_user_details);

        $o_statement = $this->o_db->prepare($str_query);

        $o_statement->execute(array(':username' => $str_username));

        return $o_statement->fetch();
    }

    public function GetUserDetailsByID($i_userid)
    {
        $str_query = str_replace("{WHERE}", "u.userid=:userid", $this->str_select_user_details);

        $o_statement = $this->o_db->prepare($str_query);

        $o_statement->execute(array(':userid' => $i_userid));

        return $o_statement->fetch();
    }

    public function GetAdminDetailsByUsername($str_username)
    {
        $str_query = str_replace("{WHERE}", "username=:username", $this->str_select_admin_details);

        $o_statement = $this->o_db->prepare($str_query);

        $o_statement->execute(array(':username' => $str_username));

        return $o_statement->fetch();
    }

    public function GetAdminDetailsByID($i_userid)
    {
        $str_query = str_replace("{WHERE}", "userid=:userid", $this->str_select_admin_details);

        $o_statement = $this->o_db->prepare($str_query);

        $o_statement->execute(array(':userid' => $i_userid));

        return $o_statement->fetch();
    }

    public function GetUserByID($i_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT u.userid,
                                                    u.username,
                                                    u.firstname,
                                                    u.lastname,
                                                    u.phonenumber,
                                                    u.is_admin,
                                                    eu.user_roles,
                                                    e.eventid,
                                                    e.name,
                                                    e.date
                                            FROM users u
                                            INNER JOIN events_user eu ON eu.userid = u.userid
                                            INNER JOIN events e ON e.eventid = eu.eventid AND e.active = 1
                                            WHERE u.userid=:userid
                                            LIMIT 1");

        $o_statement->execute(array(':userid' => $i_userid));

        return $o_statement->fetch();
    }

    public function GetAdminByID($i_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT userid,
                                                    username,
                                                    firstname,
                                                    lastname,
                                                    phonenumber,
                                                    is_admin,
                                                    (SELECT SUM(user_roleid) FROM user_role) AS user_roles,
                                                    0 AS eventid,
                                                    'Admin Login' AS name,
                                                    NOW() AS date
                                            FROM users
                                            WHERE userid=:userid
                                                  AND is_admin = 1
                                            LIMIT 1");

        $o_statement->execute(array(':userid' => $i_userid));

        return $o_statement->fetch();
    }

    public function SetAuthKey($i_userid, $str_key)
    {
        $o_statement = $this->o_db->prepare("UPDATE users SET autologin_hash=:hash WHERE userid=:userid");

        return $o_statement->execute(array(':userid' => $i_userid, ':hash' => $str_key));
    }

    public function AddUser($str_username, $str_password, $str_firstname, $str_lastname, $str_phonenumber, $b_is_admin, $b_active)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO users(username, password, firstname, lastname, phonenumber, is_admin, active )
                                             VALUES(:username, MD5(:password), :firstname, :lastname, :phonenumber, :is_admin, :active)");

        $o_statement->bindparam(":username", $str_username);
        $o_statement->bindparam(":password", $str_password);
        $o_statement->bindparam(":firstname", $str_firstname);
        $o_statement->bindparam(":lastname", $str_lastname);
        $o_statement->bindparam(":phonenumber", $str_phonenumber);
        $o_statement->bindparam(":is_admin", $b_is_admin);
        $o_statement->bindparam(":active", $b_active);
        $o_statement->execute();

        return $o_statement;
    }

    public function SetUser($i_userid, $str_username, $str_password, $str_firstname, $str_lastname, $str_phonenumber, $b_is_admin, $b_active)
    {
        $str_password_update = "";

        if(!empty($str_password))
        {
            $str_password_update = "password = MD5(:password),";
        }

        $o_statement = $this->o_db->prepare("UPDATE users
                                             SET username = :username,
                                                 $str_password_update
                                                 firstname = :firstname,
                                                 lastname = :lastname,
                                                 phonenumber = :phonenumber,
                                                 is_admin = :is_admin,
                                                 active = :active
                                             WHERE userid = :userid");

        $o_statement->bindparam(":userid", $i_userid);
        $o_statement->bindparam(":username", $str_username);
        $o_statement->bindparam(":firstname", $str_firstname);
        $o_statement->bindparam(":lastname", $str_lastname);
        $o_statement->bindparam(":phonenumber", $str_phonenumber);
        $o_statement->bindparam(":is_admin", $b_is_admin);
        $o_statement->bindparam(":active", $b_active);

        if(!empty($str_password))
            $o_statement->bindparam(":password", $str_password);

        $o_statement->execute();

        return $o_statement;
    }

    public function GetUsers($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT u.userid,
                                                    u.username,
                                                    u.firstname,
                                                    u.lastname,
                                                    u.phonenumber,
                                                    u.is_admin,
                                                    eu.user_roles,
                                                    eu.events_userid,
                                                    e.eventid,
                                                    e.name,
                                                    e.date
                                            FROM users u
                                            INNER JOIN events_user eu ON eu.userid = u.userid
                                            INNER JOIN events e ON e.eventid = eu.eventid AND e.active = 1
                                            WHERE eu.eventid = :eventid");

        $o_statement->execute(array(':eventid' => $i_eventid));

        return $o_statement->fetchAll();
    }

    public function GetUser($i_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT u.username,
                                                    u.firstname,
                                                    u.lastname,
                                                    u.phonenumber,
                                                    u.is_admin,
                                                    u.active
                                            FROM users u
                                            WHERE u.userid = :userid");

        $o_statement->bindParam(":userid", $i_userid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function GetAllUsers()
    {
        $o_statement = $this->o_db->prepare("SELECT u.userid,
                                                    u.username,
                                                    u.firstname,
                                                    u.lastname,
                                                    u.phonenumber,
                                                    u.is_admin,
                                                    u.active
                                            FROM users u");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function SetCallRequest($i_userid, $b_reset)
    {
        if($b_reset == 'true')
        {
            $o_statement = $this->o_db->prepare("UPDATE users
                                                 SET call_request = NULL
                                                 WHERE userid = :userid");
        }
        else
        {
            $o_statement = $this->o_db->prepare("UPDATE users
                                                 SET call_request = NOW()
                                                 WHERE userid = :userid");
        }

        $o_statement->bindParam(':userid', $i_userid);
        return $o_statement->execute();
    }

    public function GetCallRequestList($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT u.userid,
                                                    u.username,
                                                    u.firstname,
                                                    u.lastname,
                                                    u.phonenumber,
                                                    u.is_admin,
                                                    u.call_request,
                                                    eu.user_roles,
                                                    eu.events_userid,
                                                    e.eventid,
                                                    e.name,
                                                    e.date
                                            FROM users u
                                            INNER JOIN events_user eu ON eu.userid = u.userid
                                            INNER JOIN events e ON e.eventid = eu.eventid AND e.active = 1
                                            WHERE eu.eventid = :eventid
                                                  AND call_request IS NOT NULL");

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function Delete($i_userid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM users
                                             WHERE userid = :userid");

        $o_statement->bindParam(":userid", $i_userid);
        return $o_statement->execute();
    }
}