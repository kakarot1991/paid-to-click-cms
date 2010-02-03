<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Currency
 *
 * @author internet
 */
class Forms_Validators_Currency extends Zend_Validate_Abstract {
	const MSG_INVALID = '';

	protected $_messageTemplates = array(
	    self::MSG_INVALID => "Expected format: 00.00",
	);

	public function isValid($value) {
		if (!preg_match('/^(\d{2,})\.\d{2}$/', $value)) {
			$this->_error(self::MSG_INVALID);
			return FALSE;
		}
		return TRUE;
	}
}
?>
