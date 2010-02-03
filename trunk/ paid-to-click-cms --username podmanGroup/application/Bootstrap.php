<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initNavigation(){
		$this->bootstrap('layout');
                $front = Zend_Controller_Front::getInstance();
                $front->setRequest(new MyRequest());
                $front->registerPlugin(new Plugins_NavigationPlugin());
	}
}