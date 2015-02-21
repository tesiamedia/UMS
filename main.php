<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/

// check if install folder is still active //
if(file_exists("install/")) { 
	header("Location: /install/"); 
}

// preset php and session settings, globals, functions //
include_once('core/init.php');
include_once('core/functions.inc.php');

// important classes //
require_once('core/log.class.php');
require_once('core/user.class.php');
require_once('core/session.class.php');
require_once('core/filter.class.php');
require_once('core/content.class.php');
require_once('core/module.class.php');
require_once('core/action.class.php');
require_once('core/block.class.php');
require_once('core/login.class.php');

// initiate database connection //
require_once('core/db/config.php');
require_once('core/db/db.class.php');
require_once('core/db/'.$database_type.'.php');

$DBClass = $database_type . 'Class';
$DB = new $DBClass($database_host, $database_user, $database_pass, $database_scheme, $database_type);
$DB->connect();

// prepare global and plugins settings //
$settings = mass_unserialize($DB->getRow('settings','*'));

// declare visitor session //
$session = new Session;
$session->set_IP($_SERVER['REMOTE_ADDR']);
$session->isBanned();
$session->isLogged();

// get and post parser //
$filter = new Filter;
$page = $filter->get_page();
$action = $filter->get_action();
$mode = $filter->get_mode();
//$items = $filter->get_items();

// special actions execution //
$run = new Action($action);

// visual content printer //
$content = new Content($filter);
$content->view();

$DB->close(); 
?>