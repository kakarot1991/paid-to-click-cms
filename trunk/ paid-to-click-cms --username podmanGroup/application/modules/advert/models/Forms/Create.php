<?php
class Advert_Models_Forms_Create extends Zend_Form {
	/**
	 * Creates the registration form and it's elements.
	 * Also sets the validation techniques for the fields
	 */
	function __construct() {
		parent::__construct();
		$this->setName('advert');
		$this->setMethod('POST');
		$this->setAction('/advert/create');

		$title = new Zend_Form_Element_Text('title');
		$title->setLabel('Title');
		$title->setRequired(true);
		$title->addValidator('NotEmpty', true);

		$url = new Zend_Form_Element_Text('url');
		$url->setLabel('Url');
		$url->setRequired(true);
		$url->addValidator('NotEmpty', true);
		$url->addValidator(new Advert_Models_Forms_Validators_IsUrl(), true);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Create');

		$this->addElements(array($title, $url, $submit));
	}

}