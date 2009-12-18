<?php
class advertiser_IndexController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction(){
		//users cannot register as advertisers
		$session = new Zend_Session_Namespace('user');
		if($session->user != null) {
			$this->_redirect('/user');
			return;
		}

	}
}
?>