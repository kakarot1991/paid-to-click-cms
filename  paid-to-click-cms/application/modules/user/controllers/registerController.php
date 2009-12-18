<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class User_RegisterController extends Zend_Controller_Action {
	public function init() {
	}

	public function indexAction() {
		$session = new Zend_Session_Namespace('user');
		if($session->user != null) {
			$this->_redirect('/user');
			return;
		}

		$form =  new User_Models_Forms_Registration(array());
		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
				$user = new User_Models_User($_POST);
				$user->balance = 0;
				$user->referer = 0;
				$user->roleID = 0;
				$user->banReason = '';
				$user->status = 'pending';
				$user->IP =$_SERVER['REMOTE_ADDR'];

				$errorCode = $user->register();
				if($errorCode != User_Models_User::SUCCESS){
					$this->view->formErrors = $user->errors;
				}
				else {
						$this->_redirect('/user');
						return;
				}
			}
		}
		$this->view->form = $form;
	}
}
?>