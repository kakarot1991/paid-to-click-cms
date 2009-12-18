<?php
/**
*author Said Shire
*/


/**
* The name of the transaction table
*/
define('TRANSACTION_TABLE', 'transaction');

/**
* Represent a transaction that takes place in the system
*/
class Transaction {

	/**
	* @access private
	* @var integer $ownerID	
	* The id of the user that owns this transaction	
	*/
	private $ownerID;
	/**
	* @access private
	* @var Date $startDate
	* The date the transaction started, the date should be in YYYY-MM-DD format
	*/	
	private $startDate;//the date the transaction started, the date should be in YYYY-MM-DD format
	/**
	* @access private
	* @var Date $completionDate
	* The date the transaction completed, the date should be in YYYY-MM-DD format
	*/	
	private $completionDate;//the date the transaction completed, the date should be in YYYY-MM-DD format
	/**
	* @access private
	* @var Double $amount
	* The amount the transaction is for
	*/		
	private $amount;
	/**
	* @access private
	* @var String $status
	* The status of the transaction, i.e pending
	*/		
	private $status;//the status of the transaction, whether it has completed
	/**
	* @access private
	* @var String $name
	* The name of the transaction
	*/		
	private $name;
	/**
	* @access private
	* @var Integer $transactionID
	* Unique id for the transaction, this id is automatically assigned when the transaction is saved
	*/			
	private $transactionID;//
	/**
	* @access private
	* @var Integer $data
	* Extra data about the transaction. useful for third party modules.
	*/		
	private $data;//
	
	//private $PPTxnid;//paypals unique transaction ID. used to stop transaction playback

	/**
	* @param array $args
	* Creates a new Transaction. Uses $args to initializes internal variables.
	* The keys of $args must be of the following name,amount,startDate,endDate,data,status,pending
	*/
	function __construct($args) {
		foreach($args as $key=>$val) {
			$this->$key = $val;
		}
	}
	
	/**
	* Fetches a transaction by the given key-value pair(s)
	* If no transactions are found an empty array is returned
	* @param array $args the criteria to use to search for transaction
	* @return array|array(Transaction)
	*/	
	static function getTransactionBy($args) {
		$sql = 'SELECT * FROM ' . TRANSACTION_TABLE . ' WHERE ';
		foreach($args as $key=>$val) {
			$sql .= "$key=$val AND ";
		}
		$sql = rtrim($sql, 'AND ');
		
		$res = mysql_query($sql);
		$transactions = array();
		while($row = mysql_fetch_assoc($res)) {
			$transactions[]= new Transaction($row);
		}
		return $transactions;
	}


	function printer() {
		foreach($this as $k=>$v) {
			print "$k: $v <br>";
		}	
	}
	/**
	* Saves the transaction to permanent storage.
	* Creates the transaction if it's a new transaction
	* but also updates the transaction if it's an existing transaction
	* @return void
	*/
	function save() {	
		//get names and values of all attributes
		$pairs = $this->_getPairs();		
		//the sql statment to save the transaction
		$sql = '';
		//this is a new transaction
		if($this->transactionID == 0) {
			$fields = implode(',', array_keys($pairs));
			$values = implode("','", array_values($pairs));
			$sql = 'INSERT INTO ' . TRANSACTION_TABLE . '(' . $fields . ') VALUES(\'' .$values . '\')';
		}
		else {
		
			$sql .= 'UPDATE ' . TRANSACTION_TABLE . ' SET ';
			foreach($pairs as $key=>$val) {
				$sql .= "$key='$val', ";
			}
			
			$sql = rtrim($sql, ', ') . ' WHERE transactionID = ' . $this->transactionID;
		}
		mysql_query($sql);
		print mysql_error();
		print "<br>$sql<br>";
	}
	
	/**
	* Gets the names of attributes along with their value in a key-value array
	* @access private
	* @return array
	*/
	private function _getPairs() {
		$pairs = array();
		foreach($this as $key=>$val) {
		//don't include transactionID because that's generated automatically
			if($key != 'transactionID') {
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

// mysql_connect('localhost', 'root','');
// mysql_select_db('sep');
// $t = Transaction::getTransactionBy(array('status' => "'pending'"));
//$t->status = 'random';
//$t->printer();

