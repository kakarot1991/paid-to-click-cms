<?php
class User_Bootstrap extends Zend_Application_Module_Bootstrap
{

	protected function _initAutoloaders()
	{
		$loader = Zend_Loader_AutoLoader::getInstance();
		$loader->registerNamespace('User_');
	}

}
