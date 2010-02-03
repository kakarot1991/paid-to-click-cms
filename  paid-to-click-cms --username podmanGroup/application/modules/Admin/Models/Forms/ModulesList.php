<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModulesList
 *
 * @author internet
 */
class Admin_Models_Forms_ModulesList extends Zend_Form {
    function  __construct($modules) {
        parent::__construct();
        $this->setMethod('POST');
        $this->setAction('/admin/modules');
        $category = array();
        foreach(array_values($modules) as $module) {
            $category[$module['category']][] = $module['name'];
            $options = array(
                'value'     => $module['name'],
                'label'     => $module['name'] . ": $module[description]",
                'checked'   => $module['status'] != 'active' ? '' : 'checked'
            );
            $this->addElement('checkbox', $module['name'], $options);
        }
        foreach($category as $key=>$value) {
            $this->addDisplayGroup(array_values($value), $key, array('legend' => $key, 'class' => 'moduleCategory'));
        }
        $this->addElement('submit', 'Save');
    }
}
?>
