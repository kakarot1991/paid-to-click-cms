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

		//redirect the user to the login page if they are not logged in
		$session = new Zend_Session_Namespace('advertiser');
		if($session->advertiser == null) {
			$this->_redirect('/advertiser/login');
			return;
		}

		$this->view->advertiser = $session->advertiser->render();

	}
}
?>