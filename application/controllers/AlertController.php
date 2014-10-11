<?php


class AlertController extends Zend_Controller_Action
{

    public function init()
    {

        $auth = Zend_Auth::getInstance();
        $this->view->identity = $auth->getIdentity();
        //if the user is not logged redir to user login
        if (!$auth->hasIdentity()) {
            $this->_helper->_flashMessenger->addMessage(array('error' => 'Please, login or register to schedule a new email alert about <b>'. $qw . '</b>'  ));
            $this->_redirect('/user/profile');
        }

        //$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        //$this->view->mensajes = $this->_flashMessenger->getMessages();

    }


    public function indexAction()
    {

    }




    public function createAction()
    {

    }

}
