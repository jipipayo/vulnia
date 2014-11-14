<?php
class Model_Alert extends Zend_Db_Table_Abstract
{
    public function init(){
        $this->table = new Zend_Db_Table('alerts');
    }


    public function save(array $data)
    {
        $fields = $this->table->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset ($data [$field]);
            }
        }
        return $this->table->insert($data);
    }


    public function update(array $data)
    {
        $where = $this->table->getAdapter()->quoteInto('id= ?', (int)$data ['id']);
        $this->table->update($data, $where);
    }



    public function fetchAlertById(string $id)
    {
        $select = $this->table->select()->where('id = ?', (int)$id);

        if ($select != null) {
            $result = $this->table->fetchRow($select);
        } else {
            $result = null;
        }
        return $result;
    }

    public function fetchAlertByQueryAndUserId( int $userid, string $query)
    {
        $select = $this->table->select()
                              ->where('user_id_owner = ?', $userid)
                              ->where('query = ?', $query);

        if ($select != null) {
            $result = $this->table->fetchRow($select);
        } else {
            $result = null;
        }
        return $result;
    }

    public function deleteAlert(int $id)
    {
        $this->table->delete('id =' . (int)$id);
    }


}
