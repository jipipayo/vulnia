<?php
class Model_Vuln extends Zend_Db_Table_Abstract {

    public function init(){
        $this->table =  new Zend_Db_Table('vulns');
    }


    public function getVulnsDesc(){
        return $this->table->select()->order('id DESC');
    }


    public function getVulnById($id){
        $id = (int)$id;
        $query = "SELECT * FROM vulns WHERE id= " . $id;
        return $this->table->getAdapter()->query($query)->fetchAll();
    }
}
