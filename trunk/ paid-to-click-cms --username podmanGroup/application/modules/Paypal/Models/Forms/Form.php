<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form
 *
 * @author internet
 */
class Paypal_Models_Forms_Form  extends Zend_Form {
	function __construct($transaction) {
            parent::__construct();
            Paypal_Models_Paypal::initSettings();
            $this->setMethod('POST');
            $this->setAction(Paypal_Models_Paypal::get('IPNUrl'));
            $this->addElement('hidden', 'cmd', array('value' => '_xclick'));
            $this->addElement('hidden', 'business', array('value' => Paypal_Models_Paypal::get('sellersEmail')));
            $this->addElement('hidden', 'amount', array('value' => $transaction->amount));
            $this->addElement('hidden', 'item_name', array('value' => $transaction->name));
            $this->addElement('hidden', 'item_number', array('value' => $transaction->transactionID));
            $this->addElement('hidden', 'notify_url', array('value' => 'http://www.project.supersaid.net/paypal/ipn'));
            $this->addElement('image', 'submit', array('src' => 'https://www.paypal.com/en_GB/i/btn/btn_cart_LG.gif'));

            $this->addElement('hidden', 'return', array('value' => $transaction->returnUrl));
            $this->addElement('hidden', 'cancel_return', array('value' => $transaction->cancelUrl));
            
            foreach($this->getElements() as $key=>$value) {
                $this->getElement($key)->removeDecorator('label');
            }
        }
}
?>
