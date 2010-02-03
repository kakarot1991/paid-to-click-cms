<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class Advert_Models_Forms_Validators_IsUrl extends Zend_Validate_Abstract {

	const MSG_INVALID = '';
	protected $_messageTemplates = array(
	    self::MSG_INVALID => 'This is not a valid URL'
	);

	public function isValid($value) {
		if(preg_match('/http:\/\/.+/',$value)) {
			return TRUE;
		}
		$this->_error(self::MSG_INVALID);
		return FALSE;
	}
}
?>