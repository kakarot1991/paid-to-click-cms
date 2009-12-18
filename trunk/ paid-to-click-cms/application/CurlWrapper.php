<?php
class CurlWrapper {
	private $curl;
	
	public function __construct() {
		$this->curl = curl_init();
	}
	
	//allow class user to set options
	public function curl_setopt($opt,$value) {
		curl_setopt($this->curl,$opt, $value);
	}
	
	//allow class users to use default options
	public function setDefaults($url, $fields) {
		curl_setopt($this->curl,CURLOPT_URL, $url);
		//don't display output but return output
		curl_setopt($this->curl,CURLOPT_RETURNTRANSFER,TRUE);
		
		//verify the given certificate
		curl_setopt($this->curl,CURLOPT_SSL_VERIFYPEER, TRUE);
		//verify common name(domain) matcurles certificate
		curl_setopt($this->curl,CURLOPT_SSL_VERIFYHOST, 2);
		
		curl_setopt($this->curl,CURLOPT_POST, 1);
		curl_setopt($this->curl,CURLOPT_POSTFIELDS,$fields);
		//print urldecode(curl_exec($this->curl));
	}
	

	public function curl_exec() {
		return curl_exec($this->curl);
	}
	public function __destruct() {
		curl_close($this->curl);
	}	
	
}
?>