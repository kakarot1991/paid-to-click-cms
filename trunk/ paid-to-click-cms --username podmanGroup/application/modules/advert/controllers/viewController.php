<?php
class Advert_ViewController extends Zend_Controller_Action {
	public function init() {
		//add code to stop non users

		//disable layout markup
		$this->_helper->layout->disableLayout();
	}

	public function indexAction(){

	}

	/**
	*checks if the user has finished viewing the advert
	* if so records advert as has been visited and decrements the display value
	* if display has reached 0 then advert is removed from session
	* This method is called from javascript and prints OK on success or
	* prints ERROR on failure
	*/
	public function checkAction() {
		$end = time();
		$state = 'OK';
		$session = new Zend_Session_NameSpace('user');

		//check if the user has started viewing an advert
		if( ($session->timer == null) || (!isset($session->timer->advertID)) ){
			$state = 'ERROR';
		}
		//check if the user waited the assigned interval
		else if( ($end - $session->timer->start) < $session->timer->interval ) {
			$state = 'ERROR';
		}
		else {
			foreach($session->adverts as $key=>$advert) {
				if($advert->advertID == $session->timer->advertID) {
					//log that the user has visted this advert on this day
					$log = array(
						'advertID' => $advert->advertID,
						'userID' => $session->user->accountID,
						'clickedDate' => date('Y-m-d'),
					);
					$history = new Advert_Models_AdvertHistory($log);
					$history->save();

					//update users balance
					$session->user->balance += $session->user->role->ppc;
					$session->user->update(array());

					//unset to stop replay of viewing advert more than once
					unset($session->timer->advertID);
					unset($session->timer->start);
					$advert->display -= 1;
					break;
				}
			}
		}
		$this->getHelper('viewRenderer')->setNoRender();
		print $state;
	}

	/**
	*This is a magic method, in the sense it is called when a function is called on this
	*object but the function has not been implemented. We are using this because the actions
	* for this controller are dynamic(the id of the advert to view)
	* example of request: advert/view/123
	*/
	function __call($name, $args){
		$session = new Zend_Session_NameSpace('user');

		//remove 'Action' ZF attached from the function name
		$name = str_replace('Action','' ,$name );

		//if it's not a number it's obviously not an advert id
		if(!is_numeric($name)) {
			$this->_redirect('/advert/list');
		}

		//set the viewing start time up.
		if($session->timer == null){
			$session->timer = new Advert_Models_Timer();
		}

		$session->timer->start = time();

		//try to match the request to an assigned advert
		$match = false;
		foreach($session->adverts as $advert) {
			if( ($advert->advertID == $name) && ($advert->display > 0) ) {
				$this->view->advert = $advert;
				$session->timer->advertID = $advert->advertID;
				$match = true;
				break;
			}
		}

		//if no match was found then user hasn't been assigned this advert
		if(!$match) {
			$this->_redirect('/advert/list');
		}

		/* we need to change the view because by default it will be mapped to
		* the action name (which is dynamic)
		*/
		$this->view->interval = $session->timer->interval;
		$this->getHelper('viewRenderer')->setRender('index');
	}

}
?>