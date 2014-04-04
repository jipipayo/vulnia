<?php
class Model_Search {
    private  $_table = 'searches';

    public function saveCountSearch($query,$count){
        $table =  new Zend_Db_Table($this->_table);

        $sql = "INSERT INTO searches ( query, count ) VALUES  ( ?,? )
                            ON DUPLICATE KEY UPDATE query = ?, count = ?";

        $values = array("query"=>$query, "count"=>$count);
        $table->getAdapter()->query($sql, array_merge(array_values($values), array_values($values)));
    }


    public function getMostSearched($limit=20){
        $limit = (int)$limit;
        $table =  new Zend_Db_Table($this->_table);
        $query = "SELECT * FROM searches ORDER BY count DESC  LIMIT " . $limit;
        return $table->getAdapter()->query($query)->fetchAll();
    }
}
