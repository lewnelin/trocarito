<?php
class Db_Perfil {

    private $id;
    private $nome;
    private $log;

    const TABLE_NAME = "SC_PERFIL";
    const PERFIL_ADMIN = '1';
    const PERFIL_GERENTE = '4';
    const PERFIL_EMPREENDEDOR = '5';
    const PERFIL_SECRETARIA = '6';
    const PERFIL_COORDENADOR = '7';

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    /**
     * @return the $log
     */
    public function getLog() {
        return $this->log;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    /**
     * @param $log the $log to set
     */
    public function setLog($log) {
        $this->log = $log;
    }

    public function save() {
        $db = Db::getInstance();
        $insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (:id, :nome, :log)";
        $insert .=" ON DUPLICATE KEY UPDATE nome = :nome, log = :log;";

        $stmt = $db->prepare($insert);

        $param = array(
                "id" => $this->getId(),
                "nome" => $this->getNome(),
                "log" => $this->getLog(),
        );
        $save = $stmt->execute($param);
        if(!$this->getId()) $this->setId($db->lastInsertId());
        return $save;
    }

    public static function find($id) {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE id = ?");
        $stmt->execute(array($id));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function fullSearch($termo, $count = fasle, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();
        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM ".self::TABLE_NAME."
				   WHERE CONCAT(id,' ',UPPER(nome)) LIKE {$termo}";
        } else {
            $select = "SELECT * FROM ".self::TABLE_NAME."
				   WHERE CONCAT(id,' ',UPPER(nome)) LIKE {$termo} ";
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

    public static function fetchAll($count = false, $offset = false, $page = false) {
        $db = Db::getInstance();
        if($count && $offset == false && $page == false) {
            $stmt = $db->prepare("SELECT count(*) AS count FROM " . self::TABLE_NAME);
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME;
            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page}";
            $stmt = $db->prepare($select);
        }
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

}