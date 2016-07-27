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
		$o_statement = $this->o_db->prepare("SELECT mt.menu_typeid,
                                                            mt.name as Type_Name,
                                                            mt.tax,
                                                            mt.allowMixing,
                                                            mg.menu_groupid,
                                                            mg.name as Group_Name,
                                                            m.menuid,
                                                            m.name,
                                                            m.price,
                                                            m.availability
                                                    FROM menues m
                                                    INNER JOIN menu_groupes mg ON mg.menu_groupid = m.menu_groupid
                                                    INNER JOIN menu_types mt ON mt.menu_typeid = mg.menu_typeid
                                                    WHERE m.eventid = :eventid");

		$o_statement->execute(array(':eventid' => $i_eventid));

		$a_products = $o_statement->fetchAll();

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
}