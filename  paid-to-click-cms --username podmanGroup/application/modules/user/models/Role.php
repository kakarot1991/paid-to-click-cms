<?php
class User_Models_Role {
	const ROLE_TABLE = 'role';
	private $name;
	private $ppc;
	private $cost;
	private $description;
	private $roleID;
	function __construct($args) {
		foreach($this as $key=>$val) {
			if(!empty($args[$key])) {
				$this->$key = $args[$key];
			}
		}
	}

	function save() {
		$db = Zend_Registry::get('db');
		$data = array(
			'ppc' 			=> $this->ppc,
			'name' 			=> $this->name,
			'descriptions' 	=> $this->description,
			'cost' 			=> $this->cost
		);
		$sql = '';
		if(!isset($this->roleID)) {
			$db->insert(self::ROLE_TABLE, $data);
		}
		else {
			$db->update(self::ROLE_TABLE, $data, 'roleID=' . $this->roleID);
		}

	}

	static function getRoleById($roleID) {
		$role = null;
		$db = Zend_Registry::get('db');
		$rows = $db->fetchAll('SELECT * FROM ' . self::ROLE_TABLE . ' WHERE roleID = ' . $roleID);

		if(count($rows) > 0) {
			$role = new User_Models_Role($rows[0]);
		}
		return $role;
	}

	function __set($key, $value) {
		$this->$key = $value;
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