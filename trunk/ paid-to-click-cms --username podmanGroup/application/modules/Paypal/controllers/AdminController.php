<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminController
 *
 * @author internet
 */
class Paypal_AdminController extends Zend_Controller_Action {
    public function init() {
    }
    public function indexAction() {
        print Site::setResource('advert_timer', 100);
        return;
        $form = new Paypal_Models_Forms_Admin_Settings();
        if($form->isValid($_POST)){
            //don't save submit button
            unset($_POST['submit']);
            Paypal_Models_Paypal::save($_POST);
        }
        $this->view->form = $form;
    }
}
?>
