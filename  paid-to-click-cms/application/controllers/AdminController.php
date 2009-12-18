<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



class AdminController extends Zend_Controller_Action {
    public function init(){
        //$this->_forward($action, $controller, $module)

        

        //array ( 'module' => 'payment', 'controller' => 'index', 'action' => 'index', )
    }

    public function indexAction(){
        //$this->_forward('index', 'index','payment');
        $this->_flashMessage('');
    }

    public function formAction(){
        exit('got form');
    }

    /*public function __call($name, $args) {
        //$this->_forward('index');
    }*/
/*        $this->_setParam('controller', 'admin');
        $arg = $this->getRequest()->getUserParams();
        array_shift($arg);
        array_shift($arg);
        //array_shift($arg);
        exit(var_export($arg));
        
        foreach($this->_getAllParams() as $k=>$v) {
            if($k == 'action') {

            }
        }*/
        //$this->_forward($this->_getParam('index'), $this->_getParam('index'),$this->_getParam('module'));
        //exit(var_export($this->_getAllParams()) . "<br> $name");

        //$this->_forward('index', 'index','payment', array('do'=>'paypal'));
        //exit(var_export($this->getRequest()->));
        //exit(var_export($this->getRequest()->getParams()));
        //exit(var_export($this->getRequest()->));
    //}
}
?>
