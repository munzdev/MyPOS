<?php
/**
 * The following file shows how to bootstrap phpdbg so that you can mock specific server environments
 * 
 * Steps to run:
 * 
 * 1. Modify file to have needed vars for request simulation
 * 2. run command 'phpdbg' in same directory as this file
 * 3. ev include("phpdbg-bootstrap.php")
 * 4. exec index.php
 * 5. break ...
 * 6. run
 */
if (!defined('PHPDBG_BOOTSTRAPPED'))
{
    /* define these once */
    define("PHPDBG_BOOTPATH", "../src/public/API");
    define("PHPDBG_BOOTSTRAP", "index.php");
    define("PHPDBG_BOOTSTRAPPED", sprintf(
        "/%s", PHPDBG_BOOTSTRAP));
}

/*
* Superglobals are JIT, phpdbg will not over-write 
* whatever is set during bootstrap
*/

$_SERVER = array
(
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'HTTP_ACCEPT' => '...',
  'HTTP_USER_AGENT' => '...',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
  'HTTP_COOKIE' => '...',
  'PATH' => '/usr/local/bin:/usr/bin:/bin',
  'SERVER_SIGNATURE' => '...',
  'SERVER_SOFTWARE' => '...',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '127.0.0.1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '127.0.0.1',
  'DOCUMENT_ROOT' => PHPDBG_BOOTPATH,
  'REQUEST_SCHEME' => 'http',
  'CONTEXT_PREFIX' => '',
  'CONTEXT_DOCUMENT_ROOT' => PHPDBG_BOOTPATH,
  'SERVER_ADMIN' => '[no address given]',
  'SCRIPT_FILENAME' => sprintf(
    '%s/%s', PHPDBG_BOOTPATH, PHPDBG_BOOTSTRAP
  ),
  'REMOTE_PORT' => '47931',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'GET',
  'QUERY_STRING' => '',
  'REQUEST_URI' => PHPDBG_BOOTSTRAPPED,
  'SCRIPT_NAME' => PHPDBG_BOOTSTRAPPED,
  'PHP_SELF' => PHPDBG_BOOTSTRAPPED,
  'REQUEST_TIME' => time(),
);

$_GET = array();
$_REQUEST = array();
$_POST = array();
$_COOKIE = array();
$_FILES = array();

chdir(PHPDBG_BOOTPATH);