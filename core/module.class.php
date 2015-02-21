<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
// Visitor session control //
class Module {

	protected $module;
	protected $module_param;
	protected $_get;
	protected $_post;
	protected $_all;
	protected $action;	
	protected $item;	
	
	function __construct() {
		global $module_param;
		
		$this->module_param = $module_param;
		// prep GET array //
		$this->_get = array();
		foreach($_GET as $key=>$val) {
			$this->_get[$key] = $val;
		}
		
		// prep POST array //
		$this->_post = array();
		foreach($_POST as $key=>$val) {
			$this->_post[$key] = $val;
		}
		
		// merge GET and POST fiels to one checkable array //
		$this->_all = array_merge($this->_get, $this->_post);
	}
	
	public function set_module($module) {
		$this->module = $module;
	}
	
	public function get_module() {
		return $this->module;
	}

}
?>