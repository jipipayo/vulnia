<?php

require_once(APPLICATION_PATH . '../../library/Sphinx/sphinxapi.php');
class SearchController extends Zend_Controller_Action
{


    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }


    public function indexAction()
    {
        $page = $this->_request->getParam('page');
        $this->view->query = $qw = stripcslashes(strip_tags( $this->_getParam('vulnerabilities') ));

        $this->cl = new SphinxClient();
        $this->cl->SetServer('127.0.0.1', 3312);
        $this->cl->SetMatchMode(SPH_MATCH_EXTENDED2);
        //$this->cl->SetRankingMode(SPH_RANK_PROXIMITY);
        $this->cl->SetSortMode(SPH_SORT_EXTENDED, "@id DESC");
        $this->cl->SetMaxQueryTime(1000);


        $itemsPerSphinxPage = 10000;
        $offset = 0;
        $this->cl->SetLimits($offset, $itemsPerSphinxPage);
        $resultSph = $this->cl->Query($qw, 'vulns');

        if($resultSph === false && $qw){
            die('search engine down');
        }

        if (!is_null($resultSph["matches"])) {
            $modelVuln = new Model_Vuln();
            foreach ($resultSph["matches"] as $doc => $docinfo) {
                $resultzs[$doc] = $modelVuln->getVulnById($doc);
            }

            $this->view->query_time = $resultSph['time'];
            $this->view->total_found = $resultSph['total_found'];
            $this->view->title = $resultSph['total_found'] . ' vulnerabilities found for ' . $qw;

            if($page > 0){
                $this->view->title .= ' - page ' . $page;
            }

            $paginator = Zend_Paginator::factory($resultzs);
            $paginator->setDefaultScrollingStyle('Elastic');
            $paginator->setItemCountPerPage(10);
            $paginator->setCurrentPageNumber($page);

            $this->view->paginator = $paginator;
        } else {
            $this->_helper->_flashMessenger->addMessage(array('error' => 'Sorry, no results for search: <b>'. $qw . '</b>'  ));
            $this->_redirect('/' , array('code' => 301));
        }


    }

}
