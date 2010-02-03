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
class Fund_Info extends ModuleInfo {
    static function getInfo() {
        return array(
            'name'          => 'Fund',
            'description'   => 'Allow users top-up their balance',
            'category'      => 'Payment',
            'dependecies'   => array()
        );
    }

    static function getMenu() {
        $menus['administrator']['Minimum Amount'] = array(
            'module' => 'Fund',
            'controller' => 'Admin'
            );
        $menus['user']['Fund Account'] = array(
            'module' => 'fund'
        );
        return $menus;
    }

}
?>
