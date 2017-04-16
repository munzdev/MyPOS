<?php
namespace API\Lib;

use API\Lib\Interfaces\IRememberMe;
use Exception;

class RememberMe implements IRememberMe
{
    private $key = null;

    public function __construct(string $privateKey)
    {
        $this->key = $privateKey;
    }

    /**
     *
     * @return string|false
     * @throws Exception
     */
    public function parseCookie()
    {
        $cookie = $this->getCookie();

        if ($cookie === false) {
            return false;
        }

        $var = $cookie['user'] . $cookie['token'];

        // Check Signature
        if (! $this->verify($var, $cookie['signature'])) {
            self::destroy();
            throw new Exception("Cokies has been tampared with");
        }

        return $cookie['user'];
    }

    /**
     *
     * @param string $hash
     * @return string|false
     * @throws Exception
     */
    public function validateHash(string $hash)
    {
        $cookie = $this->getCookie();

        if ($cookie === false) {
            return false;
        }

        // Check Database
        if (!$hash) {
            return false; // User must have deleted accout
        }

        // Check User Data
        if (!$info = json_decode($hash, true)) {
            self::destroy();
            throw new Exception("User Data corrupted");
        }

        // Verify Token
        if ($info['token'] !== $cookie['token']) {
            self::destroy();
            throw new Exception("System Hijacked or User use another browser");
        }

        /**
         * Important
         * To make sure the cookie is always change
         * reset the Token information
         */

        return $this->remember($info['user']);
    }

    /**
     *
     * @param int $userid
     * @return string
     */
    public function remember(int $userid)
    {
        $cookie = [
                        "user" => $userid,
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

    public function destroy()
    {
        $_COOKIE['auto'] = null;
        setcookie('auto', null, -1, '/');
    }

    private function verify(string $data, string $hash)
    {
        $rand = substr($hash, 0, 4);
        return $this->hash($data, $rand) === $hash;
    }

    private function hash(string $value, string $rand = null)
    {
        if ($rand === null) {
            $rand = $this->getRand(4);
        }

        return $rand . bin2hex(hash_hmac('sha256', $value . $rand, $this->key, true));
    }

    private function getRand(int $length)
    {
        switch (true) {
            case function_exists("openssl_random_pseudo_bytes"):
                $randomBytes = openssl_random_pseudo_bytes($length);
                break;
            case is_readable('/dev/urandom'): // deceze
                $randomBytes = file_get_contents('/dev/urandom', false, null, 0, $length);
                break;
            default:
                $counter = 0;
                $randomBytes = "";
                while ($counter ++ < $length) {
                    $randomBytes .= chr(mt_rand(0, 255));
                }
                break;
        }
        return substr(bin2hex($randomBytes), 0, $length);
    }

    /**
     *
     * @return array|false
     */
    private function getCookie()
    {
        // Check if remeber me cookie is present
        if (!filter_has_var(INPUT_COOKIE, "auto") || empty(filter_input(INPUT_COOKIE, "auto"))) {
            return false;
        }

       $cookie = json_decode(filter_input(INPUT_COOKIE, "auto"), true);

        if (!$cookie) {
            return false;
        }

        // Check all parameters
        if (! (isset($cookie['user']) || isset($cookie['token']) || isset($cookie['signature']))) {
            return false;
        }

        return $cookie;
    }
}
