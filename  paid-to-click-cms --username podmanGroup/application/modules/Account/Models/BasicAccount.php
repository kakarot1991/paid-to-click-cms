<?php
abstract class Account_Models_BasicAccount {
        const ACCOUNTS_TABLE = 'users';

	/**
	 * The user couldn't be logged in because either username or password was incorrect
	 * Or The user doesn't exist in the database
	 * @name INVALID_LOGIN
	 */

	const INVALID_LOGIN = 'Incorrect username and/or password';

	/**
	 * The user has been banned or the IP address the user is coming from is in the ban list
	 * @name BANNED
	 */
	const BANNED = 'You have been banned';

	/**
	 * The operation was a success
	 * @name BANNED
	 */
	const SUCCESS = 'SUCCESS';

	protected $username;
	protected $password;
	protected $email;
	protected $status;
        protected $type;
	protected $accountID;
        protected $errors;
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

	abstract function register();
	abstract function update($args);
	abstract static function login($username,$password);
	abstract function resetPass();

        static function login2($username,$password) {
		$db = Zend_Registry::get('db');

		$stat = $db->query("SELECT * FROM " . self::ACCOUNTS_TABLE . " WHERE username = ? AND PASSWORD = ?", array($username,$password));
		$row = $stat->fetch();

		//check for wrong username and password
		if(!$row) {
			return self::INVALID_LOGIN;
		}

		//check if the user has been self::BANNED
		if($row['status'] == self::BANNED) {
			$this->errors[]= $row['banReason'];
			return self::BANNED;
		}
                $account = null;
                switch($row['type']){
                    case 'user' :
                        $account = new User_Models_User($row);
                    break;
                    case 'advertiser' :
                        $account = new Advertiser_Models_Advertiser($row);
                    break;
                    case 'administrator' :
                        //$account = new User_Models_User($row);
                    break;
                }
		//create and return the new user
		return $account;
        }
}



// require_once('Role.php');
// mysql_connect('localhost', 'root','');
// mysql_select_db('sep');


// $user = new User(array(
						// 'username' => 'said',
						// 'password' => '123',
						// 'email' => 'me@hotmail.com',
						// 'paymentEmail' => 'paymenow@gmail.com',
						// 'country' => 'UK',
						// 'gender' => 'M',
						// 'balance' => '0',
						// 'status' => 'active',
						// 'referer' => '0',
						// 'roleID' => 1,
						// 'IP' => '127.0.0.1',
						// 'banReason' => ''


					// )
				// );
// var_dump($user);
// $roles = Role::getRoleBy(array());
//$r = $roles[0];
// $r = get_class_vars(get_class($roles[0]));
// $r->name = 'i just changed you';
// $r->save();
// var_dump($r);
// $r = new Role(array('name' => 'elite' , 'ppc' => '0.1', 'cost' => '10.00', 'description' => 'ftw' ));
// $r->save();

?>