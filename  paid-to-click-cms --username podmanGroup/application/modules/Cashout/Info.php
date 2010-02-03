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
class Cashout_Info extends ModuleInfo {
    static function getInfo() {
        return array(
            'name'          => 'Cashout',
            'description'   => 'Allows users to cashout their credit',
            'category'      => 'Payment',
            'dependecies'   => array()
        );
    }

    static function getMenu() {
        $menus['user']['Cashout'] = array(
            'module' => 'cashout',
        );
        $menus['administrator']['Cashout Requests'] = array(
            'module' => 'cashout',
            'controller' => 'Admin',
            'action' => 'requests',
        );
        $menus['administrator']['Set Minimum Cashout'] = array(
            'module' => 'cashout',
            'controller' => 'Admin',
            'action' => 'minimum',
        );
        return $menus;
    }

}
?>
