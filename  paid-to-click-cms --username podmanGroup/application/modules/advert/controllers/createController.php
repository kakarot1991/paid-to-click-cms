<?php
class Advert_CreateController extends Zend_Controller_Action {
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

		$form = new Advert_Models_Forms_Create();
		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
				$advertDetails = array(
					'advertiserID' 	=> $session->advertiser->accountID,
					'status' 		=> 'pending',
				);
				$advertDetails = array_merge($_POST, $advertDetails);
				$advert = new Advert_Models_Advert($advertDetails);
				$advert->save();
				return;
			}
		}
		$this->view->form = $form;
	}
}
?>