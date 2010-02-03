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
class Fund_IndexController extends Zend_Controller_Action {
    public function init() {
    }

    function indexAction(){
        $session = new Zend_Session_Namespace('user');
        $form = new Fund_Models_Forms_Fund();
        $minimum = Site::getResource('fund_minimum');
        $maximum = Site::getResource('fund_maximum');

        if($this->getRequest()->isPost() && $form->isValid($_POST)) {
            $fund = Fund_Models_FundAccount::getUsersLastFundAccount('pending');
            if($fund == NULL) {
                $args = array(
                    'ownerID'           => $session->user->accountID,
                    'startDate'         => date('Y-m-d'),
                    'status'            => 'pending',
                    'amount'            => $form->getValue('amount')
                );
                $fund = new Fund_Models_FundAccount($args);
            }
            $fund->amount = $form->getValue('amount');
            $fund->save();
            $session->user->lastFundTransaction = $fund;
            //display pay button
            $paymentGateway = Site::getInstance(Site::getResource('payment_gateway'));
            $paymentGateway->returnUrl = 'http://www.project.supersaid.net/fund/complete';
            $paymentGateway->cancelUrl = 'http://www.project.supersaid.net/fund/cancel';
            $this->view->form = $paymentGateway->getForm($fund);
        }
        else {
            $this->view->form = $form;
        }
    }

}
?>
