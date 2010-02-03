<?php
class  Advert_Models_Timer {
	private $interval;
	/*private $start;
	private $end;
	private $advertID;*/
	function __construct(){
		$db = Zend_Registry::get('db');
		$result = $db->query("SELECT value FROM variables WHERE name='advert_timer'");
		$row = $result->fetch();
		$this->interval = $row['value'];
	}

	function __get($key){
		return $this->$key;
	}

	function __set($key, $value){
		$this->$key = $value;
		if($key == 'interval') {
			$db = Zend_Registry::get('db');
			$db->update('variables', array('value' => $value), "name='advert_timer'");
		}
	}
}
?>