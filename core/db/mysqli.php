<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved 
/***********************************************************************************************/
// MYSQLI specific functions //
class mysqliClass extends DB {

	public function connect() { 
		$this->DB = new mysqli($this->host, $this->user, base64_decode($this->pass), $this->scheme);
		if($this->DB->connect_errno > 0){
			Log::write('Database connection failed ('.$this->DB->connect_error.')',false);
			return false;
		} else {
			$this->DB->query("SET NAMES {$this->charset}");
			$this->DB->query("SET CHARACTER SET {$this->charset}");
		}
	}
	
	public function close() { 
		$this->DB->close();
	}
	
	public function esc($var) {	
		return $this->DB->real_escape_string($var);
	}

	public function sql($sql) {
		return $this->DB->query($sql);
	}

	// select may differ between db types, so needed to redeclare select function //
	/*public function select($table, $fields, $where=false, $order=false, $limit=false, $group=false) {
		if($order) $order = " ORDER BY ".$order." ";
		if($group) $group = " GROUP BY ".$group." ";
		if($limit) $limit = " LIMIT ".$limit." ";
		if($where) $where = " WHERE ". $this->wherePrepare($where) ." ";
		
		$result = $this->sql("SELECT ". $this->fieldsPrepare($fields) ." FROM {$this->scheme}.".$table . $where . $group . $order . $limit );
		
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$rows[] = $row;
		}
		
		if($rows) {
			return $rows;
		} else {
			return false;
		}
	}	
	*/
	
}
?>