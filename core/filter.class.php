<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
// Variables parser (alias filter) //
class Filter {

	private $all_modes = array('home','frame','load','empty','error','redirect','mobile');
	private $all_keys = array('id','item','page','pageid','offset','category','type','newsid','pollid','newcurrency','lang','action','key','mode','frame','level','group','limit','view');
	public $page;
	public $mode;
	public $_get;
	public $_post;
	public $_all;
	public $method;
	public $action;
	//public $items = array();	
	
	function __construct() {
		// prep GET array //
		$this->_get = array();
		foreach($_GET as $key=>$val) {
			if(in_array($key, $this->all_keys)) $this->_get[$key] = $val;
		}
		
		// prep POST array //
		$this->_post = array();
		foreach($_POST as $key=>$val) {
			if(in_array($key, $this->all_keys)) $this->_post[$key] = $val;
		}
		
		// merge GET and POST fiels to one checkable array //
		$this->_all = array_merge($this->_get, $this->_post);
		
		// set which method is used for filtering 
		if(count($this->_post)>0) {
			$this->set_method('post');
		} elseif(count($this->_get)>0) { 
			$this->set_method('get');
		} else {
			$this->set_method('request');
		}
		
		$this->build();
	}
	
	private function build() {
		global $settings;
		if(isset($this->_all['page'])) 
			$this->set_page($this->_all['page']);
		if(!$this->get_page()) 
			$this->set_page($settings['main']['homepage']);
			
		if(isset($this->_all['mode'])) 
			$this->set_mode($this->_all['mode']);
			
		if(isset($this->_all['action'])) 
			$this->set_action($this->_all['action']);
			
		if(isset($this->_all['method'])) 
			$this->set_method($this->_all['method']);
	}
	
	function set_page($page) {
		$this->page = $page;
	}
	function set_mode($mode) {
		if(in_array($mode,$this->all_modes)) {
			$this->mode = $mode;
		}
	}
	/*function set_items($items) {
		if(is_array($items)) {
			$this->items = $items;
		} else {
			$this->items = null;
		}
	}*/
	public function set_method($method) {
		$this->method = $method;
	}	
	public function set_action($action) {
		$this->action = $action;
	}
	public function get_page() {
		return $this->page;
	}
	public function get_mode() {
		return $this->mode;
	}
	/*function get_items() {
		return $this->items;
	}*/
	public function get_method() {
		return $this->method;
	}
	public function get_action() {
		return $this->action;
	}
	
}
?>