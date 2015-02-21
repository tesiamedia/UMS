<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/
// Block generator for content  //
class Block {

	public $id;
	public $width;
	public $height;
	public $style;
	public $template;

	public function create($var, $id=false) {
		
		
	}

	public function hide($id) {
		
	}
	
	public function show($id) {
		
	}
	
	public function set_id($id) {
		$this->id = $id;
	}
	public function set_width($width) {
		$this->width = $width;
	}
	public function set_height($height) {
		$this->height = $height;
	}
	public function set_style($style) {
		$this->style = $style;
	}
	public function set_template($template) {
		$this->template = $template;
	}		
	protected function get_id() {
		return $this->id;
	}
	protected function get_width() {
		return $this->width;
	}
	protected function get_height() {
		return $this->height;
	}
	protected function get_style() {
		return $this->style;
	}
	protected function get_template() {
		return $this->template;
	}

}
?>