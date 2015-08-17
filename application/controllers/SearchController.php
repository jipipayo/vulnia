<?php

require_once(APPLICATION_PATH . '../../library/Sphinx/sphinxapi.php');
class SearchController extends Zend_Controller_Action
{

    public function init()
    {
        $this->notifications = $this->_helper->Notifications;
    }


    public function indexAction()
    {
        $this->view->page = $page = strip_tags((int)trim($this->_request->getParam('page')) );
        $this->view->score = $score = strip_tags((int)trim($this->_getParam('score')));
        $this->view->query = $qw = strip_tags( trim($this->_getParam('vulnerabilities')) );
        //keep this query search in zend session to redir after login
        $aNamespace = new Zend_Session_Namespace('Vulnia');
        $aNamespace->lastquery = $this->_request->getParam('vulnerabilities');


        $this->cl = new SphinxClient();
        $this->cl->SetServer('127.0.0.1', 3312);
        $this->cl->SetMatchMode(SPH_MATCH_EXTENDED2);
        $this->cl->SetSortMode(SPH_SORT_EXTENDED, "@id DESC");
        //$this->cl->SetSortMode(SPH_SORT_EXTENDED, "score DESC, @id DESC");
        $this->cl->SetFilterRange( 'score', $score, 10);
        $this->cl->SetMaxQueryTime(1000);

        $itemsPerSphinxPage = 10000;
        $offset = 0;
        $this->cl->SetLimits($offset, $itemsPerSphinxPage);
        $resultSph = $this->cl->Query( $qw, 'vulns');


        if($resultSph === false && $qw){
            die('search engine down');
           // echo '<pre>';
           // var_dump($this->cl);
           // var_dump($resultSph);
           // echo '</pre>';
           // die;
        }

        if( strlen($qw) < 3 ) {
                $this->_helper->_flashMessenger->addMessage(
                array('danger' => 'Sorry, <b>'.$qw.'</b> is less than 3 characters'));
                $this->_redirect('/');
        }elseif (!is_null($resultSph["matches"])) {

            $modelVuln = new Model_Vuln();
            //here we try to not do all the selects, just the selects we need to show the page
            if(!$page){ $page = 1; }
            $res_offset = $page * 10;
            $res_count = 0;
            foreach ($resultSph["matches"] as $doc => $docinfo) {
                $res_count++;
                $resultzs[$doc] = null;
                if( $res_count > $res_offset-10  &&  $res_count <= $res_offset){
                    $resultzs[$doc] = $modelVuln->getVulnById($doc);
                }

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
            $this->_helper->_flashMessenger->addMessage(array('danger' => 'Sorry, no results for search: <b>'. $qw .
                '</b><br>Click <a href="/alert/create">here</a> if you want to setup an email alert about <b>'.$qw.'</b>'  ));
            $this->_redirect('/' , array('code' => 301));
        }


    }

}
