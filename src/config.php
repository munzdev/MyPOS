<?php

$a_config = array();

//-- BEGIN CONFIG PART !!! BEGIN CHANGE AFTER THIS LINE !! //
$a_config['DB']['Typ'] = 'mysql';
$a_config['DB']['Host'] = 'localhost';
$a_config['DB']['User'] = 'root';
$a_config['DB']['Password'] = '';
$a_config['DB']['Database'] = 'mypos';
$a_config['DB']['Persistent'] = true;

$a_config['Auth']['RememberMe_PrivateKey'] = 'MFswDQYJKoZIhvcNAQEBBQADSgAwRwJAfmBSwS0WmfKNW1Dq2N4MJ4gYDApG6lW19QhvDp2g80ajw74D2Xijm4rIuxaJPJ64GazdNWUHuc+1CL5eEkBopwIDAQAB';

$a_config['Organisation']['Name'] = "";
$a_config['Organisation']['Invoice']['Header'] = "HEADER TOP LINE\nSECOND LINE\nTHIRD LINE";
$a_config['Organisation']['Invoice']['Logo']['Use'] = false;
$a_config['Organisation']['Invoice']['Logo']['Path'] = "";
$a_config['Organisation']['Invoice']['Logo']['Type'] = MyPOS\PRINTER_LOGO_DEFAULT;

$a_config['App']['Distribution']['AmountOrdersToPreShow'] = 2;