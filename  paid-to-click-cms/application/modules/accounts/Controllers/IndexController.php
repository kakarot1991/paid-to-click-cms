<?php

class Accounts_IndexController extends Zend_Controller_Action {

	function preDispatch() {
		exit('yes');
	}
	public function init() {
	}


	public function indexAction() {
		//code taken frome http://www.zfforums.com/zend-framework-components-13/model-view-controller-mvc-21/there-no-render-option-view-811.html#post2286
		$this->getHelper('viewRenderer')->setNoRender();
	}
}