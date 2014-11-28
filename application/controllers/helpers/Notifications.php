<?php

class Zend_Controller_Action_Helper_Notifications extends Zend_Controller_Action_Helper_Abstract {

    function postDispatch() {
            $flashnotices = $this->getActionController()->getHelper('flashMessenger');
            $allnotices = $flashnotices->getMessages ();
            if ( $flashnotices->hasCurrentMessages () ) {
                $allnotices = array_merge ( $allnotices, $flashnotices->getCurrentMessages() );
                $flashnotices->clearCurrentMessages ();
                $flashnotices->clearMessages ();
            }
            $view = $this->getActionController()->view;
            $view->mensajes = $allnotices;
    }
}

