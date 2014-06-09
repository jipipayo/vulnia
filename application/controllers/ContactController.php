<?php

class ContactController extends Zend_Controller_Action
{


    public function indexAction()
    {

        $this->view->title = ' contact form';
        $request = $this->getRequest();
        $form = new Form_Contact();

        if ($request->isPost()) {

            if ($form->isValid($request->getPost())) {

                $f = new Zend_Filter_StripTags ();
                $email = $f->filter($this->_request->getPost('email'));
                $message = $f->filter($this->_request->getPost('message'));

                $user_info = $_SERVER ['REMOTE_ADDR'];
                $user_info .= ' ' . $_SERVER ['HTTP_USER_AGENT'];

                $mail = new Zend_Mail ('utf-8');
                $body = $user_info . '<br/>' . $message;
                $mail->setBodyHtml($body);
                $mail->setFrom($email);
                $mail->addTo('daniel.remeseiro@gmail.com', 'daniel remeseiro');
                $mail->setSubject('vulnia.com - contact  from ' . $email);
                $mail->send();

                $this->_helper->_flashMessenger->addMessage($this->view->translate('Message sent successfully!'));
                $this->_redirect('/');

            }

        }

        $this->view->form = $form;

    }


}