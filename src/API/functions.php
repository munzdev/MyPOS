<?php

/**
 * Original function from User comment on http://php.net/manual/de/function.str-pad.php
 * User: wes@nospamplsexample.org
 *
 * @param string $str
 * @param int $pad_len
 * @param string $pad_str
 * @param int $dir
 * @param string $encoding
 * @return string
 */
function mb_str_pad($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT, $encoding = NULL)
{
    $encoding = $encoding === NULL ? mb_internal_encoding() : $encoding;
    $padBefore = $dir === STR_PAD_BOTH || $dir === STR_PAD_LEFT;
    $padAfter = $dir === STR_PAD_BOTH || $dir === STR_PAD_RIGHT;
    $pad_len -= mb_strlen($str, $encoding);
    $targetLen = $padBefore && $padAfter ? $pad_len / 2 : $pad_len;
    $strToRepeatLen = mb_strlen($pad_str, $encoding);
    $repeatTimes = ceil($targetLen / $strToRepeatLen);
    $repeatedString = str_repeat($pad_str, max(0, $repeatTimes)); // safe if used with valid utf-8 strings
    $before = $padBefore ? mb_substr($repeatedString, 0, floor($targetLen), $encoding) : '';
    $after = $padAfter ? mb_substr($repeatedString, 0, ceil($targetLen), $encoding) : '';
    return $before . $str . $after;
}

function registerPropelConnection($a_db)
{
    $o_serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
    $o_serviceContainer->checkVersion('2.0.0-dev');
    $o_serviceContainer->setAdapterClass('default', $a_db['adapter']);
    $o_manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
    $o_manager->setConfiguration(array (
        'dsn' => $a_db['dsn'],
        'user' => $a_db['user'],
        'password' => $a_db['password'],
        'settings' => $a_db['settings'],
        'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
        'model_paths' =>
        array (
            0 => 'src',
            1 => 'vendor',
        ),
    ));
    $o_manager->setName('default');
    $o_serviceContainer->setConnectionManager('default', $o_manager);
    $o_serviceContainer->setDefaultDatasource('default');
}