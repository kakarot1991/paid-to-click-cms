<?php
class User_Bootstrap extends Zend_Application_Module_Bootstrap
{
	 protected function init()
	 {
	 	$loader = Zend_Loader_AutoLoader::getInstance(array(
			'resources' => array(
				'form' => array(
					'path' => 'forms/',
					'path' => 'Form_'
				)
			),
		));
	 	return $loader;
	 }
}