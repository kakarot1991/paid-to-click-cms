<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Info
 *
 * @author internet
 */
class SiteInfo extends ModuleInfo {
    static function getInfo() {
        return array();
    }

    static function getMenu() {
        $menus['all']['Home'] = array(
            'module' => 'default',
        );
        $menus['loggedin']['Logout'] = array(
            'module' => 'Logout',
        );
        return $menus;
    }

}
?>
