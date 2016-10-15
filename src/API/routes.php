<?php
// Routes

foreach (glob(__DIR__ . "/Routes/*.php") as $str_filename)
{
    require $str_filename;
}