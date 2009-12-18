<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class User_LoginController extends Zend_Controller_Action {
	/**
	 * The user is logged in already and cannot login again
	 * @name LOGGED_IN_ALREADY
	 */
	const LOGGED_IN_ALREADY = 'You are logged in already';

	public function init() {

	}

	public function indexAction() {
		$form = new User_Models_Forms_Login();

		$session = new Zend_Session_Namespace('user');
		if($session->user != NULL) {
			$this->view->formErrors = array(self::LOGGED_IN_ALREADY);
			$sess = new Zend_Session_Namespace('user');
			//Zend_Session::destroy();
			return;
		}

		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
					$user = User_Models_User::login($_POST['username'], $_POST['password']);
					if(get_class($user) != 'User_Models_User') {
						$this->view->formErrors = array($user);
					}
					else{
						print 'welldone you are logged in...<br>';
						print 'setting session...<br>';
						$session = new Zend_Session_Namespace('user');
						$session->user = $user;
						print 'session established...<br>';
						return;
					}
			}
		}
		$this->view->form = $form;
	}


}
?>