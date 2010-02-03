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
    function postDispatch(Zend_Controller_Request_Abstract $request) {
        //make sure the requested module is not disabled
        $module = $this->getRequest()->getModuleName();
        if($module != 'default') {
            $module = ModuleInfo::getModule($this->getRequest()->getModuleName());
            if(isset($module['status']) && $module['status'] != 'active') {
                $this->getResponse()->setRedirect('/');
            }
        }
        $front = Zend_Controller_Front::getInstance();
        $layout = Zend_Layout::getMvcInstance();
        $savedMenus = unserialize(Site::getResource('site_menu'));
        $menus = array();
        $sections = self::getSections();
        foreach($savedMenus as $moduleMenus) {
            self::getModulePages($sections, $moduleMenus, $menus);
        }
        $layout->getView()->navigation(new Zend_Navigation($menus));
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
                foreach($moduleMenus[$section] as $title=>$mvc) {
                  $mvc['label'] = $title;
                  $menus[] = Zend_Navigation_Page::factory($mvc);
                }
            }
        }
        return $menus;
    }
}
?>
