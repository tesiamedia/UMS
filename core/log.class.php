<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
// Logging class //
class Log {

	public $subject = 'ERROR';
	public $message;
	public $recipient = ADMIN_MAIL;
	protected $sender;

	// text |  send email to admin | output to screen //
	public function write($message, $send=true) {
		global $settings, $DB;		
		
		if($message) {
			Log::updateLogFile($message);
			
			if($send && $this->get_recipient()) {
				@mail($this->get_recipient(), $this->get_subject(), $message);
			}
			
			if(isset($settings['log'])) {
				if(isset($_SESSION['user'])) $user = $_SESSION['user']['username']; 
				else $user = '';
				@$DB->insert('log',array('action'=>'log', 'description'=>$message, 'ip'=>GLOB_IP, 'browser'=>browser(), 'user'=>$user));
			}
	
		}
	}

	protected function updateLogFile($txt) {
		$file = GLOB_PATH . 'log/' . date('Y_m') . '.log';
		$txt = date('Y-m-d H:i:s') . ' : ' . $txt . " \n";
		
		@file_put_contents($file, $txt, FILE_APPEND | LOCK_EX);
	}
	
	public function logOutput($limit=1000) {
		global $DB;
		return $DB->select('logs','*','','id DESC', $limit);  
	}	
	
	public function set_subject($new_subject) {
		$this->subject = $new_subject;
	}		
	public function get_subject() {
		return $this->subject;
	}	
	public function set_recipient($new_recipient) {
		$this->recipient = $new_recipient;
	}		
	public function get_recipient() {
		return $this->recipient;
	}
	
	static function output($txt) {
		$this->info($txt);
	}
	
	static function success($txt) {
		echo '<br><center><div class="info_good">'.$txt.'</div></center><br>';
	}
	
	static function failure($txt) {
		echo '<br><center><div class="info_bad">'.$txt.'</div></center><br>';
	}
	
	static function info($txt) {
		echo '<br><center><div class="info_info">'.$txt.'</div></center><br>';
	}

}

?>