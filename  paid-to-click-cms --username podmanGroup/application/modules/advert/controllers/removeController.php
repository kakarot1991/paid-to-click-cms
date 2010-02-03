<?php
class Advert_RemoveController extends Zend_Controller_Action {
	public function init() {
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
	}

	public function indexAction(){
		$this->view->advertiser = $session->advertiser->render();
	}

	function __call($name, $args){
		$this->getHelper('viewRenderer')->setNoRender();
		$session = new Zend_Session_Namespace('advertiser');
		$advertID = $this->getRequest()->getParam('id');
		$action = $this->getRequest()->getParam('action');

		if( ($action == 'confirm') and ($session->advertiser->adverts[$advertID] != null) and ($session->advertiser->adverts[$advertID]->status != 'removed') ) {
			$session->advertiser->adverts[$advertID]->remove();
			print 'advert removed';
		}
		elseif( (is_numeric($action)) and ($session->advertiser->adverts[$action] != null) and ($session->advertiser->adverts[$action]->status != 'removed') ){
			$this->view->advert = $session->advertiser->adverts[$action];
			print $this->view->render('remove/index.phtml');
		}
		else {
			$this->_redirect('/advertiser');
		}
	}
}
?>