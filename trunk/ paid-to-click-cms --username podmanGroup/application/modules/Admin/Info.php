<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Admin_Info
 *
 * @author internet
 */
class Admin_Info extends ModuleInfo {
    static function getInfo() {
        return array(
            'name'          => 'Admin',
            'description'   => 'Allows managment of system',
            'category'      => 'Core',
            'dependecies'   => array()
        );
    }

    static function getMenu() {
        $menus['administrator']['Admin'] = array(
            'module' => 'Admin'
        );
        $menus['administrator']['Modules'] = array(
            'module'        => 'Admin',
            'controller'    => 'Modules'
        );
        return $menus;
    }
}
?>
