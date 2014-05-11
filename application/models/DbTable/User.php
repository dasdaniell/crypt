<?php


class Model_DbTableLocal_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'nl_users';
    protected $_primary = 'userID';

    function init()
    {
        $this->_setAdapter('db');// set the database connection
    }
    public function save($data, $id = 0)
    {
        if ($id == 0) {
            return $this->insert($data);
        } else {
            $where = $this->getAdapter()->quoteInto('`userID` = ?', $id);
            return $this->update($data, $where);
        }
    }
    public function remove($id)
    {
        $where = $this->getAdapter()->quoteInto('`userID` = ?', $id);
        return $this->delete($where);// Zend_Db_Table_Abstract method
    }
    public function getOne($id)
    {
        $select = $this->select();
        $select ->from($this->_name)
                ->where('`userID` = ?', $id);
        $result = $this->fetchAll($select);
        if($result && count($result) === 1) {
            $row = $result->toArray();
            return $row[0];
        }
        return false;
    }

}