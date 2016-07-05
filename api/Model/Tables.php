<?php
namespace Model;

class Tables
{
    private $o_db;

    public function __construct(\PDO $o_db)
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

        $o_statement->execute();

        return $o_statement;
    }

    public function GetTable($str_tableId)
    {
        $o_statement = $this->o_db->prepare("SELECT *
                                             FROM tables
                                             WHERE tableid = :tableid");

        $o_statement->execute(array(':tableid' => $str_tableId));

        $a_table = $o_statement->fetch(\PDO::FETCH_ASSOC);

        return $a_table;
    }

    public function GetAll()
    {
        $o_statement = $this->o_db->prepare("SELECT *
                                             FROM tables");

        $o_statement->execute();

        $a_tables = $o_statement->fetchAll(\PDO::FETCH_ASSOC);

        return $a_tables;
    }
}