<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
// Visitor session control //
class Session {
	public $ip;

	public function isBanned() {
		global $DB;

		if($DB->getOne('banned', 'ip', array('ip'=>$this->get_IP() ))) { 
			print("YOU ARE BANNED!"); 
			die(); 
		}

	}
	
	public function isLogged() {
		global $settings;
		
		if(!isset($_SESSION['user'])){  
			define(LEVEL, 0);			
			if($settings['private']) {
				$login = new Login;
				$login->makeCheck();
				exit;
			}
		} else {
			define(LEVEL, $_SESSION['user']['level']);
		}
	}
	
	public function set_IP($new_IP) {
		$this->ip = $new_IP;
	}
	
	public function get_IP() {
		return $this->ip;
	}

}
?>