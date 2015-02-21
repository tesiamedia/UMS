<?php  

if(isset($_SESSION['user'])) {
	Login::logOut();
}

?>