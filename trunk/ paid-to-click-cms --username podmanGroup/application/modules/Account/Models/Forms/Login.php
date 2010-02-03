<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class Account_Models_Forms_Login extends Zend_Form {
	function __construct() {
		parent::__construct();
		$this->setName('Registration');
		$this->setMethod('POST');
		$this->setAction('/account/login');

		$username = new Zend_Form_Element('username');
		$username->setLabel('Username');
		$username->setRequired(true);
		$username->addValidator('NotEmpty', true);
		$username->addValidator(new Zend_Validate_StringLength(6, 10));
		$username->addValidator('Alnum', true);

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password');
		$password->setRequired(true);
		$password->addValidator('NotEmpty',true);
		$password->addValidator(new Zend_Validate_StringLength(6, 10));

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Register');

		$this->addElements(array($username, $password, $submit));
	}
}
?>