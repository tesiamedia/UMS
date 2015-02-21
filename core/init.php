<?php
// preset php parameters //
ini_set('session.gc_maxlifetime',60*60*8);
ini_set('session.gc_probability',1);
ini_set('session.gc_divisor',1);
ini_set('max_execution_time', 300);

// start session //
session_start();

// turn on errors //
//error_reporting(0);
// set time zone //
date_default_timezone_set('Europe/Vilnius');
// limit script execution time //
//set_time_limit(300);

// global paths //
define(GLOB_PATH, __DIR__ . '/');
define(GLOB_ROOT, $_SERVER['DOCUMENT_ROOT']);
define(GLOB_DOMAIN, str_replace('www.', '', $_SERVER["HTTP_HOST"]));
define(GLOB_HOME, 'http://' . $_SERVER["HTTP_HOST"]);
define(GLOB_URL, 'http'. ($_SERVER["HTTPS"] == "on" ? "s":"") . '://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
define(GLOB_IP, $_SERVER['REMOTE_ADDR']);
define(ADMIN_MAIL, 'admin@tesiamedia.com');

?>