<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cashoutController
 *
 * @author internet
 */
class Cashout_IndexController extends Zend_Controller_Action {
    function init() {
        
    }
    function indexAction(){
        $minimum = Site::getResource('cashout_minimum');
        $session = new Zend_Session_Namespace('user');
        if($session->user->balance < $minimum) {
            $this->view->cashout_failedMinimum = true;
        }
        if($this->getRequest()->isPost()){
            $this->request();
            $this->view->cashout_showConfirmation = true;
        }
    }
    
    function request() {
        $session = new Zend_Session_Namespace('user');
        /**
         * attempt to load cashout from storage just in case the user has
         * started a cashout out before but has not completed it, if not create
         * new cashout
         */
        $cashout = Cashout_Models_Cashout::getUsersLastPendingCashout($session->user->accountID);
        if($cashout == NULL) {
            $args = array(
                'ownerID'           => $session->user->accountID,
                'startDate'         => date('Y-m-d'),
                'status'            => 'pending',
                'amount'            => $session->user->balance
            );
            $session->user->balance = 0;
            $session->user->update(array());
            $cashout = new Cashout_Models_Cashout($args);
            $cashout->save();
        }
    }
}
?>
