<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved               */
/***********************************************************************************************/

class Content {

	protected $page;
	protected $mode;
	public $page_data;
	public $page_id;
	public $path_theme;

	function __construct($params) {
		global $settings;
		
		$this->set_path_theme(GLOB_PATH.'themes/'.$settings['main']['theme'].'/');
		$this->page = $params->page;
		$this->mode = $params->mode;
		$this->page_id = $this->get_page_id($this->page);
		if($this->page_id) {
			$this->page_data = $this->loadPageData($this->page_id);
		}
	}
	
	public function set_path_theme($path_theme) {
		$this->path_theme = $path_theme;
	
	}
	public function get_path_theme() {
		return $this->path_theme;
	}
	
	public function view() {
		global $DB, $settings;
		
		// mode switcher //
		if($this->mode) $add = '_' . $this->mode; else $add = '';

		// meta header //
		//$var .= Template::header();
		include_once($this->path_theme."header".$add.".php");
		//$var .= Template::header();

		echo '</head>';
		
		// visible content  //
		if($this->page_data) {
			include($this->path_theme."content".$add.".php");
			//$var .= Template::content();		
		} else {
			include($this->path_theme."404.php");
		}
		
		// hidden content //
		include_once($this->path_theme."footer".$add.".php");
		//$var .= Template::footer();
	}
	
	protected function loadPageData($id) {
		global $DB;
		
		$pages = $DB->getRow('pages','*',array('page'=>$id));
		return $pages;
	}
	
	// find page id by alias given //
	protected function get_page_id($page) {
		global $DB;
		
		$page_id = $DB->getOne('alias','page',array('alias'=>$page));
		if($page_id) 
			return $page_id;
		else 
			return 0;
	}
	
	protected function printModules() {
		$modules = unserialize($this->page_data['modules']);
		if($modules && is_array($modules)) {
			foreach($modules as $module=>$module_param) {
				include_once(GLOB_PATH.'modules/'.$module.'/view.php'); 
			}
		}
	}

	
	/*public function framework($id, $cols, $rows, $width, $height, $tabled=true) {		
		$this->id = $id;
		$this->cols = $cols;
		$this->rows = $rows;
		$this->width = $width;
		$this->height = $height;
		$this->tabled = $tabled;	
		$this->structure = array();
	}

	private function print_header() {
		$result = '<div id="'.$this->id.'" width="'.$this->width.'"  height="'.$this->height.'">';
		if($this->tabled) $result .= '<table cellpadding="0" cellspacing="0" width="'.$this->width.'">';		
		return $result;
	}
	
	private function print_footer() {
		if($this->tabled) $result = '</table>';
		$result .= '</div><div style="clear:both;"> </div>';		
		return $result;
	}
	
	private function print_content() {
		$result = '';
		for($i=1;$i<=$this->rows;$i++){
			if($this->tabled) $result .= '<tr>';
			for($y=1;$y<=$this->cols;$y++){
				if($this->tabled) $result .= '<td>';
				if($this->structure[$i][$y]) {
					foreach($this->structure[$i][$y] as $struct) {
						$result .= $struct[0] . $struct[1];
					}
				}
				if($this->tabled) $result .= '</td>';
			}
			if($this->tabled) $result .= '</tr>';
		}	
		return $result;
	}	

	public function add_block($row, $col, $title, $str, $class=false, $colspan=false) {
		if($str) $this->structure[$row][$col][] = array($title, $str, $class, $colspan);
	}	

	public function print_all() {
		$result = $this->print_header();
		$result .= $this->print_content();
		$result .= $this->print_footer();	
		return $result;
	}*/
	
}

?>	