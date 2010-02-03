<?php
/**
 * Description of Cashout
 *
 * @author internet
 */
class Cashout_Models_Cashout extends Transaction {
        /**
         * key-value pair to initialize the class's attributes
         * @param array $args
         */
        function  __construct($args) {
            $args['name'] = 'cashout';
            parent::__construct($args);
        }

        static function fromTransaction(&$transaction) {
            $cashout = new Cashout_Models_Cashout(array());
            foreach($transaction as $key=>$value) {
                $cashout->$key = $value;
            }
            return $cashout;
        }
        
        static function getCashoutsBy($args) {
            $args['name'] = 'cashout';
            $transactions = self::getTransactionsBy($args);
            $cashouts = array();
            foreach($transactions as $transaction ){
                $cashouts[] = self::fromTransaction($transaction);
            }
            return $cashouts;
        }
        static function getUsersLastPendingCashout($userID) {
            $cashouts = self::getCashoutsBy(array('ownerID' => $userID, 'status' => 'pending'));
            if(!empty($cashouts)){
                return $cashouts[0];
            }
            else {
                return NULL;
            }
        }
        
        function approve() {
            $this->status = 'complete';
            $this->save();
        }

        function deny() {
            $this->status = 'denied';
            $this->save();
        }
}
?>
