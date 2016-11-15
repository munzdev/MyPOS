<?php
namespace API;

const DEBUG = true;

const USER_ROLE_MANAGER = 1;
const USER_ROLE_ORDER_OVERVIEW = 2;
const USER_ROLE_DISTRIBUTION = 4;
const USER_ROLE_DISTRIBUTION_PREVIEW = 8;
const USER_ROLE_ORDER_ADD = 16;
const USER_ROLE_INVOICE = 32;

const ORDER_STATUS_WAITING = 1;
const ORDER_STATUS_IN_PROGRESS = 2;
const ORDER_STATUS_FINISHED = 3;
const ORDER_AVAILABILITY_AVAILABLE = 'AVAILABLE';
const ORDER_AVAILABILITY_DELAYED = 'DELAYED';
const ORDER_AVAILABILITY_OUT_OF_ORDER = 'OUT OF ORDER';

const ORDER_DEFAULT_SIZEID = 1;

const DATE_MYSQL_TIMEFORMAT = "Y-m-d H:i:s";
const DATE_JS_TIMEFORMAT = "dd.MM.yyyy H:mm:ss";
const DATE_JS_DATEFORMAT = "dd.MM.yyyy";
const DATE_PHP_TIMEFORMAT = "d.m.Y H:i:s";

const PRINTER_CHARACTER_EURO = "\x1B\x74\x13\xD5";
const PRINTER_LOGO_DEFAULT = 1;
const PRINTER_LOGO_BIT_IMAGE = 2;
const PRINTER_LOGO_BIT_IMAGE_COLUMN = 3;