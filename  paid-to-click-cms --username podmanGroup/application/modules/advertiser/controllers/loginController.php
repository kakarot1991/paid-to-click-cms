<?php
class Advertiser_LoginController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction(){
		//users cannot register as advertisers
		$session = new Zend_Session_Namespace('user');
		if($session->user != null) {
			$this->_redirect('/user');
			return;
		}

		//check if the advertiser is logged in again
		$session = new Zend_Session_Namespace('advertiser');
		if($session->advertiser != null) {
			$this->_redirect('/advertiser');
			return;
		}

		$form = new User_Models_Forms_Login();
		$form->setAction('/advertiser/login');
		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
				$advertiser = Advertiser_Models_Advertiser::login($_POST['username'], $_POST['password']);
				if(get_class($advertiser) != 'Advertiser_Models_Advertiser') {
					$this->view->formErrors = array($advertiser);
				}
				else{
					$session = new Zend_Session_Namespace('advertiser');
					$session->advertiser = $advertiser;
					$this->_redirect('/advertiser');
					return;
				}
			}
		}
		$this->view->form = $form;
	}
}
?>