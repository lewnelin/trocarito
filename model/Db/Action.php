<?php
class Db_Action {

    private $id;
    private $nome;
    private $controllerId;
    private $private;
    private $log;

    const TABLE_NAME = "SC_ACTION";

    public function getPrivate() {
        return $this->private;
    }

    public function setPrivate($private) {
        $this->private = $private;
    }

    public function getControllerId() {
        return $this->controllerId;
    }

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

    public function setControllerId($controllerId) {
        if($controllerId instanceof Db_Controller) $controllerId = $controllerId->getId();
        $this->controllerId = $controllerId;
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
        $insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (:id, :nome, :controllerId, :private, :log)";
        $insert .=" ON DUPLICATE KEY UPDATE id = :id , nome = :nome , controllerId = :controllerId, private = :private, log = :log;";

        $stmt = $db->prepare($insert);

        $param = array(
                "id" => $this->getId(),
                "nome" => $this->getNome(),
                "controllerId" => $this->getControllerId(),
                "private" => $this->getPrivate(),
                "log" => $this->getLog(),
        );
        return $stmt->execute($param);
    }

    public static function find($id, $controllerId = null) {
        $db = Db::getInstance();
        $filtro = array($id);
        $select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE id = ?";
        if($controllerId) {
            $select .= " AND controllerId = ?";
            $filtro[] = $controllerId;
        }
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function fullSearch($termo, $count = false, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();
        if($count && $offset === false && $page === false) {
            $select = "SELECT count(a.id) AS count FROM ".self::TABLE_NAME." a
				   JOIN SC_CONTROLLER c  ON c.id = a.controllerId
				   WHERE CONCAT(UPPER(a.nome),' ',UPPER(c.nome)) LIKE {$termo}";
        } else {
            $select = "SELECT a.id FROM ".self::TABLE_NAME." a
				   JOIN SC_CONTROLLER c  ON c.id = a.controllerId
				   WHERE CONCAT(UPPER(a.nome),' ',UPPER(c.nome)) LIKE {$termo} ";
                   if($offset !== false && $page !== false)
                       $select .= " LIMIT {$offset}, {$page}";
        }
        $stmt = $db->prepare($select);
        $stmt->execute();
        $ids = $stmt->fetchAll();
        if($count && $offset === false && $page === false) return  $ids;
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $result[] = self::find($id["id"]);
            }
        }
        return $result;
    }

    public static function findByNome($nome, $controllerId = null) {
        $db = Db::getInstance();
        $filtro = array($nome);
        $select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE nome = ?";
        if($controllerId) {
            $select .= " AND controllerId = ?";
            $filtro[] = $controllerId;
        }
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function delete($id) {
        $db = Db::getInstance();
        $delete  = "DELETE FROM ".self::TABLE_NAME."
					WHERE id = ?";
        $stmt = $db->prepare($delete);
        return $stmt->execute(array($id));
    }

    public static function fetchAll($controller = null, $count = false, $offset = false, $page = false) {
        if($controller instanceof Db_Controller) {
            $controller = $controller->getId();
        }
        $db = Db::getInstance();
        $filtro = array();
        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM " . self::TABLE_NAME;
            if($controller) {
                $select .= " WHERE controllerId = ? ";
                $filtro[] = $controller;
            }
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME;
            if($controller) {
                $select .= " WHERE controllerId = ? ";
                $filtro[] = $controller;
            }
            if($offset !== false && $page !== false)
            $select .= " LIMIT {$offset}, {$page}";
        }

        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }



}