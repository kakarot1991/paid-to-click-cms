<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paypal
 *
 * @author internet
 */
class Paypal_Models_Paypal {

    const PAYPAL_TABLE = 'paypal';
    const PAYPAL_DATA_TABLE = 'paypal_data';
    /**
     * These are required to query the paypal servers, and can be found inside
     * your paypal settings
     */
    private static $APIUsername;
    private static $APIPassword;
    private static $APISignature;

    /**
     * The email address associated with the site owners Paypal account
     */
    private static $sellersEmail;
    
    private static $APIVersion;
    /**
     * For a list of valid currency codes see
     * https://www.paypal.com/cgi-bin/webscr?cmd=p/sell/mc/mc_wa-outside
     */
    private static $currencyCode;

    /**
     * This is the URL we send data received from paypal back to paypal
     * for confirmation on whether paypal have sent us this information
     */
    private static $IPNUrl;

    /**
     * Used to send payments to paypal
     */
    private static $masspayUrl;

    private static $curl;

    /**
     * The URL the user is returned to after completing the purchase
     */
    private $returnUrl;
    /**
     * The URL the user is returned to after cancelling the purchase
     */
    private $cancelUrl;


    function  __construct() {
        self::initSettings();
    }
    function __set($name, $value) {
        $this->$name = $value;
    }

    function __get($name) {
        return $this->$name;
    }
    /**
     * Load the module settings from the storage
     */
    static function initSettings() {
        $db = Zend_Registry::get('db');
        $stmt = $db->query("SELECT * FROM " . self::PAYPAL_TABLE);
        $row = $stmt->fetch();
        foreach($row as $key=>$value) {
            /**
             * unfortunately PHP < 5.1.0 does not allow you set or get
             * dynamic static variables, We will use eval for now to get around
             * this problem, since these settings are coming from storage and are
             * entered by an administrator then there should be no threat of unexpected
             * code executed within eval
             * PHP BUG Report: http://bugs.php.net/bug.php?id=30716&edit=1
             */
            //eval("self::\$$key = '$value';");
            self::set($key, $value);
        }
        self::$curl = new CurlWrapper();
    }

    /**
     * Save settings to storage
     */
    static function save($args) {
        foreach($args as $key=>$value) {
            self::set($key, $value);
        }
        //can't find these automatically
        $pairs = array(
            'APIUsername'       => self::$APIUsername,
            'APIPassword'       => self::$APIUsername,
            'APISignature'      => self::$APIUsername,
            'APIVersion'        => self::$APIVersion,
            'currencyCode'      => self::$currencyCode,
            'IPNUrl'            => self::$IPNUrl,
            'masspayUrl'        => self::$masspayUrl,
            'sellersEmail'      => self::$sellersEmail,
        );
        $db = Zend_Registry::get('db');
        $db->update(self::PAYPAL_TABLE, $pairs);
    }

    /**
     * The passed array should be in the email-amount format
     * @param array $args
     */
    static function pay($args) {
        $fields = 'VERSION=' . self::$APIVersion . '&USER=' .
                self::$APIUsername . '&PWD=' . self::$APIPassword .
                '&Signature=' . self::$APISignature .
                '&METHOD=MassPay&CURRENCYCODE=' .
                self::$currencyCode . '&RECEIVERTYPE=EmailAddress&';
        $emails = '';
        $amounts = '';
        $counter = 0;
        foreach($args as $email => $amount) {
            $emails .= "L_EMAIL$counter=$email&";
            $amounts .= "L_AMT$counter=$amount&";
        }
        $emails = rtrim($emails, '&');
        $amounts = rtrim($amounts, '&');
        $fields .= "$emails&$amounts";
        self::$curl->setDefaults(self::$masspayUrl, $fields);
        self::$curl->curl_exec();
    }

    /**
     *sends all of the original data back to papypal in exact order for
     * confirmation and wait for VERFIED response. This is to make sure that
     * Paypal has sent this data and not an impersonator
     * @param array $originalData
     */
    static function IPN($originalData) {
        $fields = '';
	foreach($originalData as $key=>$value) {
            $fields .= "$key=$value&";
        }
        $fields = rtrim($fields, '&');
        //tell paypal we need validation
        $fields = "cmd=_notify-validate&" . $fields;
	self::$curl->setDefaults(self::$IPNUrl, $fields);
        $ret = self::$curl->curl_exec();

        $originalData['PP_RESPONSE'] = $ret;
        self::log($originalData);
        //make sure the response is VERFIED and that items price and currency are correct
        if( ($ret == 'VERIFIED') && ($originalData['payment_status'] == 'Completed') &&
                (self::isUnique($originalData['txn_id'])) &&
                ($originalData['receiver_email'] == self::$sellersEmail) &&
                ($originalData['mc_currency'] == self::$currencyCode)
                ) {
            $transaction = self::getTransaction($originalData['item_number']);
            /**
             * make sure the transaction was initiated and also it's of the
             * correct price and name
             */
            if($transaction != NULL &&
                    $originalData['mc_gross'] == $transaction->amount &&
                    $originalData['item_name'] == $transaction->name
                    ){
                $transaction->status = 'complete';
                $transaction->completionDate = date('Y-m-d');
                $transaction->save();
                self::logTransactionID($originalData['item_number'], $originalData['txn_id']);
            }
        }
    }
    
    private static function getTransaction($item_number) {
        $db = Zend_Registry::get('db');
        $transaction = Transaction::getTransactionsBy(array('transactionID' => $item_number));
        return $transaction[0];
    }

    //determines if a given transaction is unique
    private static function isUnique($txn_id) {
        $db = Zend_Registry::get('db');
        $stmt = $db->query('SELECT paypalTransactionID FROM ' . self::PAYPAL_DATA_TABLE . " WHERE paypalTransactionID = '$txn_id'");
        $row = $stmt->fetch();
        return empty($row);
    }

    function getForm($transaction) {
        $transaction->cancelUrl = $this->cancelUrl;
        $transaction->returnUrl = $this->returnUrl;
        return new Paypal_Models_Forms_Form($transaction);
    }

    static function get($name) {
        return eval("return self::\$$name;");
    }

    static function set($name, $value) {
        return eval("self::\$$name='$value';");
    }

    private static function logTransactionID($item_number, $txn_id) {
        $db = Zend_Registry::get('db');
        $data['paypalTransactionID']  = $txn_id;
        $data['siteTransactionID']  = $item_number;
        $db->insert(self::PAYPAL_DATA_TABLE, $data);
    }
    function log($data) {
        $fh = fopen("/home/project_said/pptest.txt",'w+');
        fwrite($fh, var_export($data,TRUE). "\r----------------------------------\r");
        fclose($fh);
    }
    
}
?>
