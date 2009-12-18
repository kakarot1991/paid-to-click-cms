<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class User_UpdateController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction() {
		$session = new Zend_Session_Namespace('user');
		if($session->user == null) {
			$this->_redirect('/user/login');
			return;
		}

		$form = new User_Models_Forms_Update($session->user);
		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
				$session->user->update($_POST);
				$this->_redirect('/user');
				return;
			}
		}

		$this->view->form = $form;
	}

	public function passwordAction() {

	}
}
?>