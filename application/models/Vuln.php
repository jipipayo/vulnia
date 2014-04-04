<?php
class Model_Vuln {
    private  $_table = 'vulns';


    public function getVulnsDesc(){
        $table =  new Zend_Db_Table($this->_table);
        return $table->select()->order('id DESC');
    }


    public function getVulnById($id){
        $id = (int)$id;
        $table =  new Zend_Db_Table($this->_table);
        $query = "SELECT * FROM vulns WHERE id= " . $id;
        return $table->getAdapter()->query($query)->fetchAll();
    }
}
