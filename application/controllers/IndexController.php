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
        $this->view->metadescription = 'search and find all the software security vulnerabilities';

        $cve = new Model_Vuln();
        $this->entries = $cve->getVulnsDesc();

        $searchModel = new Model_Search();
        $lastIndexedDate = $searchModel->getLastIndexed();

        $this->view->lastIndexed = $lastIndexedDate[0]['last_index'];


        $page = (int)$this->_getParam('page');
        $paginator = Zend_Paginator::factory($this->entries);
        $paginator->setDefaultScrollingStyle('Elastic');
        $paginator->setItemCountPerPage(10);

        $paginator->setCurrentPageNumber($page);
        $this->view->paginator = $paginator;
        $this->view->ishome = true;

    }


}
