<?php  
session_start();

if(isset($_SESSION['user']) && is_numeric($_GET['cid'])) {
	$_SESSION['cid'] = $_GET['cid'];
} 

exit;

?>