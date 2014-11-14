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

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $formulario = $form->getValues();
            }

            //Create a filter chain and add filters to title and body against xss, etc
            $f = new Zend_Filter();
            $f->addFilter(new Zend_Filter_StripTags());
            //->addFilter(new Zend_Filter_HtmlEntities());
            $formulario['query'] = $f->filter($formulario['query']);

            //get the user owner id of this alert
            $formulario['user_id_owner'] = $this->auth->getIdentity()->id;
            //get date created
            //TODO use the Zend Date object to fetch the user locale time zone
            $formulario['created'] = date("Y-m-d H:i:s", time());

            $modelAlert = new Model_Alert();

            //check first if the alert whit this user id and query word exists already!!
            $queryExists = $modelAlert->fetchAlertByQueryAndUserId( $formulario['user_id_owner'], $formulario['query']);

            if($queryExists === null){
                $modelAlert->save($formulario);
                $this->_helper->_flashMessenger->addMessage(
                array('success' => 'Your alert is saved and you will receive email alerts about <b>'.$formulario['query'].'</b>'));
                $this->_redirect('/');
            } else {
                $this->_helper->_flashMessenger->addMessage(
                array('error' => 'Sorry, you already have an alert saved about <b>'.$formulario['query'].'</b>'));
                $this->_redirect('/');//TODO redir to alert user list

            }

        }//end if request is post


    }

}
