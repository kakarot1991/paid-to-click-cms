<?php
abstract class Accounts_Account extends Accounts_BasicAccount {
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