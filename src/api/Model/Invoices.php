<?php
namespace Model;

use PDO;
use MyPOS;

class Invoices
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function Add($i_userid, $d_date = null)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO invoices(date)
                                             VALUES(:date)");

        if($d_date)
            $o_statement->bindParam(":date", $d_date);
        else
            $o_statement->bindValue(":date", date(MyPOS\MYSQL_TIMEFORMAT));

        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function AddOrder($i_invoiceid, $i_orders_detailid, $i_amount)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO invoices_orders_details(invoiceid, orders_detailid, amount)
                                             VAlUES (:invoiceid, :orders_detailid, :amount)");

        $o_statement->bindParam(":invoiceid", $i_invoiceid);
        $o_statement->bindParam(":orders_detailid", $i_orders_detailid);
        $o_statement->bindParam(":amount", $i_amount);

        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function AddExtra($i_invoiceid, $i_orders_details_special_extraid, $i_amount)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO invoices_orders_details_special_extra(invoiceid, orders_details_special_extraid, amount)
                                             VAlUES (:invoiceid, :orders_details_special_extraid, :amount)");

        $o_statement->bindParam(":invoiceid", $i_invoiceid);
        $o_statement->bindParam(":orders_details_special_extraid", $i_orders_details_special_extraid);
        $o_statement->bindParam(":amount", $i_amount);

        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }
}