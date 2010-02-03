<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author internet
 */
class Paypal_IndexController extends Zend_Controller_Action {
    public function init() {
    }
    public function indexAction() {
    }

    public function ipnAction() {
        Paypal_Models_Paypal::initSettings();
        Paypal_Models_Paypal::IPN($this->_getAllParams());
    }
}
?>
