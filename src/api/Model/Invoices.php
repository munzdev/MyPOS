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
        $o_statement = $this->o_db->prepare("INSERT INTO invoices(cashier_userid, date)
                                             VALUES(:userid, :date)");

        if($d_date)
            $o_statement->bindParam(":date", $d_date);
        else
            $o_statement->bindValue(":date", date(MyPOS\DATE_MYSQL_TIMEFORMAT));

        $o_statement->bindParam(":userid", $i_userid);
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

    public function GetInvoice($i_invoiceid)
    {
        $o_statement = $this->o_db->prepare("SELECT i.date,
                                                    CONCAT(u.firstname, ' ', u.lastname) AS cashier,
                                                    t.name
                                             FROM invoices i
                                             INNER JOIN users u ON u.userid = i.cashier_userid
                                             LEFT JOIN invoices_orders_details iod ON iod.invoices_orders_detailsid = (SELECT iod2.invoices_orders_detailsid
                                                                                                                        FROM invoices_orders_details iod2
                                                                                                                        WHERE iod2.invoiceid = i.invoiceid
                                                                                                                        LIMIT 1)
                                             LEFT JOIN orders_details od ON od.orders_detailid = iod.orders_detailid
                                             LEFT JOIN invoices_orders_details_special_extra iodse ON iodse.invoices_orders_details_special_extraid = (SELECT iodse2.invoices_orders_details_special_extraid
                                                                                                                                                        FROM invoices_orders_details_special_extra iodse2
                                                                                                                                                        WHERE iodse2.invoiceid = i.invoiceid
                                                                                                                                                        LIMIT 1)
                                             LEFT JOIN orders_details_special_extra odse ON odse.orders_details_special_extraid = iodse.orders_details_special_extraid
                                             LEFT JOIN orders o ON o.orderid = od.orderid OR o.orderid = odse.orderid
                                             LEFT JOIN tables t ON t.tableid = o.tableid
                                             WHERE i.invoiceid = :invoiceid");

        $o_statement->bindParam(":invoiceid", $i_invoiceid);
        $o_statement->execute();

        $a_invoice = $o_statement->fetch();

        $o_statement = $this->o_db->prepare("SELECT iod.amount,
                                                    od.single_price AS price,
                                                    mt.tax,
                                                    m.name,
                                                    ms.name AS sizeName,
                                                    ms.menu_sizeid,
                                                    GROUP_CONCAT(me.name ORDER BY me.name SEPARATOR ', ') AS selectedExtras,
                                                    (SELECT GROUP_CONCAT(m2.name ORDER BY m2.name SEPARATOR ', ')
                                                        FROM orders_details_mixed_with odmw
                                                        INNER JOIN menues m2 ON m2.menuid = odmw.menuid
                                                        WHERE odmw.orders_detailid = iod.orders_detailid) AS mixedWith,
                                                    od.extra_detail
                                             FROM invoices_orders_details iod
                                             INNER JOIN orders_details od ON od.orders_detailid = iod.orders_detailid
                                             INNER JOIN menues m ON m.menuid = od.menuid
                                             INNER JOIN menu_groupes mg ON mg.menu_groupid = m.menu_groupid
                                             INNER JOIN menu_types mt ON mt.menu_typeid = mg.menu_typeid
                                             INNER JOIN orders_detail_sizes ods ON ods.orders_detailid = od.orders_detailid
                                             INNER JOIN menues_possible_sizes mps ON mps.menues_possible_sizeid = ods.menues_possible_sizeid
                                             INNER JOIN menu_sizes ms ON ms.menu_sizeid = mps.menu_sizeid
                                             LEFT JOIN orders_detail_extras ode ON ode.orders_detailid = od.orders_detailid
                                             LEFT JOIN menues_possible_extras mpe ON mpe.menues_possible_extraid = ode.menues_possible_extraid
                                             LEFT JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid

                                             WHERE iod.invoiceid = :invoiceid
                                             GROUP By od.orders_detailid");

        $o_statement->bindParam(":invoiceid", $i_invoiceid);
        $o_statement->execute();

        $a_invoice_details = $o_statement->fetchAll();

        $o_statement = $this->o_db->prepare("SELECT iodse.amount,
                                                    odse.single_price AS price,
                                                    mt.tax,
                                                    odse.extra_detail AS name
                                             FROM invoices_orders_details_special_extra iodse
                                             INNER JOIN orders_details_special_extra odse ON odse.orders_details_special_extraid = iodse.orders_details_special_extraid
                                             INNER JOIN menu_groupes mg ON mg.menu_groupid = odse.menu_groupid
                                             INNER JOIN menu_types mt ON mt.menu_typeid = mg.menu_typeid
                                             WHERE iodse.invoiceid = :invoiceid");

        $o_statement->bindParam(":invoiceid", $i_invoiceid);
        $o_statement->execute();

        $a_invoice_extras = $o_statement->fetchAll();

        $a_invoice['rows'] = array();

        foreach($a_invoice_details as $a_invoice_detail)
        {
            $str_name = $a_invoice_detail['name'];

            if($a_invoice_detail['menu_sizeid'] != MyPOS\ORDER_DEFAULT_SIZEID)
                $str_name .= " " . $a_invoice_detail['sizeName'];

            if(!empty($a_invoice_detail['mixedWith']))
                $str_name .= " Gemischt mit: " . $a_invoice_detail['mixedWith'] . ", ";

            if(empty($a_invoice_detail['selectedExtras']))
            {
                $str_name .= " " . $a_invoice_detail['extra_detail'];
            }
            else
            {
                if(empty($a_invoice_detail['extra_detail']))
                    $str_name .= " " . $a_invoice_detail['selectedExtras'];
                else
                    $str_name .= " " . $a_invoice_detail['selectedExtras'] . ", " . $a_invoice_detail['extra_detail'];
            }

            $str_name = trim($str_name);

            if(substr($str_name, -1) == ',')
                    $str_name = substr($str_name, 0, -1);

            $a_to_add = array('amount' => $a_invoice_detail['amount'],
                              'price' => $a_invoice_detail['price'],
                              'tax' => $a_invoice_detail['tax'],
                              'name' => $str_name);

            $b_existring_entrie = false;
            foreach($a_invoice['rows'] as $i_key => $a_existing_entry)
            {
                if($a_existing_entry['name'] == $a_to_add['name'] &&
                   $a_existing_entry['tax'] == $a_to_add['tax'] &&
                   $a_existing_entry['price'] == $a_to_add['price'])
                {
                    $a_invoice['rows'][$i_key]['amount']++;
                    $b_existring_entrie = true;
                }
            }

            if(!$b_existring_entrie)
                $a_invoice['rows'][] = $a_to_add;
        }

        if(!empty($a_invoice_extras))
            array_push($a_invoice['rows'], $a_invoice_extras);

        return $a_invoice;
    }
}