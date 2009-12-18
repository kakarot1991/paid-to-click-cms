<?php
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
define('APPLICATION_ENV', 'development');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../library'),
	realpath(APPLICATION_PATH . '/modules'),
    get_include_path(),
)));

require_once('Zend/Loader.php');
Zend_Loader::registerAutoLoad();

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV,
APPLICATION_PATH . '/configs/application.ini');
 $db = Zend_Db::factory('pdo_mysql',array('host' => 'localhost', 'username' => 'root', 'password' => '', 'dbname' => 'sep'));
 Zend_Registry::set('db', $db);
 Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/layouts'));
$application->bootstrap()->run();

// $front = Zend_Controller_Front::getInstance();
// $front->addControllerDirectory(APPLICATION_PATH . '/controllers');
// $front->addModuleDirectory(APPLICATION_PATH . '/modules');
// $front->setParam('useDefaultControllerAlways', true);
// Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/layouts'));

// $db = Zend_Db::factory('pdo_mysql',array('host' => 'localhost', 'username' => 'root', 'password' => '', 'dbname' => 'sep'));
// Zend_Registry::set('db', $db);
// $front->dispatch();