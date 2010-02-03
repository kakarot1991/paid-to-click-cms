<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cashout
 *
 * @author internet
 */
class Cashout_AdminController extends Zend_Controller_Action {
    public function init() {
    }
    public function requestsAction() {
        $order = array('direction' => 'ASC', 'field' => 'status');
        $args['order'] = array('direction' => 'ASC', 'field' => 'status');
        $args['status'] = 'pending';
        $cashouts = Cashout_Models_Cashout::getTransactionsBy($args);
        $this->view->cashouts = $cashouts;
    }
    function minimumAction(){
        $form = new Cashout_Models_Forms_Minimum();
        if($this->getRequest()->isPost() && $form->isValid($_POST)) {
            Site::setResource('cashout_minimum', $form->getValue('amount'));
            $this->getResponse()->setRedirect('/admin');
        }
        $this->view->form = $form;
    }

    function approveAction(){
        $user = $this->getRequest()->getParam('user');
        $cashout = Cashout_Models_Cashout::getUsersLastPendingCashout($user);
        if($cashout != null) {
            $cashout->completionDate = date('Y-m-d');
            $cashout->approve();            
            $user = User_Models_User::getUser($user);
            $pay[$user->paymentEmail] = $cashout->amount;
            
            //now send the payment to the user
            $paymentGateway = Site::getInstance(Site::getResource('payment_gateway'));
            $paymentGateway->pay($pay);
        }
        $this->getResponse()->setRedirect('/cashout/admin/requests');
    }

    function denyAction(){
        $user = $this->getRequest()->getParam('user');
        $cashout = Cashout_Models_Cashout::getUsersLastPendingCashout($user);
        if($cashout != null) {
            $cashout->completionDate = date('Y-m-d');
            $cashout->deny();
        }
        $this->getResponse()->setRedirect('/cashout/admin/requests');
    }
}
?>
