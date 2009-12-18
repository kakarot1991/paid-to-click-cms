<?php
class LogoutController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction(){
		Zend_Session::destroy();
		//code taken frome http://www.zfforums.com/zend-framework-components-13/model-view-controller-mvc-21/there-no-render-option-view-811.html#post2286
		$this->getHelper('viewRenderer')->setNoRender();
		$this->_redirect('/');
	}
}
?>