<?php
error_reporting(E_ALL);
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
define('APPLICATION_ENV', 'development');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH,
    realpath(APPLICATION_PATH . '/../../library'),
    realpath(APPLICATION_PATH . '/models'),
	realpath(APPLICATION_PATH . '/modules'),
    get_include_path(),
)));
;

/**
* The four lines that follow allow the automatic loading of class based on the principle of
* replacing the Underscores in the class name with a / and then translating it to path of 
* the class.
* for example the class User_Models_User will translate to User/Model/User.php, this file will
* automatically be included as soon as it is used.
*/
require_once('Zend/Loader/Autoloader.php');
$loader = Zend_Loader_AutoLoader::getInstance();
$loader->setFallbackAutoloader(true);
$loader->suppressNotFoundWarnings(false);

// Create application, bootstrap, and run
$application = new Zend_Application(APPLICATION_ENV,APPLICATION_PATH . '/configs/Application.ini');
//$db = Zend_Db::factory('Pdo_Mysql',array('host' => 'localhost', 'username' => 'root', 'password' => '', 'dbname' => 'sep'));
$db = Zend_Db::factory('Pdo_Mysql',array('host' => 'mysql.supersaid.net', 'username' => 'project_said', 'password' => 'vegeta06', 'dbname' => 'supersaid_project'));
Zend_Registry::set('db', $db);
Zend_Layout::startMvc(array('layoutPath' => APPLICATION_PATH . '/layouts'));
$application->bootstrap()->run();
