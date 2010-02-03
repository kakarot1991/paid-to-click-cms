<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account_Info
 *
 * @author internet
 */
class Account_Info extends ModuleInfo {
    static function getInfo() {
        return array(
            'name'          => 'Account',
            'description'   => 'Accounts essentials',
            'category'      => 'Core',
            'dependecies'   => array()
        );
    }

    static function getMenu() {
        $menus['visitor']['Login'] = array(
            'module'        => 'Accounts',
            'controller'    => 'Login'
        );
        return $menus;
    }
}
?>
