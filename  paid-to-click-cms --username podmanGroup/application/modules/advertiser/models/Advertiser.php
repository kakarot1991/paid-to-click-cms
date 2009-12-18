<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class  Advertiser_Models_Advertiser extends Accounts_Account {
	const ADVERTISER_TABLE = 'users';

	/**
	 * The user couldn't be logged in because either username or password was incorrect
	 * Or The user doesn't exist in the database
	 * @name INVALID_LOGIN
	 */

	const INVALID_LOGIN = 'Incorrect username and/or password';

	/**
	 * The user has been banned or the IP address the user is coming from is in the ban list
	 * @name BANNED
	 */
	const BANNED = 'You have been banned';

	/**
	 * The operation was a success
	 * @name BANNED
	 */
	const SUCCESS = 'SUCCESS';

	/**
	 * The user cannot be registered because some of fields are already in use
	 * These are fields such as username, email and paymentEmail
	 * @name FIELDS_ALREADY_IN_USE
	 */
	const FIELDS_ALREADY_IN_USE = '%s is already in use';

	private $origattrs = array();

	/**
	 * Creates a new advertiser using the keys and values from the array in its parameters
	 * @param array $args
	 */
	function __construct($args) {
		foreach($this as $key=>$val) {
			if(($key != 'origattrs') and ($key != 'errors')) {
				$this->origattrs[]= $key;
				$this->$key = (isset($args[$key]) ? $args[$key] : null);
			}
		}
	}
	/**
	 * Attempts to register the current user.
	 * @return SUCCESS
	 */
	function register() {
		$db = Zend_Registry::get('db');

		//create the new user
		$db->insert(self::ADVERTISER_TABLE, $this->_getPairs());
		return self::SUCCESS;
	}

	/**
	 * Updates the users stored details
	 * @return boolean
	 */
	function update($args) {
		//check if the user is already in the system
		if($this->accountID != NULL) {
			foreach($args as $key=>$value) {
				$this->$key = $value;
			}
			$db = Zend_Registry::get('db');
			$data = $this->_getPairs();
			$db->update(self::ADVERTISER_TABLE, $data, 'accountID = ' . $this->accountID);
			return true;
		}
		return false;
	}

	/**
	 * Attempts to login in the advertiser
	 * If the visitor cannot be logged in due to no exist username or invalid username and password
	 * then an INVALID_LOGIN is returned otherwise a new advertiser is returned
	 * If the advertiser exist but is banned the a BANNED is returned
	 * @param string $username
	 * @param string $password
	 * @return INVALID_LOGIN|BANNED|Advertiser_Models_Advertiser
	 */
	static function login($username, $password) {
		$db = Zend_Registry::get('db');
		$stat = $db->query("SELECT * FROM " . self::ADVERTISER_TABLE . " WHERE username = ? AND PASSWORD = ? AND status = ?", array($username,$password, 'advertiser'));
		$row = $stat->fetch();

		//check for wrong username and password
		if(!$row) {
			return self::INVALID_LOGIN;
		}

		//check if the user has been self::BANNED
		if($row['status'] == 'self::BANNED') {
			$this->errors[]= $row['banReason'];
			return self::BANNED;
		}

		//create and return the new user
		return new Advertiser_Models_Advertiser($row);
	}

	/**
	 * Gets the names of attributes along with their value in a key-value array
	 * @access private
	 * @return array
	 */
	private function _getPairs() {
		$pairs = array();
		foreach($this->origattrs as $key) {
			$pairs[$key] = $this->$key;
		}
		return $pairs;
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

	function render(){
		return array(
			'Username' 		=> $this->username,
			'Country' 		=> $this->country,
			'Email' 		=> $this->email,
			'Payment Email' => $this->paymentEmail,
		);
	}
	function resetPass() {

	}
}
?>