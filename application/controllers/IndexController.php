<?php
class IndexController extends Zend_Controller_Action
{

    public function init()
    {

        $this->notifications = $this->_helper->Notifications;

    }

    public function indexAction()
    {

        $this->view->title = 'search and find all the software security vulnerabilities';

        $cve = new Model_Vuln();

        $this->entries = $cve->getVulnsDesc();


        $page = (int)$this->_getParam('page');
        $paginator = Zend_Paginator::factory($this->entries);
        $paginator->setDefaultScrollingStyle('Elastic');
        $paginator->setItemCountPerPage(10);

        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;


    }


}
