<?php
class RememberMe {
	private $str_key = null;
	private $o_users;

	function __construct(Model\Users $o_users, $str_privatekey) {
		$this->str_key = $str_privatekey;
		$this->o_users = $o_users;
	}

	public function auth() {

		// Check if remeber me cookie is present
		if (! isset($_COOKIE["auto"]) || empty($_COOKIE["auto"])) {
			return false;
		}

		// Decode cookie value
		if (! $cookie = @json_decode($_COOKIE["auto"], true)) {
			return false;
		}

		// Check all parameters
		if (! (isset($cookie['user']) || isset($cookie['token']) || isset($cookie['signature']))) {
			return false;
		}

		$var = $cookie['user'] . $cookie['token'];

		// Check Signature
		if (! $this->verify($var, $cookie['signature'])) {
			self::Destroy();
			throw new Exception("Cokies has been tampared with");
		}

		// Check Database
		$a_user = $this->o_users->GetUserDetailsByID($cookie['user']);
		$info = $a_user['autologin_hash'];
		if (! $info) {
			return false; // User must have deleted accout
		}

		// Check User Data
		if (! $info = json_decode($info, true)) {
			self::Destroy();
			throw new Exception("User Data corrupted");
		}

		// Verify Token
		if ($info['token'] !== $cookie['token']) {
			self::Destroy();
			throw new Exception("System Hijacked or User use another browser");
		}

		/**
		 * Important
		 * To make sure the cookie is always change
		 * reset the Token information
		 */

		$this->remember($info['user']);
		return $a_user;
	}

	public function remember($i_userid) {
		$cookie = [
				"user" => $i_userid,
				"token" => $this->getRand(64),
				"signature" => null
		];
		$cookie['signature'] = $this->hash($cookie['user'] . $cookie['token']);
		$encoded = json_encode($cookie);

		// Add User to database
		$this->o_users->SetAuthKey($i_userid, $encoded);

		/**
		 * Set Cookies
		 * In production enviroment Use
		 * setcookie("auto", $encoded, time() + $expiration, "/~root/",
		 * "example.com", 1, 1);
		 */
		setcookie("auto", $encoded, time() + 31536000, "/", null, null, 1);
	}

	public function verify($data, $hash) {
		$rand = substr($hash, 0, 4);
		return $this->hash($data, $rand) === $hash;
	}
	
	public static function Destroy()
	{
		unset($_COOKIE['auto']);
		setcookie('auto', null, -1, '/');		
	}

	private function hash($value, $rand = null) {
		$rand = $rand === null ? $this->getRand(4) : $rand;
		return $rand . bin2hex(hash_hmac('sha256', $value . $rand, $this->str_key, true));
	}

	private function getRand($length) {
		switch (true) {
			case function_exists("mcrypt_create_iv") :
				$r = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
				break;
			case function_exists("openssl_random_pseudo_bytes") :
				$r = openssl_random_pseudo_bytes($length);
				break;
			case is_readable('/dev/urandom') : // deceze
				$r = file_get_contents('/dev/urandom', false, null, 0, $length);
				break;
			default :
				$i = 0;
				$r = "";
				while($i ++ < $length) {
					$r .= chr(mt_rand(0, 255));
				}
				break;
		}
		return substr(bin2hex($r), 0, $length);
	}
}