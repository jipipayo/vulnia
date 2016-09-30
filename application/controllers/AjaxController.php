<?php

require_once(APPLICATION_PATH . '../../library/Sphinx/sphinxapi.php');
class AjaxController extends Zend_Controller_Action
{

    public function init(){
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
    }

    public function searchproductAction(){
        $this->query = stripcslashes(strip_tags( trim($this->_getParam('query')) ));

        $this->cl = new SphinxClient();
        $this->cl->SetServer('127.0.0.1', 3312);
        $this->cl->SetMatchMode(SPH_MATCH_EXTENDED2);
//        $this->cl->SetSortMode(SPH_SORT_EXTENDED, "@products DESC");
        $this->cl->SetMaxQueryTime(1000);

        $itemsPerSphinxPage = 100;
        $offset = 0;
        $this->cl->SetLimits($offset, $itemsPerSphinxPage);

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
                $vuln = $modelVuln->getVulnById($doc);
                //fix the dash in the var names, to get the names from js properly
                unset($vuln['ext-id']);
                unset($vuln['origin']);
                unset($vuln['summary']);
                unset($vuln['references']);
                unset($vuln['ext_id']);
                unset($vuln['score']);
                unset($vuln['published-datetime']);
                unset($vuln['last-modified-datetime']);

                $resultzs['items'][] = $vuln;
                ++$resultzs['results_count'];
            }
        }

        echo json_encode($resultzs);



    }

}
