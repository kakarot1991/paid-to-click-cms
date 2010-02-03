<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Site
 *
 * @author internet
 */
class Site {
    const RESOURCE_TABLE = 'variables';
    private static $resources;

    static function getResource($name) {
        if( !isset(self::$resources[$name]) ){
            $db = Zend_Registry::get('db');
            $stmt = $db->query('SELECT value FROM ' . self::RESOURCE_TABLE . ' WHERE name = ?', $name);
            $row  = $stmt->fetch();
            self::$resources[$name] = $row['value'];
        }
        return self::$resources[$name];
    }

    /*
     * @param string $name resource name
     * @param mixed $value resource value
     */
    static function setResource($name, $value) {
        //resource is not saved in storage, save it
        self::getResource($name);
        $data['value'] = $value;
        $data['name'] = $name;
        if(!isset(self::$resources[$name])){
            self::$resources[$name] = $value;
            $db = Zend_Registry::get('db');
            $db->insert(self::RESOURCE_TABLE, $data) ;
        }
        //update resource in storage
        else {
            self::$resources[$name] = $value;
            $db = Zend_Registry::get('db');
            $where['name = ?'] =  $name;
            $db->update(self::RESOURCE_TABLE, $data, $where) ;
        }
    }

    static function getInstance($class, $arg = NULL) {
        if($arg != null) {
            $class .= "($arg)";
        }
        return eval("return new $class;");
    }

    static function getPersons() {
        return array(
            'all',
            'visitor',
            'loggedin',
            'user',
            'advertiser',
            'administrator'
        );
    }
}
?>
