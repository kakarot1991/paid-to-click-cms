<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class Advertiser_UpdateController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction() {
		$session = new Zend_Session_Namespace('advertiser');
		if($session->advertiser == null) {
			$this->_redirect('/advertiser/login');
			return;
		}

		$form = new Advertiser_Models_Forms_Update($session->advertiser);
		if($this->getRequest()->isPost()) {
			if($form->isValid($_POST)){
				$session->advertiser->update($_POST);
				$this->_redirect('/advertiser');
				return;
			}
		}

		$this->view->form = $form;
	}

	public function passwordAction() {

	}
}
?>