<?php

class TestController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $session = new Zend_Session_Namespace('default');
        if(isset($session->counter))
            $session->counter++;
        else
            $session->counter = 0;


        $this->view->counter = $session->counter;
       // $x = $this->view->render('test.phtml');
       // var_dump($x);
       //exit('in');

        // action body*/
    }

    public function testAction() {

    }


}

