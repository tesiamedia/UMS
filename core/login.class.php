<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
// User login class //
class Login {

	public $username;
	public $level;
	public $path_theme;
	
	function __construct() {
		$this->path_theme = 'themes/'.SET_THEME.'/';
	}

	public function check() {
		global $settings, $DB;		
		
		if(isset($_POST['login'])) {
			$username = addslashes(htmlspecialchars($_POST['user'])); 	// NOTE: username is email of user
			$password = md5($_POST['pass']);
			if($this->captcha()) {	
		
				if($username) {
					
					$user = $DB->getRow('users','*',array('email'=>$username, 'password'=>$password));
					if($user) {
						$_SESSION['user'] = $user;
						$_SESSION['user']['username'] = $username;
						unset($_SESSION['user']['password']); // remove password from memory for security
						
						$this->username = $username;
						$this->level = $_SESSION['user']['level'];
						
						if(isset($settings['log'])) {
							@$DB->insert('log',array('action'=>'login', 'ip'=>GLOB_IP, 'browser'=>browser(), 'user'=>$_SESSION['user']['id']));
						}
						
						Log::success('Logg Inn vellykket');
						redirect(GLOB_URL);
						
					// if login failed then count the failed attempts //
					} else {
						if(isset($_SESSION['login_repeat'])) 
							$_SESSION['login_repeat'] += 1;
						else 
							$_SESSION['login_repeat'] = 1;
							
						Log::failure('Feil påloggingsinformasjon');
						$this->printForm();
					}
					
				} else {
					Log::failure('Feil påloggingsinformasjon');
					$this->printForm();
				}
				
			} else {
				Log::failure('Feil antispam-kode');
				$this->printForm();
			}
	
		} else {
			if($settings['private']) {
				Log::failure('Innlogging er obligatorisk');
				$this->printForm();
			}
		}
		
	}
	
	public function printForm() {
		include($this->path_theme."header_public.php");
		include($this->path_theme."login.php");
		include($this->path_theme."footer_public.php");
	}
	
	public function makeCheck() {
		$this->check();
	}
	
	public function logOut() {
		global $DB, $settings;
		
		if(isset($settings['log'])) {
			@$DB->insert('log',array('action'=>'logout', 'ip'=>GLOB_IP, 'browser'=>browser(), 'user'=>$_SESSION['user']['id']));
		}
		unset($_SESSION['user']);
		session_destroy();
		redirect(GLOB_HOME);
		echo "<script>top.location.href = '".GLOB_HOME."';</script>";
		exit;
	}

	
	public function captcha() {
		if(isset($_SESSION['login_repeat'])) {
			if(strtolower($_POST['login_captcha']) == $_SESSION['login_captcha']) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

}

?>