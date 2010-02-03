<?php
/**
*author Said Shire
*/


/**
* Represent a transaction that takes place in the system
*/
class Transaction {
        /**
         * @access protected
         * @var const string $self::TRANSACTION_TABLE
        * The name of the transaction table
        */
        const TRANSACTION_TABLE = 'transaction';

	/**
	* @access protected
	* @var integer $ownerID
	* The id of the user that owns this transaction
	*/
	protected $ownerID;
	/**
	* @access protected
	* @var Date $startDate
	* The date the transaction started, the date should be in YYYY-MM-DD format
	*/
	protected $startDate;//the date the transaction started, the date should be in YYYY-MM-DD format
	/**
	* @access protected
	* @var Date $completionDate
	* The date the transaction completed, the date should be in YYYY-MM-DD format
	*/
	protected $completionDate;//the date the transaction completed, the date should be in YYYY-MM-DD format
	/**
	* @access protected
	* @var Double $amount
	* The amount the transaction is for
	*/
	protected $amount;
	/**
	* @access protected
	* @var String $status
	* The status of the transaction, i.e pending
	*/
	protected $status;//the status of the transaction, whether it has completed
	/**
	* @access protected
	* @var String $name
	* The name of the transaction
	*/
	protected $name;
	/**
	* @access protected
	* @var Integer $transactionID
	* Unique id for the transaction, this id is automatically assigned when the transaction is saved
	*/
	protected $transactionID;//
	/**
	* @access protected
	* @var string $data
	* Extra data about the transaction. useful for third party modules.
	*/
	protected $data;

	/**
	* @access protected
	* @var string $data
	* Extra data about the transaction but that is not stored.
	*/
        protected $nonStoreableData;       

	/**
	* @param array $args
	* Creates a new Transaction. Uses $args to initializes internal variables.
	* The keys of $args must be of the following name,amount,startDate,endDate,data,status,pending
	*/
        function  __construct($args) {
            foreach($this as $key=>$value) {
                if(isset($args[$key])){
                    $this->$key = $args[$key];
                }
            }
        }

	/**
	* Fetches a transaction by the given key-value pair(s)
	* If no transactions are found an empty array is returned
	* @param array $args the criteria to use to search for transaction
	* @return array|array(Transaction)
	*/
	public static function getTransactionsBy($args) {
            $db = Zend_Registry::get("db");
            $sql = 'SELECT t.*, u.username FROM ' . self::TRANSACTION_TABLE . ' t JOIN ';
            $sql .= User_Models_User::USER_TABLE . ' u ON u.accountID = t.ownerID';
            if(empty($args['order'])) {
                $order = array('direction' => 'DESC', 'field' => 'transactionID');
            }
            else {
                $order = $args['order'];
                unset($args['order']);
            }
            if(!empty($args)) {
                $where = ' WHERE ';
                foreach(array_keys($args) as $key) {
                    $where .= "t.$key = ? AND ";
                }
                $where = rtrim($where, 'AND ');
                $sql .= $where;
            }
            $sql .= " ORDER BY t.$order[field] $order[direction]";
            //var_dump(array_values($args));
            //exit($sql);
            $stat = $db->query($sql ,array_values($args));

            /**
             * loop throw all the records returned and create new
             * Transaction objects
             */
            $transactions = array();
            while($row = $stat->fetch()) {
                $transaction = new Transaction($row);
                $transaction->nonStoreableData = $row['username'];
                $transactions[] = $transaction;
            }
            return $transactions;
	}


	/**
	* Saves the transaction to permanent storage.
	* Creates the transaction if it's a new transaction
	* but also updates the transaction if it's an existing transaction
	* @return void
	*/
	function save() {
            $db = Zend_Registry::get('db');
            //get names and values of all attributes
            $pairs = $this->_getPairs();
            if($this->transactionID != NULL) {
                $db->update(self::TRANSACTION_TABLE, $pairs, 'transactionID = ' . $this->transactionID);
            }
            else {
                $db->insert(self::TRANSACTION_TABLE, $pairs);
                $this->transactionID = $db->lastInsertId();
                }
	}

	/**
	* Gets the names of attributes along with their value in a key-value array
	* @access protected
	* @return array
	*/
	protected function _getPairs() {
		$pairs = array();
		foreach($this as $key=>$val) {
		//don't include transactionID because that's generated automatically
			if($key != 'transactionID' && $key != 'nonStoreableData') {
				$pairs[$key] = $val;
			}
		}
		return $pairs;
	}

	/**
	* gets the given attributes name($key) value
	* @param $key the name of the attribute to get
	* @return [given keys value type]
	*/
	function __get($key) {
		return $this->$key;
	}

	/**
	* @param $key the name of the attribute to set
	* @param $value the value to assign the attribute to
	* @return void
	*/
	function __set($key, $value) {
		switch($key) {
			case "transactionID":
				//shouldn't set these variables from outside
			break;
			default:
				$this->$key = $value;
			break;
		}

	}

}
