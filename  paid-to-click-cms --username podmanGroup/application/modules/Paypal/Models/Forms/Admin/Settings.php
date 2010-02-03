<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Settings
 *
 * @author internet
 */
class Paypal_Models_Forms_Admin_Settings extends Zend_Form {
    function __construct() {
        parent::__construct();
        $this->setName('Paypal Settings');
        $this->setMethod('POST');
        $this->setAction('/paypal/admin');
        Paypal_Models_Paypal::initSettings();
        $this->addElement('text', 'APIUsername', array(
            'value' => Paypal_Models_Paypal::get('APIUsername'),
            'label' => 'API Username'
                ));
        
        $this->addElement('text', 'APIPassword', array(
            'value' => Paypal_Models_Paypal::get('APIPassword'),
            'label' => 'API Password'
                ));        
        
        $this->addElement('text', 'APISignature', array(
            'value' => Paypal_Models_Paypal::get('APISignature'),
            'label' => 'API Signature'
                ));

        $this->addElement('text', 'APIVersion', array(
            'value' => Paypal_Models_Paypal::get('APIVersion'),
            'label' => 'The API Version to use'
                ));

        $this->addElement('text', 'IPNUrl', array(
            'value' => Paypal_Models_Paypal::get('IPNUrl'),
            'label' => 'IPN URL'
                ));

        $this->addElement('text', 'currencyCode', array(
            'value' => Paypal_Models_Paypal::get('currencyCode'),
            'label' => 'Currency Code'
                ));

        $this->addElement('text', 'sellersEmail', array(
            'value' => Paypal_Models_Paypal::get('sellersEmail'),
            'label' => 'Your Paypal Email'
                ));

        $this->addElement('submit', 'submit', array(
            'value' => 'save'
                ));
    }
}
?>
