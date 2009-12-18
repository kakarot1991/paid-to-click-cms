<?php
abstract class Accounts_BasicAccount {
	protected $username;
	protected $password;
	protected $email;
	protected $status;
	protected $accountID;
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