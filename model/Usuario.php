<?php

class Usuario extends Zend_Db_Table_Abstract
{
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

    public function getPerfilId()
    {
        return $this->perfilId;
    }

    public function setPerfilId($perfil)
    {
        if ($perfil instanceof Db_Perfil) $perfil = $perfil->getId();
        $this->perfilId = $perfil;
    }

    /**
     * @return string (9999-99-99)
     */
    public function getDataAltSenha()
    {
        return $this->dataAltSenha;
    }

    /**
     * @param string (9999-99-99) $dataAltSenha
     */
    public function setDataAltSenha($dataAltSenha)
    {
        $this->dataAltSenha = $dataAltSenha;
    }

    /**
     * @return string (9999-99-99)
     */
    public function getDataExpiracao()
    {
        return $this->dataExpiracao;
    }

    /**
     * @param string (9999-99-99) $dataExpiracao
     */
    public function setDataExpiracao($dataExpiracao)
    {
        $this->dataExpiracao = $dataExpiracao;
    }

    /**
     * @return string (HH:mm:ss)
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * @param string (HH:mm:ss) $horaInicio
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;
    }

    /**
     * @return string (HH:mm:ss)
     */
    public function getHoraTermino()
    {
        return $this->horaTermino;
    }

    /**
     * @param string (HH:mm:ss) $horaTermino
     */
    public function setHoraTermino($horaTermino)
    {
        $this->horaTermino = $horaTermino;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if ($id instanceof Db_Pessoa) {
            $id = $id->getId();
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param string $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return boolean (flag)
     */
    public function getSuper()
    {
        return $this->super;
    }

    /**
     * @param int (flag) $super
     */
    public function setSuper($super)
    {
        $this->super = $super;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return ($this->status);
    }

    public static function fetchByLogin($login, $senha)
    {
        $db = DB::getInstance();
        $select = "SELECT * FROM " . self::TABLE_NAME . " WHERE login = :login AND senha = :senha";
        $stmt = $db->prepare($select);
        $stmt->execute(array("login" => $login, "senha" => $senha));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    //Busca o usuário pelo id retornando id e login
    public static function findById($id){
        $usuario = self::getDefaultAdapter()->select()
            ->from(array('u' => TB_USUARIO), array('id', 'login'))
            ->where('id = ?', $id)
            ->query()->fetch();

        return $usuario;
    }
}