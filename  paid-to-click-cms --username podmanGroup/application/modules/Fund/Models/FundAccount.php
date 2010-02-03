<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FundAccount
 *
 * @author internet
 */
class Fund_Models_FundAccount extends Transaction {
    function  __construct($args) {
        $args['name'] = 'fund';
        parent::__construct($args);
    }

    static function fromTransaction(&$transaction) {
        $cashout = new Fund_Models_FundAccount(array());
        foreach($transaction as $key=>$value) {
            $cashout->$key = $value;
        }
        return $cashout;
    }
    
    static function getFundAccountsBy($args) {
        $args['name'] = 'fund';
            $transactions = self::getTransactionsBy($args);
            $funds = array();
            foreach($transactions as $transaction ){
                $funds[] = self::fromTransaction($transaction);
            }
            return $funds;
    }
    
    static function getUsersLastFundAccount($status = NULL) {
        if($status != NULL) {
            $args['status'] = $status;
        }
        $args['order'] = array('field' => 'transactionID', 'direction' => 'DESC');
        $funds = self::getFundAccountsBy($args);
        if(!empty($funds)) {
            return $funds[0];
        }
        else {
            return NULL;
        }
    }
}
?>
