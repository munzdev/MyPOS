<?php
namespace Lib;

use PDO;

class Database
{
	private static $o_pdo_connection;

	public static function GetConnection()
	{
		if (self::$o_pdo_connection == null)
		{
			$a_config = $GLOBALS['a_config']['DB'];

			$str_dsn = $a_config['Typ'] . ":dbname=" . $a_config['Database'] . ';host=' . $a_config['Host'];

			self::$o_pdo_connection = new PDO($str_dsn,
                                                          $a_config['User'],
                                                          $a_config['Password'],
                                                          array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                                                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                                                                PDO::ATTR_PERSISTENT => $a_config['Persistent']
                                                          ));
		}

		return self::$o_pdo_connection;
	}
}