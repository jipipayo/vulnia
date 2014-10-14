<?php


class AlertController extends Zend_Controller_Action
{

    public function init()
    {

        $this->notifications = $this->_helper->Notifications;
        $auth = Zend_Auth::getInstance();
        $this->view->identity = $auth->getIdentity();
        //if the user is not logged redir to user login
        if (!$auth->hasIdentity()) {
            $this->_helper->_flashMessenger->addMessage(array('error' => 'Please, login or register to schedule a new email alert about <b>'. $qw . '</b>'  ));
            $this->_redirect('/user/profile');
        }


    }


    public function indexAction()
    {

    }




    public function createAction()
    {

    }

}
