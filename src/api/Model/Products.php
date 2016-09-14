<?php
namespace Model;

use PDO;

class Products
{
    private $o_db;

    public function __construct(PDO $o_db)
    {
        $this->o_db = $o_db;
    }

    public function GetList($i_eventid)
    {
        $a_products = $this->GetMenues($i_eventid);

        $a_return = array();

        foreach ($a_products as $a_product)
        {
            if(!isset($a_return[$a_product['menu_typeid']]))
            {
                $a_return[$a_product['menu_typeid']] = array('menu_typeid' => $a_product['menu_typeid'],
                                                                                         'name' => $a_product['Type_Name'],
                                                                                         'tax' => $a_product['tax'],
                                                                                         'allowMixing' => $a_product['allowMixing'],
                                                                                         'groupes' => array());
            }

            if(!isset($a_return[$a_product['menu_typeid']]
                                    ['groupes']
                                        [$a_product['menu_groupid']]))
            {
                $a_return[$a_product['menu_typeid']]
                    ['groupes']
                        [$a_product['menu_groupid']] = array('menu_groupid' => $a_product['menu_groupid'],
                                                                                          'menu_typeid' => $a_product['menu_typeid'],
                                                                                          'name' => $a_product['Group_Name'],
                                                                                          'menues' => array());
            }

            $o_statement = $this->o_db->prepare("SELECT ms.menu_sizeid,
                                                        mps.menuid AS menuid,
                                                        ms.name,
                                                        ms.factor,
                                                        mps.price
                                                 FROM menues_possible_sizes mps
                                                 INNER JOIN menu_sizes ms ON ms.menu_sizeid = mps.menu_sizeid
                                                 WHERE mps.menuid = :menuid");

            $o_statement->execute(array(':menuid' => $a_product['menuid']));

            $a_products_sizes = $o_statement->fetchAll();

            $o_statement = $this->o_db->prepare("SELECT me.menu_extraid,
                                                        mpe.menuid AS menuid,
                                                        me.name,
                                                        me.availability,
                                                        me.availability_amount,
                                                        mpe.price
                                                 FROM menues_possible_extras mpe
                                                 INNER JOIN menu_extras me ON me.menu_extraid = mpe.menu_extraid
                                                 WHERE mpe.menuid = :menuid");

            $o_statement->execute(array(':menuid' => $a_product['menuid']));

            $a_products_extras = $o_statement->fetchAll();

            $a_return[$a_product['menu_typeid']]
                ['groupes']
                    [$a_product['menu_groupid']]
                        ['menues']
                            [$a_product['menuid']] = array('menuid' => $a_product['menuid'],
                                                                                  'menu_groupid' => $a_product['menu_groupid'],
                                                                                  'name' => $a_product['name'],
                                                                                  'price' => $a_product['price'],
                                                                                  'availability' => $a_product['availability'],
                                                                                  'extras' => $a_products_extras,
                                                                                  'sizes' => $a_products_sizes);
        }

        //-- Reset array Keys
        $a_return = array_values($a_return);
        foreach ($a_return as $i_key => $a_value)
        {
            foreach($a_value['groupes'] as $a_value_group)
            {
                $a_return[$i_key]['groupes'] = array_values($a_return[$i_key]['groupes']);

                foreach($a_return[$i_key]['groupes'] as $i_key_group => $a_value_menu)
                {
                    $a_return[$i_key]['groupes'][$i_key_group]['menues'] = array_values($a_return[$i_key]['groupes'][$i_key_group]['menues']);
                }

            }
        }

        return $a_return;
    }

    public function GetMenues($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT mt.menu_typeid,
                                                    mt.name as Type_Name,
                                                    mt.tax,
                                                    mt.allowMixing,
                                                    mg.menu_groupid,
                                                    mg.name as Group_Name,
                                                    m.menuid,
                                                    m.name,
                                                    m.price,
                                                    m.availability,
                                                    m.availability_amount
                                            FROM menues m
                                            INNER JOIN menu_groupes mg ON mg.menu_groupid = m.menu_groupid
                                            INNER JOIN menu_types mt ON mt.menu_typeid = mg.menu_typeid
                                            WHERE m.eventid = :eventid");

        $o_statement->execute(array(':eventid' => $i_eventid));

        return $o_statement->fetchAll();
    }

    public function GetExtras($i_eventid)
    {
        $o_statement = $this->o_db->prepare("SELECT menu_extraid,
                                                    name,
                                                    availability,
                                                    availability_amount
                                                 FROM menu_extras
                                                 WHERE eventid = :eventid");

        $o_statement->bindParam(':eventid', $i_eventid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetSizes()
    {
        $o_statement = $this->o_db->prepare("SELECT menu_sizeid,
                                                    name,
                                                    factor
                                                 FROM menu_sizes");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function SetMenuAvailabilityAmount($i_menuid, $i_amount)
    {
        $o_statement = $this->o_db->prepare("UPDATE menues
                                             SET availability_amount = :amount
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":amount", $i_amount);

        return $o_statement->execute();
    }

    public function SetExtraAvailabilityAmount($i_menu_extraid, $i_amount)
    {
        $o_statement = $this->o_db->prepare("UPDATE menu_extras
                                             SET availability_amount = :amount
                                             WHERE menu_extraid = :menu_extraid");

        $o_statement->bindParam(":menu_extraid", $i_menu_extraid);
        $o_statement->bindParam(":amount", $i_amount);

        return $o_statement->execute();
    }


    public function SetMenuAvailabilityStatus($i_menuid, $str_status)
    {
        $o_statement = $this->o_db->prepare("UPDATE menues
                                             SET availability = :status
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":status", $str_status);

        return $o_statement->execute();
    }

    public function SetExtraAvailabilityStatus($i_menu_extraid, $str_status)
    {
        $o_statement = $this->o_db->prepare("UPDATE menu_extras
                                             SET availability = :status
                                             WHERE menu_extraid = :menu_extraid");

        $o_statement->bindParam(":menu_extraid", $i_menu_extraid);
        $o_statement->bindParam(":status", $str_status);

        return $o_statement->execute();
    }

    public function GetTypesList()
    {
         $o_statement = $this->o_db->prepare("SELECT menu_typeid,
                                                     name,
                                                     tax,
                                                     allowMixing
                                              FROM menu_types
                                              ORDER BY name");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetGroupesList()
    {
         $o_statement = $this->o_db->prepare("SELECT menu_groupid,
                                                     menu_typeid,
                                                     name
                                              FROM menu_groupes
                                              ORDER BY name");

        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetType($i_menu_typeid)
    {
        $o_statement = $this->o_db->prepare("SELECT name,
                                                    tax,
                                                    allowMixing
                                             FROM menu_types
                                             WHERE menu_typeid = :menu_typeid");

        $o_statement->bindParam(":menu_typeid", $i_menu_typeid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function GetGroup($i_menu_groupid)
    {
         $o_statement = $this->o_db->prepare("SELECT menu_typeid,
                                                     name
                                              FROM menu_groupes
                                              WHERE menu_groupid = :menu_groupid");

        $o_statement->bindParam(":menu_groupid", $i_menu_groupid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function AddType($str_name, $i_tax, $b_allowMixing)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO menu_types (name, tax, allowMixing)
                                             VALUES (:name, :tax, :allowMixing)");

        $o_statement->bindParam(":name", $str_name);
        $o_statement->bindParam(":tax", $i_tax);
        $o_statement->bindParam(":allowMixing", $b_allowMixing);
        return $o_statement->execute();
    }

    public function SetType($i_menu_typeid, $str_name, $i_tax, $b_allowMixing)
    {
        $o_statement = $this->o_db->prepare("UPDATE menu_types
                                             SET name = :name,
                                                 tax = :tax,
                                                 allowMixing = :allowMixing
                                             WHERE menu_typeid = :menu_typeid");

        $o_statement->bindParam(":menu_typeid", $i_menu_typeid);
        $o_statement->bindParam(":name", $str_name);
        $o_statement->bindParam(":tax", $i_tax);
        $o_statement->bindParam(":allowMixing", $b_allowMixing);
        return $o_statement->execute();
    }

    public function AddGroup($i_menu_typeid, $str_name)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO menu_groupes (menu_typeid, name)
                                             VALUES (:menu_typeid, :name)");

        $o_statement->bindParam(":menu_typeid", $i_menu_typeid);
        $o_statement->bindParam(":name", $str_name);
        return $o_statement->execute();
    }

    public function SetGroup($i_menu_groupid, $str_name)
    {
        $o_statement = $this->o_db->prepare("UPDATE menu_groupes
                                             SET name = :name
                                             WHERE menu_groupid = :menu_groupid");

        $o_statement->bindParam(":menu_groupid", $i_menu_groupid);
        $o_statement->bindParam(":name", $str_name);
        return $o_statement->execute();
    }

    public function DeleteGroup($i_menu_groupid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM menu_groupes
                                             WHERE menu_groupid = :menu_groupid");

        $o_statement->bindParam(":menu_groupid", $i_menu_groupid);
        return $o_statement->execute();
    }

    public function DeleteType($i_menu_typeid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM menu_types
                                             WHERE menu_typeid = :menu_typeid");

        $o_statement->bindParam(":menu_typeid", $i_menu_typeid);
        return $o_statement->execute();
    }

    public function AddMenu($i_eventid, $i_menu_groupid, $str_name, $i_price, $str_availability, $i_availability_amount)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO menues (eventid, menu_groupid, name, price, availability, availability_amount)
                                             VALUES (:eventid, :menu_groupid, :name, :price, :availability, :availability_amount)");

        $o_statement->bindParam(":eventid", $i_eventid);
        $o_statement->bindParam(":menu_groupid", $i_menu_groupid);
        $o_statement->bindParam(":name", $str_name);
        $o_statement->bindParam(":price", $i_price);
        $o_statement->bindParam(":availability", $str_availability);
        $o_statement->bindParam(":availability_amount", $i_availability_amount);
        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function GetMenu($i_menuid)
    {
        $o_statement = $this->o_db->prepare("SELECT eventid,
                                                    menu_groupid,
                                                    name,
                                                    price,
                                                    availability,
                                                    availability_amount
                                             FROM menues
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetMenu($i_menuid, $str_name, $i_price, $str_availability, $i_availability_amount)
    {
        $o_statement = $this->o_db->prepare("UPDATE menues
                                             SET name = :name,
                                                 price = :price,
                                                 availability = :availability,
                                                 availability_amount = :availability_amount
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":name", $str_name);
        $o_statement->bindParam(":price", $i_price);
        $o_statement->bindParam(":availability", $str_availability);
        $o_statement->bindParam(":availability_amount", $i_availability_amount);
        return $o_statement->execute();
    }

    public function DeleteMenu($i_menuid)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM menues
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        return $o_statement->execute();
    }

    public function AddMenuExtra($i_menuid, $i_extraid, $i_price)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO menues_possible_extras (menuid, menu_extraid, price)
                                             VALUES (:menuid, :menu_extraid, :price)");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":menu_extraid", $i_extraid);
        $o_statement->bindParam(":price", $i_price);
        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }

    public function GetMenuExtras($i_menuid)
    {
        $o_statement = $this->o_db->prepare("SELECT menues_possible_extraid,
                                                    menu_extraid,
                                                    price
                                             FROM menues_possible_extras
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetMenuExtra($i_menuid, $i_menu_extraid)
    {
        $o_statement = $this->o_db->prepare("SELECT menues_possible_extraid,
                                                    price
                                             FROM menues_possible_extras
                                             WHERE menuid = :menuid
                                                   AND menu_extraid = :menu_extraid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":menu_extraid", $i_menu_extraid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetMenuExtraPrice($i_menues_possible_extraid, $i_price)
    {
        $o_statement = $this->o_db->prepare("UPDATE menues_possible_extras
                                             SET price = :price
                                             WHERE menues_possible_extraid = :menues_possible_extraid");

        $o_statement->bindParam(":menues_possible_extraid", $i_menues_possible_extraid);
        $o_statement->bindParam(":price", $i_price);
        return $o_statement->execute();
    }

    public function DeleteMenuExtrasWhereExtraNotIn($i_menuid, $a_extraids)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM menues_possible_extras
                                             WHERE menuid = :menuid
                                                   AND menu_extraid NOT IN (:extraids)");

        if(empty($a_extraids))
            $a_extraids[] = 0;

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindValue(":extraids", join(',', $a_extraids));
        return $o_statement->execute();
    }

    public function GetMenuSizes($i_menuid)
    {
        $o_statement = $this->o_db->prepare("SELECT menues_possible_sizeid,
                                                    menu_sizeid,
                                                    price
                                             FROM menues_possible_sizes
                                             WHERE menuid = :menuid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->execute();

        return $o_statement->fetchAll();
    }

    public function GetMenuSize($i_menuid, $i_menu_sizeid)
    {
        $o_statement = $this->o_db->prepare("SELECT menues_possible_sizeid,
                                                    price
                                             FROM menues_possible_sizes
                                             WHERE menuid = :menuid
                                                   AND menu_sizeid = :menu_sizeid");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":menu_sizeid", $i_menu_sizeid);
        $o_statement->execute();

        return $o_statement->fetch();
    }

    public function SetMenuSizePrice($i_menues_possible_sizeid, $i_price)
    {
        $o_statement = $this->o_db->prepare("UPDATE menues_possible_sizes
                                             SET price = :price
                                             WHERE menues_possible_sizeid = :menues_possible_sizeid");

        $o_statement->bindParam(":menues_possible_sizeid", $i_menues_possible_sizeid);
        $o_statement->bindParam(":price", $i_price);
        return $o_statement->execute();
    }

    public function DeleteMenuSizesWhereSizeNotIn($i_menuid, $a_sizeids)
    {
        $o_statement = $this->o_db->prepare("DELETE FROM menues_possible_sizes
                                             WHERE menuid = :menuid
                                                   AND menu_sizeid NOT IN (:sizeids)");

        if(empty($a_sizeids))
            $a_sizeids[] = 0;

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindValue(":sizeids", join(',', $a_sizeids));
        return $o_statement->execute();
    }

    public function AddMenuSize($i_menuid, $i_sizeid, $i_price)
    {
        $o_statement = $this->o_db->prepare("INSERT INTO menues_possible_sizes (menuid, menu_sizeid, price)
                                             VALUES (:menuid, :menu_sizeid, :price)");

        $o_statement->bindParam(":menuid", $i_menuid);
        $o_statement->bindParam(":menu_sizeid", $i_sizeid);
        $o_statement->bindParam(":price", $i_price);
        $o_statement->execute();

        return $this->o_db->lastInsertId();
    }
}