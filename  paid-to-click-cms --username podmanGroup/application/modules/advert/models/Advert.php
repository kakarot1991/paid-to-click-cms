<?php

/**
 *
 *
 * @version $Id$
 * @copyright 2009
 */

class Advert_Models_Advert {
	const ADVERT_TABLE = 'advert';
	private $title;
	private $url;
	private $status;
	private $impressions;
	private $advertID;
	private $packageID;
	private $advertiserID;
	//$title, $url, $advertiserID, $status
	function __construct($args){
		foreach($args as $key=>$value) {
			$this->$key = $value;
		}
//		$this->title = $title;
//		$this->url = $url;
//		$this->advertiserID = $advertiserID;
//		$this->status = $status;
	}

	function save(){
		$db = Zend_Registry::get('db');
		$advert = array(
			'title' 		=> $this->title,
			'url' 			=> $this->url,
			'status' 		=> $this->status,
			'impressions' 	=> $this->impressions,
			'packageID' 	=> $this->packageID,
			'advertiserID' 	=> $this->advertiserID
		);

		if($this->advertID == NULL) {
			$db->insert(self::ADVERT_TABLE, $advert);
		}
		else {
			$db->update(self::ADVERT_TABLE, $advert, 'advertID = ' . $this->advertID);
		}
		print mysql_error();
	}

	static function getAdvertisersAds($advertiserID){
		$adverts = array();
		if(isset($advertiserID)) {
			$db = Zend_Registry::get('db');
			$rows = $db->fetchAssoc('SELECT * FROM ' . self::ADVERT_TABLE . ' WHERE advertiserID = ?', $advertiserID);
			foreach($rows as $row) {
				$advert = new Advert_Models_Advert($row);
				$advert->history =  Advert_Models_AdvertHistory::getAdvertHistory($row['advertID']);
				$adverts [$row['advertID']]= $advert;
			}
		}
		return $adverts;
	}

	/**
	* Gets given number of adverts from storage and decrements the adverts
	* impression value.This method also gets an exclusive WRITE LOCK on the advert table to stop other
	* users reading or writing to it whilst it is updating it
	* @param integer $max
	* @return array
	*/
	static function getAdvertsForDisplay($max){
		$db = Zend_Registry::get('db');
		//obtain WRITE LOCK
		$db->query('LOCK TABLES ' . self::ADVERT_TABLE . ' WRITE');
		$result = $db->query('SELECT advert.*  FROM ' . self::ADVERT_TABLE . " advert WHERE impressions > 0 AND status = 'active' LIMIT $max");
		$adverts = array();
		if($result){
			$rows = $result->fetchAll();
			$totalImpressions = 0;

			//create the new advert objects
			foreach($rows as $row) {
				$row['display'] = 0;
				$adverts[]= new Advert_Models_Advert($row) ;
				$totalImpressions += $row['impressions'];
			}

			//if there are less adverts available than max then just take what's available
			if($totalImpressions < $max) {
				$max = $totalImpressions;
			}

			//decrement advert impression and save the changes
			self::distrubuteAdvertsDisplay($adverts, $max);
			foreach($adverts as $advert) {
				$advert->impressions -= $advert->display;
				$advert->save();
			}
		}

		//Release lock
		$db->query('UNLOCK TABLES');
		return $adverts;
	}


	static function distrubuteAdvertsDisplay(&$adverts, $max){
		foreach($adverts as $advert) {
			if( ($advert->impressions > 0) && ($max > 0) ) {
				$advert->display += 1;
				$max -= 1;
			}
		}
		if($max > 0) {
			self::distrubuteAdvertsDisplay($adverts, $max);
		}
	}

	function remove(){
		$this->status = 'removed';
		$this->save();
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
		$this->$key = $value;
	}

	function render(){
		return array(
			'advertID'		=> $this->advertID,
			'Title'			=> $this->title,
			'Url'			=> $this->url,
			'Status'		=> $this->status,

		);
	}
}

?>