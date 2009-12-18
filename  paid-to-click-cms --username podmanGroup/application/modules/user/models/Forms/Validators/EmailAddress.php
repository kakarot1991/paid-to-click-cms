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
class User_Models_Forms_Validators_EmailAddress extends Zend_Validate_Abstract {

	const MSG_INVALID = '';

	protected $_messageTemplates = array(
	    self::MSG_INVALID => "This is not a valid email address",
	);

	public function isValid($value) {
		if (!preg_match('/^[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/', $value)) {
			$this->_error(self::MSG_INVALID);
			return FALSE;
		}
		return TRUE;
	}
}
?>