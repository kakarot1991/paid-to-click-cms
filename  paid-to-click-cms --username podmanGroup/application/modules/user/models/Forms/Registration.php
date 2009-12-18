<?php

/**
* The user registration form
*/
class User_Models_Forms_Registration extends Zend_Form {
	/**
	* Creates the registration form and it's elements.
	* Also sets the validation techniques for the fields
	*/
	function __construct() {
		parent::__construct();
		$this->setName('Registration');
		$this->setMethod('POST');
		$this->setAction('/user/register');

		$username = new Zend_Form_Element('username');
		$username->setLabel('Username');
		$username->setRequired(true);
		$username->addValidator('NotEmpty', true);
		$username->addValidator(new Zend_Validate_StringLength(6, 10), true);
		$username->addValidator('Alnum', true);
		$username->addValidator(new User_Models_Forms_Validators_IsUnique('username'));

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password');
		$password->setRequired(true);
		$password->addValidator('NotEmpty',true);
		$password->addValidator(new Zend_Validate_StringLength(6, 10));

		$gender = new Zend_Form_Element_Select('gender');
		$gender->setLabel('Gender');
		$gender->setMultiOptions(array('Male' => 'Male', 'Female' => 'Female'));

		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Email');
		$email->setRequired(true);
		$email->addValidator('NotEmpty',true);
		$email->addValidator(new User_Models_Forms_Validators_EmailAddress(),true);
		$email->addValidator(new User_Models_Forms_Validators_IsUnique('email'));

		$paymentEmail = new Zend_Form_Element_Text('paymentEmail');
		$paymentEmail->setLabel('Payment Email');
		$paymentEmail->setRequired(true);
		$paymentEmail->addValidator('NotEmpty', true);
		$paymentEmail->addValidator(new User_Models_Forms_Validators_EmailAddress(), true);
		$paymentEmail->addValidator(new User_Models_Forms_Validators_IsUnique('paymentEmail'));

		$countries = new Zend_Form_Element_Select('country');
		$countries->setMultiOptions(self::getCountries());
		$countries->setLabel('Country');
		$countries->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Register');

		$this->addElements(array($username,$password, $gender, $email,$paymentEmail,$countries,$submit));
	}

	/**
	* Reads the countries that the user is allowed to register from a text file
	* The  file is located at APPLICATION_PATH . /modules/user/models/countries.txt
	* The name of each country must be on a new line
	* @return array $countries
	*/
	public static function getCountries() {
		$lines = file(APPLICATION_PATH. '/modules/user/models/countries.txt');
		$countries = array();
		foreach($lines as $line) {
			$line = rtrim($line);
			$countries[$line] = $line;
		}
		return $countries;
	}
}