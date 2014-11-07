<?php


class AlertController extends Zend_Controller_Action
{

    private $aNamespace;
    private $notifications;
    private $auth;

    public function init()
    {

        $this->aNamespace = new Zend_Session_Namespace('Vulnia');
        $this->notifications = $this->_helper->Notifications;
        $this->auth = Zend_Auth::getInstance();
        $this->view->identity = $this->auth->getIdentity();
        //if the user is not logged redir to user login
        if (!$this->auth->hasIdentity()) {
            //keep this url in zend session to redir after login
            $this->aNamespace->redir = '/alert/create';
            $this->_helper->_flashMessenger->addMessage(
                array('error' => 'Please, login to schedule a new email alert about <b>'.
               $this->aNamespace->lastquery . '</b>'  ));
            $this->_redirect('/user/login');
        }


    }



    public function createAction()
    {
        require_once APPLICATION_PATH . '/forms/AlertCreate.php';
        $this->view->form = $form = new Form_AlertCreate();
        if( $this->aNamespace->lastquery ){
            $form->query->setValue($this->aNamespace->lastquery);
        }
    }

}
