<?php

use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Connection\ConnectionWrapper;
use Propel\Runtime\Connection\DebugPDO;
use Propel\Runtime\Propel;
use const API\DEBUG;

/**
 * Original function from User comment on http://php.net/manual/de/function.str-pad.php
 * User: wes@nospamplsexample.org
 *
 * @param  string $str
 * @param  int    $padLen
 * @param  string $padStr
 * @param  int    $dir
 * @param  string $encoding
 * @return string
 */
function mb_str_pad($str, $padLen, $padStr = ' ', $dir = STR_PAD_RIGHT, $encoding = null)
{
    $encoding = $encoding === null ? mb_internal_encoding() : $encoding;
    $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
    $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
    $padLen -= mb_strlen($str, $encoding);
    $targetLen = $padBefore && $padAfter ? $padLen / 2 : $padLen;
    $strToRepeatLen = mb_strlen($padStr, $encoding);
    $repeatTimes = ceil($targetLen / $strToRepeatLen);
    $repeatedString = str_repeat($padStr, max(0, $repeatTimes)); // safe if used with valid utf-8 strings
    $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
    $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
    return $before . $str . $after;
}

function registerPropelConnection($dbConfig)
{
    $serviceContainer = Propel::getServiceContainer();
    $serviceContainer->checkVersion('2.0.0-dev');
    $serviceContainer->setAdapterClass('default', $dbConfig['adapter']);
    $manager = new ConnectionManagerSingle();
    
    $config = array('dsn' => $dbConfig['dsn'],
                    'user' => $dbConfig['user'],
                    'password' => $dbConfig['password'],
                    'settings' => $dbConfig['settings'],
                    'classname' => (DEBUG) ? DebugPDO::class : ConnectionWrapper::class,
                    'model_paths' =>
                        array(
                            0 => 'src',
                            1 => 'vendor',
                        ),
                    );
    
    $manager->setConfiguration($config);
    $manager->setName('default');
    $serviceContainer->setConnectionManager('default', $manager);
    $serviceContainer->setDefaultDatasource('default');
}
