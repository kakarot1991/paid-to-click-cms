<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CancelController
 *
 * @author internet
 */
class Fund_CancelController extends Zend_Controller_Action {
    public function init() {
    }

    function indexAction(){
        $this->getHelper('viewRenderer')->setNoRender(true);
        if($session->user->lastFundTransaction != NULL) {
            $session->user->lastFundTransaction->status = 'cancelled';
            $session->user->lastFundTransaction->save();
            $session->user->lastFundTransaction = NULL;
        }
        $this->_redirect('/');
    }
}
?>
