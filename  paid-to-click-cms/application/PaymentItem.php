<?php
class PaymentItem {
	private $name;//the name of the item
	private $price;//the price of the item
	private $description;//description of the item
	private $callback;//a method to callback when the transaction has been successful
	
	function __construct($name, $price, $desc, $callback) {
		$this->name = $name;
		$this->price = $price;
		$this->description = $description;
		$this->callback = $callback;
	}
	
	function __get($key) {
		return $this->$key;
	}
	
	function __set($key, $value) {
		$this->$key = $value;
	}
}
?>