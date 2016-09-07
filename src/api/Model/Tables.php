<?php
namespace Model;

use PDO;

class Tables
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function GetTableID($str_tableNr)
    {
        $o_statement = $this->o_db->prepare("SELECT tableid
                                             FROM tables
                                             WHERE name = :name");

        $o_statement->execute(array(':name' => $str_tableNr));

        $i_tableId = $o_statement->fetchColumn();

        if(!$i_tableId)
        {
            $this->AddTable($str_tableNr, null);

            $i_tableId = $this->o_db->lastInsertId();
        }

        return $i_tableId;
    }

    public function AddTable($str_tableNr, $str_data)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO tables(name, data)
                                             VALUES(:name, :data)");

        $o_statement->bindparam(":name", $str_tableNr);
        $o_statement->bindparam(":data", $str_data);

        return $o_statement->execute();
    }

    public function GetTable($str_tableid)
    {
        $o_statement = $this->o_db->prepare("SELECT tableid,
                                                    name,
                                                    data
                                             FROM tables
                                             WHERE tableid = :tableid");

        $o_statement->bindParam(":tableid", $str_tableid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function GetAll()
    {
        $o_statement = $this->o_db->prepare("SELECT tableid,
                                                    name,
                                                    data
                                             FROM tables");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function SetTable($i_tableid, $str_tableNr, $str_data)
    {
        $o_statement = $this->o_db->prepare("UPDATE tables
                                             SET name = :name,
                                                 data = :data
                                             WHERE tableid = :tableid");

        $o_statement->bindparam(":tableid", $i_tableid);
        $o_statement->bindparam(":name", $str_tableNr);
        $o_statement->bindparam(":data", $str_data);

        return $o_statement->execute();
    }

    public function Delete($i_tableid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM tables
                                             WHERE tableid = :tableid");

        $o_statement->bindparam(":tableid", $i_tableid);

        return $o_statement->execute();
    }
}