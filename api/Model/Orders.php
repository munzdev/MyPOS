<?php
namespace Model;

class Orders
{
	private $o_db;

	public function __construct(\PDO $o_db)
	{
            $this->o_db = $o_db;
	}

	public function GetList($i_eventid, $i_userid, $b_finished)
	{
            $o_statement = $this->o_db->prepare("SELECT o.orderid,
                                                        o.tableid,
                                                        t.name AS table_name,
                                                        o.ordertime,
                                                        o.priority,
                                                        IF(oip.done IS NULL, 1, IF(oip.done = FALSE, 2, 3)) AS status,
                                                        (SELECT SUM(od1.amount * od1.single_price)
                                                            FROM orders_details od1
                                                            WHERE od1.orderid = o.orderid ) AS price,
                                                        (SELECT SUM(od2.amount_payed)
                                                            FROM orders_details od2
                                                            WHERE od2.orderid = o.orderid ) AS payed
                                                 FROM orders o
                                                 INNER JOIN tables t ON t.tableid = o.tableid
                                                 LEFT JOIN orders_in_progress oip ON oip.orderid = o.orderid
                                                 WHERE o.eventid = :eventid
                                                           AND o.userid = :userid
                                                           AND o.finished = :finished
                                                 ORDER BY o.priority ASC");

            $o_statement->execute(array(':eventid' => $i_eventid,
                                        ':userid' => $i_userid,
                                        ':finished' => $b_finished
            ));

            $a_orders = $o_statement->fetchAll(\PDO::FETCH_ASSOC);

            return $a_orders;
	}

        public function Add($i_eventid, $i_userid, $str_tableNr)
        {
            $o_statement = $this->o_db->prepare("INSERT INTO orders(eventid, tableiid, userid, ordertime, priority )
                                                 VALUES(:username, MD5(:password), :firstname, :lastname, :phonenumber, :is_admin)");

            $o_statement->bindparam(":username", $str_username);
            $o_statement->bindparam(":password", $str_password);
            $o_statement->bindparam(":firstname", $str_firstname);
            $o_statement->bindparam(":lastname", $str_lastname);
            $o_statement->bindparam(":phonenumber", $str_phonenumber);
            $o_statement->bindparam(":is_admin", $b_is_admin);
            $o_statement->execute();

            return $o_statement;
        }
}