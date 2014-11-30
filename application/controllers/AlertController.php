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
            $this->_redirect('/user/login');
        }

    }

    public function listAction(){
        $modelAlert = new Model_Alert();
        $user_id_owner = $this->auth->getIdentity()->id;

        $this->view->alerts = $modelAlert->fetchAlertsByUserId($user_id_owner);
    }

    public function deleteAction(){
        $modelAlert = new Model_Alert();
        $user_id_session = $this->auth->getIdentity()->id;
        $alert_id = $this->_request->getParam('id');

        //only allow delete alert to alert user owner
        $alert_user_id_owner = $modelAlert->fetchAlertById($alert_id)->user_id_owner;
        if ( $alert_user_id_owner === $user_id_session  ){
            $modelAlert->delete($alert_id);
            $this->_helper->_flashMessenger->addMessage(
                array('info' => 'Alert deleted successfully' ));
            $this->_redirect('/alert/list');
        } else {
            $this->_helper->_flashMessenger->addMessage(
                array('danger' => 'Sorry, you are not allowed to do that' ));
            $this->_redirect('/');

        }
    }

    public function createAction()
    {
        require_once APPLICATION_PATH . '/forms/AlertCreate.php';
        $this->view->form = $form = new Form_AlertCreate();
        if( $this->aNamespace->lastquery ){
            $form->query->setValue($this->aNamespace->lastquery);
        }

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formulario = $form->getValues();
            }

            //Create a filter chain and add filters to query string against xss, etc
            $f = new Zend_Filter();
            $f->addFilter(new Zend_Filter_StripTags())->addFilter(new Zend_Filter_HtmlEntities());
            $formulario['query'] = trim($f->filter($formulario['query']));

            //get the user owner id of this alert
            $formulario['user_id_owner'] = $this->auth->getIdentity()->id;
            //TODO use the Zend Date object to fetch the user locale time zone
            $formulario['created'] = date("Y-m-d H:i:s", time());

            $modelAlert = new Model_Alert();
            //check first if the alert with this user id and query word exists already!!
            $queryExists = $modelAlert->fetchAlertByQueryAndUserId( $formulario['user_id_owner'], $formulario['query']);

            if($queryExists != null){
                $this->_helper->_flashMessenger->addMessage(
                array('danger' => 'Sorry, you already have an alert saved about <b>'.$formulario['query'].'</b>'));
                $this->_redirect('/alert/list');
            } else if (strlen($formulario['query']) < 3 ) {
                $this->_helper->_flashMessenger->addMessage(
                array('danger' => 'Sorry, <b>'.$formulario['query'].'</b> is less than 3 characters'));
                $this->_redirect('/alert/create');
            } else {
                //TODO: add here api call to the cronopio scheduler
                //use here a Rest Client (Guzzle or other library) to create a new scheduled job in cronopio
                //if the response is ok from cronopio api then save in alert db table
                //if not, show error msg and NOT save in db

                $modelAlert->save($formulario);
                $this->_helper->_flashMessenger->addMessage(
                array('info' => 'Your alert is saved and you will receive email alerts about <b>"'.$formulario['query'].'"</b>'));
                $this->_redirect('/alert/list');

            }

        }//end if request is post


    }

}
