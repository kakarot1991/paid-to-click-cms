<?php
class Payment_Bootstrap extends Zend_Application_Module_Bootstrap
{
	protected function _initAutoload()
	{
		$al = new Zend_Application_Module_Autoloader(array(
		'basePath' => APPLICATION_PATH . '/modules/user',
		'namespace' => 'User_Models_',
		));
		return $al;
	}
}
