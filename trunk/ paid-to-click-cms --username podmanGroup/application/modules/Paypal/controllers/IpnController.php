<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IPNController
 *
 * @author internet
 */
class Paypal_IpnController extends Zend_Controller_Action  {
    function init() {

    }
    function indexAction() {
        Paypal_Models_Paypal::initSettings();
        Paypal_Models_Paypal::IPN($this->_getAllParams());
    }

    function payAction() {
        Paypal_Models_Paypal::initSettings();
        Paypal_Models_Paypal::pay(array());
    }
}
?>
