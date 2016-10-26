<?php
namespace API\Lib;

use Model\Users;
use Exception;

class RememberMe
{
    private $str_key = null;

    function __construct(string $str_privateKey)
    {
        $this->str_key = $str_privateKey;
    }        

    public function parseCookie()
    {
        $cookie = $this->getCookie();
        
        if($cookie === false)
            return false;

        $var = $cookie['user'] . $cookie['token'];

        // Check Signature
        if (! $this->verify($var, $cookie['signature']))
        {
            self::Destroy();
            throw new Exception("Cokies has been tampared with");
        }
        
        return $cookie['user'];
    }
    
    public function validateHash($str_hash)
    {        
        $cookie = $this->getCookie();
        
        if($cookie === false)
            return false;
        
        // Check Database
        if (!$str_hash)
        {
            return false; // User must have deleted accout
        }

        // Check User Data
        if (!$a_info = json_decode($str_hash, true))
        {
            self::Destroy();
            throw new Exception("User Data corrupted");
        }

        // Verify Token
        if ($a_info['token'] !== $cookie['token'])
        {
            self::Destroy();
            throw new Exception("System Hijacked or User use another browser");
        }

        /**
         * Important
         * To make sure the cookie is always change
         * reset the Token information
         */

        return $this->remember($a_info['user']);
    }

    public function remember(int $i_userid) {
        $cookie = [
                        "user" => $i_userid,
                        "token" => $this->getRand(64),
                        "signature" => null
        ];
        $cookie['signature'] = $this->hash($cookie['user'] . $cookie['token']);
        $encoded = json_encode($cookie);

        // Add User to database
        //$this->o_usersQuery->SetAuthKey($i_userid, $encoded);

        /**
         * Set Cookies
         * In production enviroment Use
         * setcookie("auto", $encoded, time() + $expiration, "/~root/",
         * "example.com", 1, 1);
         */
        setcookie("auto", $encoded, time() + 31536000, "/", null, null, 1);
        
        return $encoded;
    }

    public function verify(string $data, string $hash)
    {
        $rand = substr($hash, 0, 4);
        return $this->hash($data, $rand) === $hash;
    }

    public static function Destroy()
    {
        unset($_COOKIE['auto']);
        setcookie('auto', null, -1, '/');
    }

    private function hash(string $value, string $rand = null)
    {
        $rand = $rand === null ? $this->getRand(4) : $rand;
        return $rand . bin2hex(hash_hmac('sha256', $value . $rand, $this->str_key, true));
    }

    private function getRand(int $length)
    {
        switch (true) {
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
    
    private function getCookie()
    {
        // Check if remeber me cookie is present
        if (! isset($_COOKIE["auto"]) || empty($_COOKIE["auto"]))
        {
            return false;
        }

        // Decode cookie value
        if (! $cookie = @json_decode($_COOKIE["auto"], true))
        {
            return false;
        }

        // Check all parameters
        if (! (isset($cookie['user']) || isset($cookie['token']) || isset($cookie['signature'])))
        {
            return false;
        }
        
        return $cookie;
    }
}