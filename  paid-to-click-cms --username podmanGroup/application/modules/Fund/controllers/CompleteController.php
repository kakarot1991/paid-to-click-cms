<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompleteController
 *
 * @author internet
 */
class Fund_CompleteController extends Zend_Controller_Action {
    public function init() {
    }

    function indexAction(){
        $session = new Zend_Session_Namespace('user');
        //var_dump($session->user->lastFundTransaction);
        if($session->user->lastFundTransaction != NULL) {
            //make sure the transaction matches a previously stored one
            $fund = Fund_Models_FundAccount::getUsersLastFundAccount('complete');
            if( ($fund !=  NULL) && ($session->user->lastFundTransaction->transactionID == $fund->transactionID) ) {
                //top up the users balance
                $session->user->balance += $fund->amount;
                $session->user->update(array());
                $session->user->lastFundTransaction = NULL;
            }
        }
    }
}
?>
