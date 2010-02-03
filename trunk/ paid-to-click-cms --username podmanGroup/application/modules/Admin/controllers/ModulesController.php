<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModuleController
 *
 * @author internet
 */
class Admin_ModulesController  extends Zend_Controller_Action{
	public function init() {

	}
        function indexAction(){
            if($this->getRequest()->isPost()) {
             //save module as enabled
                foreach($_POST as $key=>$value) {
                    if(strtolower($key) != 'save') {
                        $status = $value == 1 ? 'active' : 'disabled';
                        ModuleInfo::updateModuleStatus($key, $status);
                    }
                }
            }
            $modules = self::getModules();
            $this->view->form = new Admin_Models_Forms_ModulesList($modules);
            $this->view->modules = $modules;
            self::buildMenus($modules);
            self::listModules($modules);
        }

        function getModules() {
            //get saved modules
            $modules = ModuleInfo::getModulesBy();

            //the path to the modules directory
            $modulesPath = realpath(APPLICATION_PATH . '/modules');
            
            foreach(scandir($modulesPath) as $folder) {
                if( ($folder != '.') && ($folder != '..') && ($folder != 'admin') ) {
                    if(!array_key_exists($folder, $modules)) {
                        $infoFile = "$modulesPath/$folder/Info.php";
                        if(is_file($infoFile)) {
                            $mInfo = eval("return new $folder" . '_Info();');
                            $module = $mInfo->getInfo();
                            $module['status'] = 'disabled';
                            $modules[$module['name']] = $module;
                            //create the file but disbaled it
                            $mInfo->saveModule($module);
                        }
                    }
                }
            }
            return $modules;
        }
        
        function buildMenus($modules) {
            //get the persons available for the person issuing the request
            $persons = Site::getPersons();
            //the path to the modules directory
            $modulesPath = realpath(APPLICATION_PATH . '/modules');
            /**
             * Get the side wide menus first
             */
            $menus[] = SiteInfo::getMenu();

            foreach(array_values($modules) as $module){
                $infoFile = "$modulesPath/$module[name]/Info.php";
                if( ($module['status'] != 'active') || (!is_file($infoFile)) ) {
                    continue;
                }
                $menus[] = eval("return $module[name]" . '_Info::getMenu();');
                //self::getModulePages($persons, $moduleMenus, $menus);
            }
            Site::setResource('site_menu', serialize($menus));
        }
        
	public function listModules(){
                $modulesPath = APPLICATION_PATH .'/modules';
		$folders = scandir($modulesPath);
		$modules = ModuleInfo::getModulesBy();
		foreach($folders as $folder) {
			if( ($folder != '.') && ($folder != '..') && ($folder != 'admin') ) {
				if(!array_key_exists($folder, $modules)) {
                                    $infoFile = "$modulesPath/$folder/Info.php";
                                    if(is_file($infoFile)) {
                                        //$module = eval("return $folder" . '_Info::getInfo();');
                                        $mInfo = eval("return new $folder" . '_Info();');
                                        $module = $mInfo->getInfo();
                                        $module['status'] = 'disabled';
                                        $modules[$module['name']] = $module;
                                        //create the file but disbaled it
                                        $mInfo->saveModule($module);
                                    }
                                }
			}
		}
                $this->view->form = new Admin_Models_Forms_ModulesList($modules);
                //var_dump($modules);
		$this->view->modules = $modules;
	}
}
?>
