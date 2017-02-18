<?php
class Db_Declaracoes {

    private $id;
    private $assunto;
    private $texto;
    

    const TABLE_NAME = "DECLARACOES";

    public function setPrivate($private) {
        $this->private = $private;
    }
    /**
     * @param $log the $log to set
     */
    public function setTexto($texto) {
        $this->texto = $texto;
    }

    /**
     * @return the $log
     */
    public function getTexto() {
        return $this->texto;
    }

    /**
     * @param string $uf
     */
    public function setAssunto($assunto) {
        $this->assunto = $assunto;
    }

    /**
     * @return string $uf
     */
    public function getAssunto() {
        return $this->assunto;
    }


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }


   public function save() {
        $db = Db::getInstance();
        $insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (:id, :assunto, :texto)";
        $insert .=" ON DUPLICATE KEY UPDATE assunto = :assunto , texto = :texto";

        $stmt = $db->prepare($insert);

        $param = array(
                "id" => $this->getId(),
                "assunto" => $this->getAssunto(),
                "texto" => $this->getTexto(),
        );
        $save = $stmt->execute($param);
        if(!$this->getId()) $this->setId($db->lastInsertId());
        return $save;
    }

    /**
     *
     * @param int $id
     * @return Db_Cidade
     */
    public static function find($id) {
        $db = Db::getInstance();
        $filtro = array($id);
        $select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE id = ?";
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    /**
     *
     * @param string $nome
     * @return Db_Cidade
     */
    public static function fullSearch($termo, $count = false, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();

        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM ".self::TABLE_NAME."
				   WHERE CONCAT(UPPER(assunto),' ',UPPER(texto)) LIKE {$termo}";
        } else {
            $select = "SELECT * FROM ".self::TABLE_NAME."
				   WHERE CONCAT(UPPER(assunto),' ',UPPER(texto)) LIKE {$termo} ";

            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";
        }

        $stmt = $db->prepare($select);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);

        return $stmt->fetchAll();
    }
 
    public static function delete($id) {
        $db = Db::getInstance();
        $delete  = "DELETE FROM ".self::TABLE_NAME."
					WHERE id = ?";
        $stmt = $db->prepare($delete);
        return $stmt->execute(array($id));
    }

    public static function fetchAll($where = null,$orderBy = null, $count = false, $offset = false, $page = false) {
        $db = Db::getInstance();
        $filtro = array();

        if($count && $offset == false && $page == false) {
            $select = "SELECT count(*) AS count FROM " . self::TABLE_NAME;
            if($where)
                $select .= " WHERE {$where}";
            if($orderBy)
                $select .= " ORDER BY {$orderBy}";
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME;
            if($where)
                $select .= " WHERE {$where}";

            if($orderBy)
                $select .= " ORDER BY {$orderBy}";

            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";

        
        }
        
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    public function toArray() {
        $array = array();
        $vars = get_class_vars(__CLASS__);
        foreach($vars as $k => $v)
            $array[$k] = $this->$k;
        return $array;
    }

}
