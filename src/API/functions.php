<?php

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
    if ($encoding === null) {
        $encoding = mb_internal_encoding();
    }

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
