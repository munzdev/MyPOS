<?php
namespace Model;

class Invoices
{
    private $o_db;

    public function __construct(\PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function Add($d_date = null)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO invoices(date)
                                             VALUES(:date)");

        if($d_date)
            $o_statement->bindParam(":date", $d_date);
        else
            $o_statement->bindValue(":date", date(MYSQL_TIMEFORMAT));

        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }
}