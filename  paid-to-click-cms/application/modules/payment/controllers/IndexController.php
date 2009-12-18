<?php
class Payment_IndexController extends Zend_Controller_Action {
    public function init() {
//        exit(var_export($this->_getAllParams()));
     //exit('init');
    }
    public function indexAction() {
        /*$this->view->test = 'TEST';
       // exit('inside admin');
       $fh = fopen('test.txt','w+');
       fclose($fh);*/

    }
    public function __call($name, $args) {
        exit(var_export($this->_getAllParams()));
    }
}

?>
