<?php
abstract class Account_Models_Account extends Account_Models_BasicAccount {
	protected $paymentEmail;
	protected $country;
	protected $IP;
	protected $banReason;

	function __constructor($args) {
		foreach($args as $key=>$value) {
			$this->$key = $value;
		}
	}

	function sendActivationEmail() {

	}

	function activate() {

	}

	function remove() {

	}

}
?>