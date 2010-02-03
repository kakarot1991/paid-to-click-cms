<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class Account_LoginController extends Zend_Controller_Action {
	/**
	 * The user is logged in already and cannot login again
	 * @name LOGGED_IN_ALREADY
	 */
	const LOGGED_IN_ALREADY = 'You are logged in already';

	public function init() {
            Zend_Session::start();
	}

        function isLoggedin(){
            foreach(array('user','advertiser','administrator') as $person){
                if(Zend_Session::namespaceIsset($person)) {
                    return true;
                }
            }
            return false;
        }
	public function indexAction() {
		$form = new Account_Models_Forms_Login();
		if(self::isLoggedin()) {
			$this->view->formErrors = array(self::LOGGED_IN_ALREADY);
			return;
		}

		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
                            $acc = Account_Models_Account::login2($_POST['username'], $_POST['password']);
                            if(!is_object($acc)) {
                                    $this->view->formErrors = array($acc);
                            }
                            else{
                                $type = $acc->type;
                                $session = new Zend_Session_Namespace($type);
                                $session->$type = $acc;
                                $this->getResponse()->setRedirect('/' . $acc->type);
                                return;
                            }
			}
		}
		$this->view->form = $form;
	}


}
?>