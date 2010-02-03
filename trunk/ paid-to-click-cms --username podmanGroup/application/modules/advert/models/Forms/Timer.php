<?php
class Advert_Models_Forms_Timer extends Zend_Form {
	/**
	 * Creates the registration form and it's elements.
	 * Also sets the validation techniques for the fields
	 */
	function __construct() {
		parent::__construct();
		$this->setName('Timer');
		$this->setMethod('POST');
		$this->setAction('/advert/admin');

		$timer = new Zend_Form_Element_Text('interval');
		$timer->setLabel('Seconds to count down from');
		$timer->setRequired(true);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Submit');

		$this->addElements(array($timer, $submit));
	}

}