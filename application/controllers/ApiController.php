<?php

require_once(APPLICATION_PATH . '../../library/Sphinx/sphinxapi.php');
class ApiController extends Zend_Rest_Controller
{

    public function init()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();


    }

    public function indexAction(){
        $this->score = (int)trim($this->_getParam('score'));
        $this->date = trim($this->_getParam('date'));
        $this->date = strtotime($this->date);
        $this->query = stripcslashes(strip_tags( trim($this->_getParam('query')) ));


        $this->cl = new SphinxClient();
        $this->cl->SetServer('127.0.0.1', 3312);
        $this->cl->SetMatchMode(SPH_MATCH_EXTENDED2);
        $this->cl->SetSortMode(SPH_SORT_EXTENDED, "@id DESC");
        //$this->cl->SetSortMode(SPH_SORT_EXTENDED, "score DESC, @id DESC");
        $this->cl->SetMaxQueryTime(1000);

        $itemsPerSphinxPage = 100;
        $offset = 0;
        $this->cl->SetLimits($offset, $itemsPerSphinxPage);
        $this->cl->SetFilterRange( 'score', $this->score, 10);
        $today = strtotime (date('Y-m-d H:i:s'));

        $this->cl->SetFilterRange( 'published-datetime', $this->date, $today, $exclude=false);
        $resultSph = $this->cl->Query( $this->query, 'vulns');

        $resultzs['results_count'] = 0;
        if($resultSph === false && $this->query){
            $resultzs['api_status'] = 'ko';
            $resultzs['message'] = $this->cl->GetLastError();
        } else { //success sphinx so lets fetch data from db
            //var_dump($resultSph);
            $modelVuln = new Model_Vuln;
            $resultzs['api_status'] = 'ok';
            foreach ($resultSph["matches"] as $doc => $docinfo) {
                    $resultzs['items'][$doc] = $modelVuln->getVulnById($doc);
                    ++$resultzs['results_count'];
            }
        }

        echo json_encode( $resultzs);



    }

    public function headAction(){
        $this->_forward('index');
    }

    public function getAction(){
        $this->_forward('index');
    }

    public function postAction(){
        $this->_forward('index');
    }
    public function putAction(){
        $this->_forward('index');
    }
    public function deleteAction(){
        $this->_forward('index');
    }
}
