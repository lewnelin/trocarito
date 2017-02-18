<?php
class Db_Usuario {
    private $id;
    private $login;
    private $senha;
    private $horaInicio;
    private $horaTermino;
    private $dataExpiracao;
    private $dataAltSenha;
    private $super;
    private $log;
    private $status;
    private $perfilId;

    CONST TABLE_NAME = "SC_USUARIO";

    public function getPerfilId() {
        return $this->perfilId;
    }

    public function setPerfilId($perfil) {
        if($perfil instanceof Db_Perfil) $perfil = $perfil->getId();
        $this->perfilId = $perfil;
    }
    /**
     * @return string (9999-99-99)
     */
    public function getDataAltSenha() {
        return $this->dataAltSenha;
    }

    /**
     * @param string (9999-99-99) $dataAltSenha
     */
    public function setDataAltSenha($dataAltSenha) {
        $this->dataAltSenha = $dataAltSenha;
    }

    /**
     * @return string (9999-99-99)
     */
    public function getDataExpiracao() {
        return $this->dataExpiracao;
    }

    /**
     * @param string (9999-99-99) $dataExpiracao
     */
    public function setDataExpiracao($dataExpiracao) {
        $this->dataExpiracao = $dataExpiracao;
    }

    /**
     * @return string (HH:mm:ss)
     */
    public function getHoraInicio() {
        return $this->horaInicio;
    }

    /**
     * @param string (HH:mm:ss) $horaInicio
     */
    public function setHoraInicio($horaInicio) {
        $this->horaInicio = $horaInicio;
    }

    /**
     * @return string (HH:mm:ss)
     */
    public function getHoraTermino() {
        return $this->horaTermino;
    }

    /**
     * @param string (HH:mm:ss) $horaTermino
     */
    public function setHoraTermino($horaTermino) {
        $this->horaTermino = $horaTermino;
    }

    /**
     * @return int
     */
    public function getId() {
        return (int) $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        if($id instanceof Db_Pessoa) {
            $id = $id->getId();
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * @param string $log
     */
    public function setLog($log) {
        $this->log = $log;
    }

    /**
     * @return string
     */
    public function getLogin() {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login) {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getSenha() {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha) {
        $this->senha = $senha;
    }

    /**
     * @return boolean (flag)
     */
    public function getSuper() {
        return $this->super;
    }

    /**
     * @param int (flag) $super
     */
    public function setSuper($super) {
        $this->super = $super;
    }

    /**
     * @param int $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function getStatus() {
        return ($this->status);
    }

    public static function find($id) {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT *
		                      FROM ".self::TABLE_NAME."
		                      WHERE id = ?");
        $stmt->execute(array($id));
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Db_Usuario");
        return $stmt->fetch();
    }

    public static function findAllId() {
        $db = Db::getInstance();
        $stmt = $db->prepare("SELECT id FROM ".self::TABLE_NAME);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_COLUMN,"Db_Usuario");
        return $stmt->fetchAll();
    }

    public static function findByField($campo, $valor) {
        $db = Db::getInstance();
        $filtro = array($valor);
        $select = "SELECT * FROM " . self::TABLE_NAME . "
		                      WHERE {$campo} = ?";
        $stmt = $db->prepare($select);
        $stmt->execute($filtro);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    public static function delete($id) {
        $db = Db::getInstance();
        $delete  = "UPDATE ".self::TABLE_NAME." SET status = '0' WHERE id = ?";
        $stmt = $db->prepare($delete);
        return $stmt->execute(array($id));
    }

    public static function fetchByLogin($login, $senha) {
        $db = DB::getInstance();
        $select = "SELECT * FROM ".self::TABLE_NAME." WHERE login = :login AND senha = :senha";
        $stmt = $db->prepare($select);
        $stmt->execute(array("login" => $login, "senha" => $senha));
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        return $stmt->fetch();
    }

    public static function fullSearch($termo, $count = false, $offset = false, $page = false) {
        $result = array();
        $termo = "'%".strtoupper($termo)."%'";
        $db = DB::getInstance();
        if($count && $offset === false && $page === false) {
            $select = "SELECT count(u.id) FROM ".self::TABLE_NAME." u
				   JOIN ".TB_PESSOA." p  ON u.id = p.id
				   WHERE CONCAT(u.id,' ',UPPER(p.nm_pessoa),' ',UPPER(u.login)) LIKE {$termo} AND u.status = '1'";
        } else {
            $select = "SELECT u.id FROM ".self::TABLE_NAME." u
				   JOIN ".TB_PESSOA." p  ON u.id = p.id
				   WHERE CONCAT(u.id,' ',UPPER(p.nm_pessoa),' ',UPPER(u.login)) LIKE {$termo} AND u.status = '1' ";
            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";
        }

        $stmt = $db->prepare($select);
        $stmt->execute();
        $ids = $stmt->fetchAll();

        if($count && $offset === false && $page === false) return $ids;

        if(is_array($ids)) {
            foreach ($ids as $id) {
                $result[] = self::find($id["id"]);
            }
        }
        return $result;
    }

    public static function fetchAll($count = false, $offset = false, $page = false) {
        $db = DB::getInstance();
        if($count && $offset === false && $page === false) {
            $select = "SELECT count(*) AS count FROM ".self::TABLE_NAME." WHERE status = '1'";
        } else {
            $select = "SELECT * FROM ".self::TABLE_NAME." WHERE status = '1' ";
            if($offset !== false && $page !== false)
                $select .= " LIMIT {$offset}, {$page} ";
        }
        $stmt = $db->prepare($select);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Db_Usuario");
        return $stmt->fetchAll();
    }

    public static function loginIsAvailable($login) {
        $db = DB::getInstance();
        $select = "SELECT * FROM ".self::TABLE_NAME." WHERE login = :login";
        $stmt = $db->prepare($select);
        $stmt->execute(array("login" => $login));
        $stmt->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        return ($stmt->fetch() == NULL);
    }

    /**
     * @return bool
     */
    public function save() {
        $db = Db::getInstance();
        $insert = "INSERT INTO ".self::TABLE_NAME." VALUES
		(:id, :login, :senha, :horaInicio, :horaTermino, :dataExpiracao, :dataAltSenha, :super, :status, :log, :perfilId) 
		ON DUPLICATE KEY UPDATE login = :login, senha = :senha, horaInicio = :horaInicio, horaTermino = :horaTermino, dataExpiracao = :dataExpiracao, dataAltSenha = :dataAltSenha, super = :super, status = :status, log = :log, perfilId = :perfilId;";

        $stmt = $db->prepare($insert);
        $param = array(
                "id" 				=> $this->getId(),
                "login" 			=> $this->getLogin(),
                "senha"				=> $this->getSenha(),
                "horaInicio"     	=> $this->getHoraInicio(),
                "horaTermino" 	    => $this->gethoraTermino(),
                "dataExpiracao"		=> $this->getDataExpiracao(),
                "dataAltSenha" 	    => $this->getDataAltSenha(),
                "super"				=> $this->getSuper(),
                "log"				=> $this->getLog(),
                "status"            => $this->getStatus(),
                "perfilId"			=> $this->getPerfilId(),
        );

        $save = $stmt->execute($param);
        if(!$this->getId()) $this->setId($db->lastInsertId());
        return $this->getId();
    }



    public static function createBy(Pessoa_Fisica $pf, Db_Perfil $perfil) {

        return (rand(0,9) % 2 == 0);

    }
}