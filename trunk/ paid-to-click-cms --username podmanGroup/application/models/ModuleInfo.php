<?php

/**
 * Description of ModuleInfo
 *
 * @author internet
 */
abstract class ModuleInfo {
    const MODULE_TABLE = 'module';
    /**
     * needs to return an associative array providing information about the module
     * the array must contain the following format:
     * array(
     *  name => module name
     *  description => module description
     *  category => the category the module is in
     *  dependecies => array(module name 1, module name 2...)
     *  do not put common modules such as user in the dependecies, if your module
     *  does not have dependecies then just use an empty array
     * )
     */
    public static abstract function getInfo();
    /**
     * needs return an associative array that has all the menus that this module
     * exposes to the site users. The array must have the following keys:
     * all: all links exposed to all persons
     * visitor:  all the links exposed to the visitor
     * loggedin: all links exposed to all logged in users
     * user: all links exposed to users
     * advertiser: all links exposed to advertisers
     * administrator: all links exposed to administrators
     *
     * Each value of the array must also be an associative array, in the following
     * format:
     * array(
     * module => your module name,
     *  controller => your controller,
     * action' => the action that handles the request
     * )
     * menus must be more than one level deep, so no submenuss
     *
     */
    public static abstract function getMenu();

    static final function getModulesBy($args = null) {
        $sql = 'SELECT * FROM ' . self::MODULE_TABLE;
        if(is_array($args)){
            $sql .= ' WHERE ';
            foreach($args as $key=>$value)  {
                $sql .= "$key = '$value'";
            }
        }

        if(!isset($args['order'])) {
            $args['order'] = array('direction' => 'ASC', 'field' => 'category');
        }
        $sql .= ' ORDER BY ' . $args['order']['field'] . ' ' . $args['order']['direction'];
        $db = Zend_Registry::get('db');
        $stmt = $db->query($sql);
        $modules = array();
        while($row = $stmt->fetch()){
            $row['dependecies'] = unserialize($row['dependecies']);
            $modules[$row['name']] = $row;
        }
        return $modules;
    }

    static final function updateModuleStatus($module, $status) {
        $db = Zend_Registry::get('db');
        $where = "name = '$module'";
        $data = array('status' => $status);
        $db->update(self::MODULE_TABLE, $data, $where);
    }

    static final function saveModule($module) {
        $db = Zend_Registry::get('db');
        $module['dependecies'] = serialize($module['dependecies']);
        $db->insert(self::MODULE_TABLE, $module);
    }

    static final function getModule($name) {
        $sql = 'SELECT * FROM ' . self::MODULE_TABLE . ' WHERE name = ?';
        $db = Zend_Registry::get('db');
        $stmt = $db->query($sql, array($name));
        $row = $stmt->fetch();
        $row['dependecies'] = unserialize($row['dependecies']);
        return $row;
    }
}
?>
