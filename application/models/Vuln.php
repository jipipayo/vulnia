<?php
class Model_Vuln {
    private  $_table = 'vulns';


    public function getVulnsDesc(){
        $cves =  new Zend_Db_Table($this->_table);
        return $cves->select()->order('id DESC');
    }


    public function getVulnById($id){
        $id = (int)$id;
        $vuln =  new Zend_Db_Table($this->_table);
        $query = "SELECT * FROM vulns WHERE id= " . $id;
        $result = $vuln->getAdapter()->query($query)->fetchAll();

        return $result;
    }
}
