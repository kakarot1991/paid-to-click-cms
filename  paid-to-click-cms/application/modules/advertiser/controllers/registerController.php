<?php
class advertiser_RegisterController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction(){
		//users cannot register as advertisers
		$session = new Zend_Session_Namespace('user');
		if($session->user != null) {
			//$this->_redirect('/user');
			Zend_Session::destroy();
			//return;
		}

		$form = new Advertiser_Models_Forms_Registration();
		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
				$advertiser = new Advertiser_Models_Advertiser($_POST);
				$advertiser->banReason = '';
				$advertiser->balance = '';
				$advertiser->status = 'pending';
				$advertiser->IP =$_SERVER['REMOTE_ADDR'];

				$errorCode = $advertiser->register();
				if($errorCode != Advertiser_Models_Advertiser::SUCCESS){
					$this->view->formErrors = $errorCode;
				}
				else {
					$this->_redirect('/advertiser');
					return;
				}
			}
		}
		$this->view->form = $form;
	}
}
?>