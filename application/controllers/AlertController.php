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
            //keep this url in zend session to redir after login
            $aNamespace = new Zend_Session_Namespace('Vulnia');
            $aNamespace->redir = '/alert/create';
            $this->_helper->_flashMessenger->addMessage(
                array('error' => 'Please, login to schedule a new email alert about <b>'.
               $aNamespace->lastquery . '</b>'  ));
            $this->_redirect('/user/profile');
        }


    }



    public function createAction()
    {
        die('alerts will be available soon.');
        //$aNamespace = new Zend_Session_Namespace('Vulnia');
        //var_dump($aNamespace->lastquery);die;
    }

}
