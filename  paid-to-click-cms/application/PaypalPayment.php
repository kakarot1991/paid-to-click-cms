<?php
require_once('CurlWrapper.php');
class PaypalPayment {
	private $APIUsername;
	private $APIPassword;
	private $APISignature;
	private $curl;
	//private $url;//was "https://api.sandbox.paypal.com/nvp"; before
	private $massPayUrl = 'https://api.sandbox.paypal.com/nvp';
	private $IPNUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; 
	private $PP_API_VERSION = '52.0';
	private $currencyCode = 'USD';
	
	//constructor
	public function __construct($u,$p,$s) {
		$this->curl = new CurlWrapper();
		$this->APIUsername  = $u;
		$this->APIPassword  = $p;
		$this->APISignature = $s;
	}
	
	public function __get($name) {
		return $this->$name;
	}
	
	public function __set($name,$value) {
		$this->$name = $value;
	}
	//pay all given users at the same time
	//expects an array of user objects
	public function massPay($users) {
		$emails = '';
		$amounts = '';
		$fields = 'VERSION=' . $this->PP_API_VERSION . '&USER=' . $this->APIUsername . '&PWD=' . $this->APIPassword . '&Signature=' . $this->APISignature . '&METHOD=MassPay&CURRENCYCODE=' . $this->currencyCode . '&RECEIVERTYPE=EmailAddress&';
		for($i= 0; $i < sizeof($users); $i++) {
			if($i > 0 ) {
				$emails .= '&';
				$amounts .= '&';
			}
			$emails .= "L_EMAIL$i=" . $users[$i]->email;
			$amounts .= "L_AMT$i=" .  $users[$i]->balance;
		}
		$fields .= "$emails&$amounts";
		$this->curl->setDefaults($this->massPayUrl, $fields);
		print $this->curl->curl_exec();
	}
	
	public function IPN() {
		//send all fields back to papypal in exact order for confirmation and wait for VERFIED response
		foreach($_POST as $k=>$v) {
			if($fields != NULL) {
				$fields .= '&';
			}
			$fields .= "$k=$v";
		}
		$fields = "cmd=_notify-validate&" . $fields;		
		$this->curl->setDefaults($this->IPNUrl, $fields);
		$this->curl->curl_exec();
		
		//make sure the response is VERFIED and that items price and currency are correct
		if($ret == 'VERIFIED' && $_POST['payment_status'] == 'Completed' && isUnique($_POST['txn_id']) && 
			$_POST['receiver_email'] == 'seller_1257453128_biz@hotmail.co.uk' && $_POST['mc_gross'] == '1.00' && 
			$_POST['mc_currency'] == 'USD' & $_POST['item_name'] == 'ITEM NAME XXX' && $_POST['item_name'] == 'ITEM NAME XXX' ) {
			///success code here
		}	
		else {
			//log failed.
		}
	}
	
	//determines if a given transaction is unique
	private function isUnique($txn_id) {
		//use data attribute of the Transaction module
		return true;
	}
}

class User {
	private $email;
	private $balance;
	public function __construct($e, $b) {
		$this->email = $e;
		$this->balance = $b;
	}
	
	public function __get($key) {
		return $this->$key;
	}
}

$p = new PaypalPayment('seller_1257453128_biz_api1.hotmail.co.uk','1257453132','AFcWxV21C7fd0v3bYYYRCpSSRl31Aq0ieLDgl8-TejuLH.olLgv6IgXf');
$p->massPay(array(new User('buyer_1257454052_per@hotmail.co.uk','5.00')));
//$p->IPN();
