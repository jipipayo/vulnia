<?php
class Model_Search {

    private  $_table =  new Zend_Db_Table('searches');

    public function saveCountSearch($query,$count){
        $sql = "INSERT INTO searches ( query, count ) VALUES  ( ?,? )
                            ON DUPLICATE KEY UPDATE query = ?, count = ?";
        $values = array("query"=>$query, "count"=>$count);
        $this->_table->getAdapter()->query($sql, array_merge(array_values($values), array_values($values)));
    }


    public function getMostSearched($limit=20){
        $limit = (int)$limit;
        $query = "SELECT * FROM searches ORDER BY count DESC  LIMIT " . $limit;
        return $this->table->getAdapter()->query($query)->fetchAll();
    }
}
