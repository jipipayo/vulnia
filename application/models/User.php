<?php

class Model_User
{

    private $table;


    public function save(array $data)
    {
        $table = new Zend_Db_Table('users');
        $fields = $table->info(Zend_Db_Table_Abstract::COLS);
        foreach ($data as $field => $value) {
            if (!in_array($field, $fields)) {
                unset ($data [$field]);
            }
        }
        return $table->insert($data);
    }


    public function update(array $data)
    {
        $table = new Zend_Db_Table('users');
        $where = $table->getAdapter()->quoteInto('id= ?', (int)$data ['id']);
        $table->update($data, $where);

    }

    public function checkEmail($email)
    {
        $table = new Zend_Db_Table('users');
        $select = $table->select()->where('email = ?', $email);
        return $table->fetchRow($select);
    }


    public function getToken($email)
    {
        $table = new Zend_Db_Table('users');
        $select = $table->select()->where('email = ?', $email);
        return $table->fetchRow($select)->token;
    }

    public function isActiveByEmail($email)
        {
            $table = new Zend_Db_Table('users');
            $select = $table->select()->where('email = ?', $email);
            return $table->fetchRow($select)->active;
        }


    public function validateToken($token)
    {
        $table = new Zend_Db_Table('users');
        $select = $table->select()->where('token = ?', $token);
        return $table->fetchRow($select);
    }


    public function checkIsLocked($id)
    {
        $table = new Zend_Db_Table('users');
        $select = $table->select()->where('id = ?', (int)$id);
        return $table->fetchRow($select)->locked;
    }


    public function checkLockedUser($id)
    {
        $table = new Zend_Db_Table('users');
        $select = $table->select('locked')->where('id = ?', (int)$id);
        return $table->fetchRow($select)->locked;
    }


    public function fetchUser($id)
    {
        $table = new Zend_Db_Table('users');
        $select = $table->select()->where('id = ?', (int)$id);

        if ($select != null) {
            $result = $table->fetchRow($select);
            //do not return password and token ever!
            unset($result['password']);
            unset($result['token']);
        } else {
            $result = null;
        }

        return $result;
    }


    public function deleteUser($id)
    {
        $table = new Zend_Db_Table('users');
        $table->delete('id =' . (int)$id);
    }


}
