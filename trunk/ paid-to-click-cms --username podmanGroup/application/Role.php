<?php
define('ROLE_TABLE', 'role');
class Role {

	private $name;
	private $ppc;
	private $cost;
	private $description;
	private $roleID;
	function __construct($args) {
		foreach($args as $key=>$value) {
			$this->$key = $value;
		}
	}
	
	function save() {
		$sql = '';
		if(!isset($this->roleID)) {
			$sql = 'INSERT INTO ' . ROLE_TABLE . " (name,ppc,cost,description) VALUES('" . $this->name . "'," . $this->ppc . "," . $this->cost . ",'" . $this->description . "')";			
		}
		else {
			$sql = 'UPDATE ' . ROLE_TABLE . " SET name = '" . $this->name . "', ppc = " . $this->ppc . ", cost = " . $this->cost . ", description = '" . $this->description . "' WHERE roleID = " . $this->roleID;
		}
		mysql_query($sql);
		print "query: $sql <br>";
		print "query error: " . mysql_error() . "<br>";
		
	}
	
	static function getRoleBy($args) {
		$sql = 'SELECT * FROM ' . ROLE_TABLE;
		if(count($args) > 0) {
			$sql .= ' WHERE ';

			foreach($args as $key=>$val) {
				$sql .= "$key=$val AND ";
			}
			$sql = rtrim($sql, 'AND ');			
		}
		
		$res = mysql_query($sql);
		$roles = array();
		while($row = mysql_fetch_assoc($res)) {
			$roles[]= new Role($row);
		}
		print "query: $sql <br>";
		print "query error: " . mysql_error() . "<br>";		
		return $roles;
	}
	
	function __set($key, $value) {
		switch($key) {
			case "roleID":
				//shouldn't set these variables from outside
			break;
			default:
				$this->$key = $value;
			break;
		}
		
	}

	//getters and setters
	/**
	* gets the given attributes name($key) value
	* @param $key the name of the attribute to get
	* @return [given keys value type]
	*/
	function __get($key) {
		return $this->$key;
	}	
	
}
?>