<?php

class ModelBase extends Zend_Db_Table_Abstract
{

    public static function findById($id, $tableName)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . $tableName . "
		                      WHERE id = ?");
        $stmt->execute(array($id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function findByName($nome, $tableName)
    {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT *
		                      FROM " . $tableName . "
		                      WHERE nome = ?" );
        $stmt->execute(array($nome));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

}