<?php

class User_IndexController extends Zend_Controller_Action {
    public function init() {
    }


    public function indexAction() {
    	$session = new Zend_Session_Namespace('user');
    	if($session->user == null) {
    		$this->_redirect('/user/login');
    		return;
    	}
    	$this->view->user = $session->user->render();
    }
}