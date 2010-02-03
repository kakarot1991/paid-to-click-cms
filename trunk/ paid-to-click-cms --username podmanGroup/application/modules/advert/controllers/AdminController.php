<?php
class Advert_AdminController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction(){
		//users cannot register as advertisers
		$session = new Zend_Session_Namespace('user');
/*		if($session->admin != null) {
			$this->_redirect('/user');
			return;
		}*/
		$form = new Advert_Models_Forms_Timer();
		$timer = new Advert_Models_Timer();
		$form->getElement('timer');
		if( ($this->getRequest()->isPost()) && ($form->isValid($_POST)) ) {
			$timer->interval = $_POST['interval'];
			$this->_redirect('/admin');
		}

		$form->setDefaults( array( 'interval' => $timer->seconds ) );
		$form->getElement('timer');
		$this->view->form = $form;
	}
}
?>