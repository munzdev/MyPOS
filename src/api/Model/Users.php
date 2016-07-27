<?php
namespace Model;

use PDO;

class Users
{
	private $o_db;

	private $str_select_user_details = "SELECT u.*,
											 eu.user_roles,
											 e.eventid,
											 e.name,
											 e.date
									    FROM users u
									    INNER JOIN events_user eu ON eu.userid = u.userid
									    INNER JOIN events e ON e.eventid = eu.eventid AND e.active = 1
										WHERE {WHERE}
									    LIMIT 1";

	private $str_select_admin_details = "SELECT *,
												(SELECT SUM(user_roleid) FROM user_role) AS user_roles,
												0 AS eventid,
												'Admin Login' AS name,
												NOW() AS date
										  FROM users
										  WHERE {WHERE}
         								 		AND is_admin = 1
										  LIMIT 1";

	public function __construct(PDO $o_db)
	{
		$this->o_db = $o_db;
	}

	public function GetUserDetailsByUsername($str_username)
	{
		$str_query = str_replace("{WHERE}", "u.username=:username", $this->str_select_user_details);

		$o_statement = $this->o_db->prepare($str_query);

		$o_statement->execute(array(':username' => $str_username));

		return $o_statement->fetch();
	}

	public function GetUserDetailsByID($i_userid)
	{
		$str_query = str_replace("{WHERE}", "u.userid=:userid", $this->str_select_user_details);

		$o_statement = $this->o_db->prepare($str_query);

		$o_statement->execute(array(':userid' => $i_userid));

		return $o_statement->fetch();
	}

	public function GetAdminDetailsByUsername($str_username)
	{
		$str_query = str_replace("{WHERE}", "username=:username", $this->str_select_admin_details);

		$o_statement = $this->o_db->prepare($str_query);

		$o_statement->execute(array(':username' => $str_username));

		return $o_statement->fetch();
	}

	public function GetAdminDetailsByID($i_userid)
	{
		$str_query = str_replace("{WHERE}", "userid=:userid", $this->str_select_admin_details);

		$o_statement = $this->o_db->prepare($str_query);

		$o_statement->execute(array(':userid' => $i_userid));

		return $o_statement->fetch();
	}

	public function GetUserByID($i_userid)
	{
		$o_statement = $this->o_db->prepare("SELECT u.userid,
										            u.username,
										            u.firstname,
										            u.lastname,
										            u.phonenumber,
										            u.is_admin,
													eu.user_roles,
													e.eventid,
													e.name,
													e.date
											FROM users u
											INNER JOIN events_user eu ON eu.userid = u.userid
											INNER JOIN events e ON e.eventid = eu.eventid AND e.active = 1
											WHERE u.userid=:userid
											LIMIT 1");

		$o_statement->execute(array(':userid' => $i_userid));

		return $o_statement->fetch();
	}

	public function GetAdminByID($i_userid)
	{
		$o_statement = $this->o_db->prepare("SELECT userid,
											            username,
											            firstname,
											            lastname,
											            phonenumber,
											            is_admin,
														(SELECT SUM(user_roleid) FROM user_role) AS user_roles,
														0 AS eventid,
														'Admin Login' AS name,
														NOW() AS date
											FROM users
											WHERE userid=:userid
         										  AND is_admin = 1
										    LIMIT 1");

		$o_statement->execute(array(':userid' => $i_userid));

		return $o_statement->fetch();
	}

	public function SetAuthKey($i_userid, $str_key)
	{
		$o_statement = $this->o_db->prepare("UPDATE users SET autologin_hash=:hash WHERE userid=:userid");

		return $o_statement->execute(array(':userid' => $i_userid, ':hash' => $str_key));
	}

	public function AddUser($str_username, $str_password, $str_firstname, $str_lastname, $str_phonenumber, $b_is_admin)
	{
		$o_statement = $this->o_db->prepare("INSERT INTO users(username, password, firstname, lastname, phonenumber, is_admin )
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