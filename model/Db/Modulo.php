<?php
class Db_Modulo {

    private $id;
    private $nome;
    private $path;
    private $flPainelVenda;
    private $log;

    const TABLE_NAME = "SC_MODULO";

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getFlPainelVenda() {
        return $this->fl_painel_venda;
    }

    public function setFlPainelVenda($flPainelVenda) {
        $this->fl_painel_venda = $flPainelVenda;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    /**
     * @param $log the $log to set
     */
    public function setLog($log) {
        $this->log = $log;
    }

    public function getPath() {
        return $this->path;
    }

    /**
     * @return the $log
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * @return the $icon
     */
    public function getIcon() {
        return $this->icon;
    }

    /**
     * @param $icon the $icon to set
     */
    public function setIcon($icon) {
        $this->icon = $icon;
    }

    public function save() {
        $db = Db::getInstance();
        $insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (:id, :nome, :path, :log)";
        $insert .=" ON DUPLICATE KEY UPDATE nome = :nome, path = :path, log = :log ;";

        $stmt = $db->prepare($insert);

        $param = array(
            "id" => $this->getId(),
            "nome" => $this->getNome(),
            "path" => $this->getPath(),
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

    public static function fullSearch($termo, $count = false, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();

        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM ".self::TABLE_NAME."
				   WHERE id <> 1 AND CONCAT(id,' ',UPPER(nome),' ',UPPER(path)) LIKE {$termo}";
        } else {
            $select = "SELECT * FROM ".self::TABLE_NAME."
				   WHERE id <> 1 AND CONCAT(id,' ',UPPER(nome),' ',UPPER(path)) LIKE {$termo} ";
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
        if($count && $offset === false && $page === false) {
            $stmt = $db->prepare("SELECT count(*) AS count FROM " . self::TABLE_NAME." WHERE id <> 1");
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME." WHERE id <> 1 ";
            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page}";
            $stmt = $db->prepare($select);
        }
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    public static function findByPath($path) {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE path = ?");
        $stmt->execute(array($path));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

}