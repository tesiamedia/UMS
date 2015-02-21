<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/

class Action {

	private $action;

	function __construct($action) {
		$this->action = $action;
		$this->run();
	}
	
	private function run() {
		global $DB;
		
		$result = $DB->getOne('actions','action',array('action'=>$this->action));
		if($result) {
			include('load/'.$this->action.'.php');
		}
	}
	
	public function restart() {
		$this->run();
	}

}

?>	