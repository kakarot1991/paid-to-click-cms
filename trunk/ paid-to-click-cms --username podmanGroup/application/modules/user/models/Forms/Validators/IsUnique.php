<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */


/**
 *
 *
 */
class User_Models_Forms_Validators_IsUnique extends Zend_Validate_Abstract {

	const MSG_INVALID = '';
	private $type;
	protected $_messageTemplates = array(
	    self::MSG_INVALID => 'This %s is already in use'
	);
	function __construct($type){
		$this->type = $type;
	}

	public function isValid($value) {
		$type = $this->type . 'IsUnique';
		return $this->$type($value);
	}

	private function emailIsUnique($value){
		$db = Zend_Registry::get('db');
		//check the users table for an email matching this one
		$stat = $db->query("SELECT email FROM " . User_Models_User::USER_TABLE . " WHERE email = ?", $value );
		$rows = $rows = $stat->fetch();
		//check if we got any results back, if so this user already exists
		if($rows) {
			$this->_messageTemplates[self::MSG_INVALID] = str_replace('%s', 'email', $this->_messageTemplates[self::MSG_INVALID]);
			$this->_error(self::MSG_INVALID);
			return FALSE;
		}
		return TRUE;
	}

	private function paymentEmailIsUnique($value){
		$db = Zend_Registry::get('db');
		//check the users table for an email matching this one
		$stat = $db->query("SELECT paymentEmail FROM " . User_Models_User::USER_TABLE . " WHERE email = ? OR paymentEmail = ?", array($value, $value) );
		$rows = $rows = $stat->fetch();
		//check if we got any results back, if so this user already exists
		if($rows) {
			$this->_messageTemplates[self::MSG_INVALID] = str_replace('%s', 'payment email', $this->_messageTemplates[self::MSG_INVALID]);
			$this->_error(self::MSG_INVALID);
			return FALSE;
		}
		return TRUE;
	}

	private function usernameIsUnique($value){
		$db = Zend_Registry::get('db');
		//check the users table for an email matching this one
		$stat = $db->query("SELECT username FROM " . User_Models_User::USER_TABLE . " WHERE username = ?", $value );
		$rows = $rows = $stat->fetch();
		//check if we got any results back, if so this user already exists
		if($rows) {
			$this->_messageTemplates[self::MSG_INVALID] = str_replace('%s', 'username', $this->_messageTemplates[self::MSG_INVALID]);
			$this->_error(self::MSG_INVALID);
			return FALSE;
		}
		return TRUE;
	}
}
?>