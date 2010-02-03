<?php
/**
 * Description of Info
 *
 * @author internet
 */
class Fund_Info extends ModuleInfo {
    static function getInfo() {
        return array(
            'name'          => 'Advertiser',
            'description'   => 'Manages Advertisers',
            'Category'      => 'Core',
            'dependecies'   => array()
        );
    }

    static function getMenu() {
        $menus['advertiser'] = array(
            'visitor' => array(
                    'module' => 'Advertiser',
                    'controller' => 'Login',
                )
        );
        return $menus;
    }

}
?>
