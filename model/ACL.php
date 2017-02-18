<?php
class ACL extends Zend_Db_Table_Abstract {

    protected $_name = "SC_ACL";

    public static function hasPermission($usuario, $action){
        if(!$usuario instanceof Db_Usuario)
        return true;
        else
        return false;
    }
}