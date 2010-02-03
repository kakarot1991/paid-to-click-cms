<?php
class Advert_ListController extends Zend_Controller_Action {
	public function init() {

	}

	public function indexAction(){
		//make sure the user is logged in
		$session = new Zend_Session_Namespace('user');
		if($session->user == null) {
			$this->_redirect('/user');
			return;
		}

		//get and assign user adverts if they already haven't been assigned
		if($session->adverts == null) {
			//get available adverts
			$adverts = Advert_Models_Advert::getAdvertsForDisplay(4);

			//assigned the adverts to the user
			$session->adverts = $adverts;

			//log that the adverts were assigned to this user but have not been click yet
			foreach($adverts as $advert) {
				$log = array(
					'advertID' => $advert->advertID,
					'userID' => $session->user->accountID,
					'assignedDate' => date('Y-m-d'),
				);
				$history = new Advert_Models_AdvertHistory($log);
				for($i = 0;$i < $advert->display; $i++) {
					$history->save();
				}
			}

		}

		//make adverts accessible in the view
		$this->view->adverts = $session->adverts;

	}
}
?>