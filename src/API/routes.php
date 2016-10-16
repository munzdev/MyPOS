<?php
// Routes

foreach (glob(__DIR__ . "/Routes/*.php", GLOB_NOSORT) as $str_filename)
{
    require $str_filename;
}