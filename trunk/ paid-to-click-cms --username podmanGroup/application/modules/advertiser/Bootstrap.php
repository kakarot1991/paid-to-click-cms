<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */


class Advertiser_Bootstrap extends Zend_Application_Module_Bootstrap
{

	protected function _initAutoloaders()
	{
		$loader = Zend_Loader_AutoLoader::getInstance();
		$loader->registerNamespace('Advertiser_');
	}


}
?>