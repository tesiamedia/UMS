<?php
/***********************************************************************************************/
/* TESIA CMS 5 | TESIA DYNAMICS [tm] | http://www.tesia.lt | All rights reserved 
/***********************************************************************************************/
// Database connection and data manipulation class //
class DB {

	protected $DB;
	protected $user;
	protected $pass;
	protected $host;
	protected $scheme;
	protected $type;
	protected $flags;
	public $charset = 'utf8';

	function __construct($host, $user, $pass, $scheme, $type, $charset=false) {
		
		$this->set_host($host);
		$this->set_user($user);
		$this->set_pass($pass);		
		$this->set_scheme($scheme);
		$this->set_type($type);
		if($charset != false) {
			$this->set_charset($charset);
		}		
	}
	
	public function connect() { 
		// MySQLi
		if($this->type == 'mysqli') {
			$this->DB = new mysqli($this->host, $this->user, base64_decode($this->pass), $this->scheme);
			$this->sql("SET NAMES {$this->charset}");
			$this->sql("SET CHARACTER SET {$this->charset}");
			
		// MySQL
		} elseif($type == 'mysql') {
			$this->DB = mysql_connect($this->host, $this->user, base64_decode($this->pass));
			if(!$this->DB) {
				Log::write('Failed '.$type.' connection.',false);
				return false;
			} else {
				mysql_select_db($this->scheme, $this->DB);
				return true;
			}
		// Oracle 
		} elseif($this->type == 'oracle') {
			/*
			$conn = oci_connect('hr', 'welcome', 'localhost/XE');
			if (!$conn) {
				$e = oci_error();
				trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}

			$stid = oci_parse($conn, 'SELECT * FROM employees');
			oci_execute($stid);
			*/
		// PostgreSQL
		} elseif($this->type == 'postgresql') {
		
		// [Error]
		} else {
			Log::write('Wrong database type ('.$type.')',false);
			return false;
		}
	}
	
	public function close() { 
		// MySQLi
		if($this->type == 'mysqli') {
			$this->DB->close();
			
		// MySQL
		//} elseif($this->type == 'mysql') {
		//	mysql_close($this->DB);
			
		// Oracle 
		} elseif($this->type == 'oracle') {
		
		// PostgreSQL
		} elseif($this->type == 'postgresql') {
		
		}
	}
	
	function set_host($var) {
		$this->host = $var;
	}	
	function set_user($var) {
		$this->user = $var;
	}	
	function set_pass($var) {
		$this->pass = $var;
	}	
	function set_scheme($var) {
		$this->scheme = $var;
	}	
	function set_type($var) {
		$this->type = $var;
	}	
	function set_charset($var) {
		$this->charset = $var;
	}

	public function esc($var) {	
		if($this->type == 'mysqli') {
			return $this->DB->real_escape_string($var);
		}
	}

	protected function valueCheck($var) {
		// if the value is numeric, then leave as it is, otherwise use antihack magic depending on system used 
		if(is_numeric($var)) {
			return $var;
		} else {
			return "'". $this->esc($var) . "'";			
		}
	}

	protected function fieldCheck($var) {
		if(is_string($var)) {
			return preg_replace('/[^A-Za-z_)(*]/','',$var);
		} else {
			Log::write('Critical error: Wrong field in SQL syntax ('.$var.')',true);
			exit;
		}
	}

	protected function wherePrepare($var) {
		if(is_array($var)) {
			foreach($var as $key=>$val) {
				if(isset($where)) $where .= ' AND ';
				$where  .= $this->fieldCheck($key) . "=" . $this->valueCheck($val) . " ";
			}
			return $where;
		} else {
			return $var;
		}
	}
		
	protected function fieldsPrepare($var) {
		if(is_array($var)) {
			foreach($var as $key=>$val) {
				if($key) {
					if(isset($fields)) $fields .= ', ';
					$fields  .= $this->fieldCheck($key) . "=" . $this->valueCheck($val) . "";
				}
			}
			return $fields;
		} else {
			return $var;
		}
	}
	
	// db-type dependant sql query method //
	public function sql($sql) {
		if($this->type == 'mysqli') {
			return $this->DB->query($sql);
		}
	}
	
	// unified insert method //
	public function insert($table, $vals, $no_duplicates=false) {
		if(is_array($vals)) {
			foreach($vals as $key=>$val) {
				if(isset($fields)) $fields .= ',';
				$fields  .= $this->fieldCheck($key);
				if(isset($values)) $values .= ',';
				$values .= $this->valueCheck($val);
			}
			
			if($no_duplicates && (count($this->select($table, $fields, $vals))>0)) {
				Log::failure('Such row already exist in the database. Duplicates are not allowed.');
			} else {
				$result = $this->sql("INSERT INTO {$this->scheme}.".$table." (".$fields.") VALUES (".$values.")");
			}
		} else {
			Log::write('Wrong field in SQL syntax ('.$var.')',true);
		}
		return $result;
	}
	
	// unified update method //
	public function update($table, $vals, $where) {
		if(is_array($vals)) {
			$result = $this->sql("UPDATE {$this->scheme}.".$table." SET ".$this->fieldsPrepare($vals)."  WHERE ". $this->wherePrepare($where) );
		}
		return $result;
	}
		
	// when executed method checks for such row(s) in DB, if found only updates data, if not insert new //
	public function replace($table, $vals, $where) {		
		// prepare array of key/value to key only
		$fields = '';
		foreach($vals as $key=>$f) {
			if($fields) $fields .= ',';
			$fields .= $key;
		}
		
		if(count($this->select($table, $fields, $where))>0) {
			$result = $this->update($table, $vals, $where);
		} else {
			$result = $this->insert($table, $vals);
		}
		return $result;
	}
	
	// complex non-standard sql queries //
	public function complex($sql) {
		$result = $this->sql($sql);
		if($result) {
			return $result->fetch_array(MYSQLI_ASSOC);
		} else {
			return false;
		}
	}
	
	public function delete($table, $where) {
		$result = $this->sql("DELETE FROM {$this->scheme}.".$table." WHERE ". $this->wherePrepare($where));
		return $result;
	}
	
	public function select($table, $fields, $where=false, $order=false, $limit=false, $group=false) {
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

	public function getOne($table, $field, $where=false, $order=false, $group=false) {
		if(is_string($field)) {
			$result = $this->select($table, $field, $where, $order, 1, $group);
			if($result) {
				return $result[0][$field];
			} else {
				return null;
			}
		}
	}
	
	public function getRow($table, $field, $where=false, $order=false, $group=false) {
		$result = $this->select($table, $field, $where, $order, 1, $group);
		if($result) {
			return $result[0];
		} else {
			return null;
		}		
	}
	
}
?>