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
class Fund_Models_Forms_Validators_Currency extends Forms_Validators_Currency {
	const MSG_FORMAT = 'MSG_FORMAT';
        const MSG_MIN_ERROR = 'MSG_MIN_ERROR';
        const MSG_MAX_ERROR = 'MSG_MAX_ERROR';
	protected $_messageTemplates = array(
	    self::MSG_INVALID   => "Expected format: 00.00",
	    self::MSG_MIN_ERROR => "Amount cannot be less than %d",
	    self::MSG_MAX_ERROR => "Amount cannot be greater than %d",
	);

	public function isValid($value) {
                $min = Site::getResource('fund_min');
                $max = Site::getResource('fund_max');
                $this->_messageTemplates[self::MSG_MIN_ERROR] =
                        str_replace('%d', $min, $this->_messageTemplates[self::MSG_MIN_ERROR]);

                $this->_messageTemplates[self::MSG_MAX_ERROR] =
                        str_replace('%d', $max, $this->_messageTemplates[self::MSG_MAX_ERROR]);
		if (!preg_match('/^(\d{2,})\.\d{2}$/', $value)) {
                    $this->_error(self::MSG_INVALID);
                    return FALSE;
		}
                else if($value < $min) {
                    $this->_error(self::MSG_MIN_ERROR);
                    return FALSE;
                }
                else if($value > $max) {
                    $this->_error(self::MSG_MAX_ERROR);
                    return FALSE;
                }
		return TRUE;
	}
}
?>
