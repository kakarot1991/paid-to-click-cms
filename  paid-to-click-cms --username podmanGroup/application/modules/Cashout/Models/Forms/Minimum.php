<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fund
 *
 * @author internet
 */
class Cashout_Models_Forms_Minimum extends Zend_Form {
	function __construct() {
            parent::__construct();
            $this->setMethod('POST');
            $this->setAction('/cashout/admin/minimum');
            $amount = new Zend_Form_Element_Text('amount');
            $submit = new Zend_Form_Element_Submit('submit');
            $amount->addValidator(new Fund_Models_Forms_Validators_Currency());
            $amount->setAllowEmpty(false);
            $amount->setLabel('Amount');
            $submit->setLabel('Set Minimum');
            $this->addElements(array($amount,$submit));
        }
}
?>
