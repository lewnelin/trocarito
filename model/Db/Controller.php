<?php
class Db_Controller{

	private $id;
	private $rotulo;
	private $nome;
	private $rotina;
	private $moduloId;
	private $log;
	
	const TABLE_NAME = "SC_CONTROLLER";
	
	public function getId() {
		return $this->id;
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function getRotulo() {
		return $this->rotulo;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}
	
	public function setRotulo($rotulo) {
		$this->rotulo = $rotulo;
	}
	
	/**
	 * @param $log the $log to set
	 */
	public function setLog($log) {
		$this->log = $log;
	}
	
	public function getModuloId(){
		return $this->moduloId;
	}
	
	public function setModuloId($moduloId){
		if($moduloId instanceof Db_Modulo) $moduloId = $moduloId->getId();
		$this->moduloId = $moduloId;
	}
	
	public function getRotina(){
		return $this->rotina;
	}
	
	public function setRotina($rotina){
		$this->rotina = $rotina;
	}

	/**
	 * @return the $log
	 */
	public function getLog() {
		return $this->log;
	}
	
	public function save(){
		$db = Db::getInstance();
		$insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (:id, :rotulo, :nome, :rotina, :moduloId, :log)";
		$insert .=" ON DUPLICATE KEY UPDATE rotulo = :rotulo , nome = :nome, rotina = :rotina, moduloId = :moduloId, log = :log";
		
		$stmt = $db->prepare($insert);
		
		$param = array(
			"id" => $this->getId(),
			"rotulo" => $this->getRotulo(),
			"nome" => $this->getNome(),
			"rotina" => $this->getRotina(),
			"moduloId" => $this->getModuloId(),
			"log" => $this->getLog(),
		);
		
		$save = $stmt->execute($param);
		
		if(!$this->getId()) $this->setId($db->lastInsertId());
		return $save;
	}
	
	public static function find($id){
		$db = Db::getInstance();
		$stmt = $db->prepare("SELECT *
		                      FROM " . self::TABLE_NAME . "
		                      WHERE id = ?" );
		$stmt->execute(array($id) );
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetch();
	}

	public static function findByRotina($rotina){
		$db = Db::getInstance();
		$stmt = $db->prepare("SELECT *
		                      FROM " . self::TABLE_NAME . "
		                      WHERE rotina = ?" );
		$stmt->execute(array($rotina));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetch();
	}	
	
	public static function findByName($nome){
		$db = Db::getInstance();
		$stmt = $db->prepare("SELECT *
		                      FROM " . self::TABLE_NAME . "
		                      WHERE nome = ?" );
		$stmt->execute(array($nome));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetch();
	}
	
	public static function fullSearch($termo){
		$result = array();
		$termo = "'%".strtoupper($termo)."%'";
		$db = DB::getInstance();
		$select = "SELECT c.id FROM ".self::TABLE_NAME." c
				   JOIN SC_MODULO m  ON m.id = c.moduloId
				   WHERE CONCAT(UPPER(m.nome),' ',UPPER(c.nome),' ',UPPER(c.rotulo),' ',UPPER(c.rotina)) LIKE {$termo}";
		$stmt = $db->prepare($select);
		$stmt->execute();
		$ids = $stmt->fetchAll();
		if(is_array($ids)){
			foreach ($ids as $id){
				$result[] = self::find($id["id"]);
			}
		}
		return $result;
	}
	
	public static function delete($id){
		$db = Db::getInstance();
		$delete  = "DELETE FROM ".self::TABLE_NAME." 
					WHERE id = ?";
		$stmt = $db->prepare($delete);
		return $stmt->execute(array($id));
	}
	
	public static function fetchAll($modulo = null, $count = false, $offset = false, $page = false){
		$db = Db::getInstance();
		$param = array();

        if($count && $offset === false && $page === false){
            $select = "SELECT count(id) AS count FROM " . self::TABLE_NAME;
        } else {
            $select = "SELECT * FROM " . self::TABLE_NAME;
        }
        
		if($modulo){
			$select .= " WHERE moduloId = ?";
			$param[] = $modulo;
		}

        if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page}";
                
		$stmt = $db->prepare($select);
		$stmt->execute($param);
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetchAll();		
	}

}
