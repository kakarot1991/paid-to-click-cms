<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyPlugin
 *
 * @author internet
 */
class Plugins_NavigationPlugin extends Zend_Controller_Plugin_Abstract {
    //code to add before dispatching to actions
    function preDispatch(Zend_Controller_Request_Abstract $request) {
        $front = Zend_Controller_Front::getInstance();
        //the path to the modules directory
        $modulesPath = realpath(APPLICATION_PATH . '/modules');
        //menus loaded from modules
        $menus = array();
        //get the sections available for the person issuing the request
        $sections = self::getSections();

        /**
         * Get the side wide menus first
         */
        $menu = SiteInfo::getMenu();
        self::getModulePages($sections, $menu, $menus);

        /**
         * Scan all the modules and then load Info.php file and get the menus
         * from it
         */
        foreach(scandir($modulesPath) as $module){
            $infoFile = "$modulesPath/$module/Info.php";
            if(!is_file($infoFile) ) {
                continue;
            }
            $moduleMenus = eval("return $module" . '_Info::getMenu();');
            self::getModulePages($sections, $moduleMenus, $menus);
        }*/
        $front = Zend_Controller_Front::getInstance();
        $layout = Zend_Layout::getMvcInstance();
        $menus = unserialize(Site::getResource('site_menu'));
        var_dump($menus);exit;
        $layout->getView()->navigation(new Zend_Navigation($menus));
    }

    /**
     *Takes the module menus and converts them to Zend_Navigation_Page
     * @param array $sections
     * @param array $moduleMenus
     * @param array $menus
     * @return
     */
    function getModulePages($sections, $moduleMenus, &$menus) {
        foreach($sections as $section) {
            if(isset($moduleMenus[$section])) {
                $menu['label'] = array_shift(array_keys($moduleMenus[$section]));
                $menu = array_merge($menu, array_shift(array_values($moduleMenus[$section])));
                $menus[] = Zend_Navigation_Page::factory($menu);
            }
        }
        return $menus;
    }

    //returns the person issuing this request
    function getPerson() {
        Zend_Session::start();
        $person = 'visitor';
        foreach(Site::getPersons() as $who) {
            if(Zend_Session::namespaceIsset($who)) {
                $session = new Zend_Session_Namespace($who);
                if($session->$who != null) {
                    $person = $who;
                    break;
                }
            }
        }
        return $person;
    }

    function getSections() {
        $sections = array('all',self::getPerson());
        if(!in_array('visitor', $sections)) {
            array_push($sections, 'loggedin');
        }
        return $sections;
    }
}
?>
