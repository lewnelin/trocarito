<?php
class Db_ACL{

	private $perfilId;
	private $actionId;
	
	public function getActionId() {
		return $this->actionId;
	}
	
	public function getPerfilId() {
		return $this->perfilId;
	}
	
	public function setActionId($actionId) {
		if($actionId instanceof Db_Action)
		$actionId = $actionId->getId();		
		$this->actionId = $actionId;
	}
	
	public function setPerfilId($perfilId) {
		if($perfilId instanceof Db_Perfil)
		$perfilId = $perfilId->getId();
		$this->perfilId = $perfilId;
	}

	
	const TABLE_NAME = "SC_ACL";
	
	public function save(){
		$db = Db::getInstance();
		$insert = "INSERT INTO " . self::TABLE_NAME . " VALUES (?, ?)";
		
		$stmt = $db->prepare($insert);
		
		$param = array($this->getPerfilId(),$this->getActionId());
		return $stmt->execute($param);	
	}
	
	public static function find($perfil, $action){
		if($action instanceof Db_Action) $action = $action->getId();
		if($perfil instanceof Db_Perfil) $perfil = $perfil->getId();
		
		$db = Db::getInstance();
		$stmt = $db->prepare("SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE perfilId = ? AND actionId = ?");
		$stmt->execute(array($perfil,$action));
		$stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		return $stmt->fetch();
	}
	
	public static function deleteAll($perfil){
		$db = Db::getInstance();
		$insert = "DELETE FROM " . self::TABLE_NAME . " WHERE perfilId = ?";
		$stmt = $db->prepare($insert);
		$param = array($perfil);
		return $stmt->execute($param);		
	}

}
