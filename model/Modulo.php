<?php
class Modulo extends Zend_Db_Table_Abstract
{

    protected $_name = "SC_MODULO";
    const Table = 'SC_MODULO';

    public static function findId($id) {
        return self::findById($id,self::Table);
    }

}