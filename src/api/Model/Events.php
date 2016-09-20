<?php
namespace Model;

use PDO;

class Events
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function GetRoles()
    {
        $o_statement = $this->o_db->prepare("SELECT * FROM events_user_role");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetEventsList()
    {
        $o_statement = $this->o_db->prepare("SELECT eventid,
                                                    name,
                                                    date,
                                                    active
                                            FROM events
                                            ORDER BY date DESC");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function AddUser($i_eventid, $i_userid, $i_role, $i_begin_money)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO events_user(eventid, userid, user_roles, begin_money)
                                             VALUES(:eventid, :userid, :user_roles, :begin_money)");

        $o_statement->bindparam(":eventid", $i_eventid);
        $o_statement->bindparam(":begin_money", $i_begin_money);
        $o_statement->bindparam(":userid", $i_userid);
        $o_statement->bindparam(":user_roles", $i_role);
        return $o_statement->execute();
    }

    public function AddEvent($str_name, $d_date, $b_active)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO events(name, date, active)
                                             VALUES(:name, :date, :active)");

        $o_statement->bindparam(":name", $str_name);
        $o_statement->bindparam(":date", $d_date);
        $o_statement->bindparam(":active", $b_active);

        return $o_statement->execute();
    }

    public function GetPrinters($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT events_printerid,
                                                    name,
                                                    `default`,
                                                    ip,
                                                    port,
                                                    characters_per_row
                                             FROM events_printers
                                             WHERE eventid = :eventid");

        $o_statement->bindParam(':eventid', $i_eventid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetPrinter($i_printerid)
    {
        $o_statement = $this->o_db->prepare("SELECT eventid,
                                                    name,
                                                    `default`,
                                                    ip,
                                                    port,
                                                    characters_per_row
                                             FROM events_printers
                                             WHERE events_printerid = :printerid");

        $o_statement->bindParam(':printerid', $i_printerid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetActive($i_eventid)
    {
        $o_statement = $this->o_db->prepare("UPDATE events
                                             SET active = 0");
        $o_statement->execute();

        $o_statement = $this->o_db->prepare("UPDATE events
                                             SET active = 1
                                             WHERE eventid = :eventid");
        $o_statement->bindParam(':eventid', $i_eventid);
        return $o_statement->execute();
    }

    public function Delete($i_eventid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM events
                                             WHERE eventid = :eventid");

        $o_statement->bindParam(':eventid', $i_eventid);
        return $o_statement->execute();
    }

    public function GetEvent($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT eventid,
                                                    name,
                                                    date,
                                                    active
                                            FROM events
                                            WHERE eventid = :eventid");

        $o_statement->bindParam(':eventid', $i_eventid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetEvent($i_eventid, $str_name, $d_date)
    {
        $o_statement = $this->o_db->prepare("UPDATE events
                                             SET name = :name,
                                                 date = :date
                                            WHERE eventid = :eventid");

        $o_statement->bindParam(':name', $str_name);
        $o_statement->bindParam(':date', $d_date);
        $o_statement->bindParam(':eventid', $i_eventid);
        return $o_statement->execute();
    }

    public function GetUserList($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT eu.events_userid,
                                                    eu.userid,
                                                    u.username,
                                                    CONCAT(u.firstname, ' ', u.lastname) AS name,
                                                    eu.user_roles,
                                                    GROUP_CONCAT(eur.name SEPARATOR ', ') AS roles,
                                                    eu.begin_money
                                            FROM events_user eu
                                            INNER JOIN users u ON u.userid = eu.userid
                                            LEFT JOIN events_user_role eur ON eur.events_user_roleid & eu.user_roles
                                            WHERE eu.eventid = :eventid
                                            GROUP BY eu.events_userid");

        $o_statement->bindParam(':eventid', $i_eventid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetUser($i_events_userid)
    {
        $o_statement = $this->o_db->prepare("SELECT user_roles,
                                                    begin_money
                                             FROM events_user
                                             WHERE events_userid = :events_userid");

        $o_statement->bindParam(':events_userid', $i_events_userid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetUser($i_events_userid, $i_user_roles, $i_begin_money)
    {
        $o_statement = $this->o_db->prepare("UPDATE events_user
                                             SET user_roles = :user_roles,
                                                 begin_money = :begin_money
                                             WHERE events_userid = :events_userid");

        $o_statement->bindParam(':user_roles', $i_user_roles);
        $o_statement->bindParam(':begin_money', $i_begin_money);
        $o_statement->bindParam(':events_userid', $i_events_userid);
        return $o_statement->execute();
    }

    public function DeleteUser($i_events_userid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM events_user
                                             WHERE events_userid = :events_userid");

        $o_statement->bindParam(':events_userid', $i_events_userid);
        return $o_statement->execute();
    }
}