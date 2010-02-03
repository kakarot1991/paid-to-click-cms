<?php
class  Advert_Models_AdvertHistory {
	const ADVERT_HISTORY_TABLE = 'advertHistory';
	private $advertHistoryID;
	private $advertID;
	private $userID;
	private $clickedDate;
	private $assignedDate;
	private $clickCount;

	function __construct($args){
		foreach($this as $key=>$val) {
			if(!empty($args[$key])) {
				$this->$key = $args[$key];
			}
		}
	}

	function save(){
		$db = Zend_Registry::get('db');
		if($this->clickedDate == null) {
			$history = array(
				'advertID' 			=> $this->advertID,
				'userID' 			=> $this->userID,
				'clickedDate' 		=> $this->clickedDate,
				'assignedDate'		=> $this->assignedDate,
			);
			$db->insert(self::ADVERT_HISTORY_TABLE, $history);
		}
		else {
			$where = ' WHERE advertID=' . $this->advertID . ' AND userID=' . $this->userID .' AND clickedDate IS NULL LIMIT 1';
			$sql ='UPDATE ' . self::ADVERT_HISTORY_TABLE . " SET clickedDate='" . $this->clickedDate . "' $where";
			$db->query($sql);
		}
	}

	static function getAdvertHistory($advertID) {
		$advertHistory = array();
		if(isset($advertID)) {
			$db = Zend_Registry::get('db');
			$rows = $db->fetchAssoc('SELECT COUNT(advertID) AS clickCount, advertHistory.* FROM ' . self::ADVERT_HISTORY_TABLE . ' advertHistory WHERE advertID = ?', $advertID);

			foreach($rows as $row) {
				$advertHistory []= new Advert_Models_AdvertHistory($row);
			}
		}
		return $advertHistory;
	}

	/**
	 * gets the given attributes name($key) value
	 * @param $key the name of the attribute to get
	 * @return [given keys value type]
	 */
	function __get($key) {
		return $this->$key;
	}

	/**
	 * @param $key the name of the attribute to set
	 * @param $value the value to assign the attribute to
	 * @return void
	 */
	function __set($key, $value) {
		$this->$key = $value;

	}
}
?>