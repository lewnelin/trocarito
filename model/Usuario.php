<?php

class Usuario extends Zend_Db_Table_Abstract
{
    private $id_usuario;
    private $email;
    private $senha;
    private $nome;
    private $nv_caridade;

    CONST TABLE_NAME = "usuario";

    /**
     * @return mixed
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param mixed $id_usuario
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getNvCaridade()
    {
        return $this->nv_caridade;
    }

    /**
     * @param mixed $nv_caridade
     */
    public function setNvCaridade($nv_caridade)
    {
        $this->nv_caridade = $nv_caridade;
    }

    public static function fetchByLogin($login, $senha)
    {
        $db = DB::getInstance();
        $select = "SELECT * FROM " . self::TABLE_NAME . " WHERE email = :email AND senha = :senha";
        $stmt = $db->prepare($select);
        $stmt->execute(array("email" => $login, "senha" => $senha));
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function getMaiorDoador(){
        $usuarios = self::getDefaultAdapter()->select()
            ->from(array('u' => 'usuario'), array('id_usuario','nome','nv_caridade'))
            ->join(array('d' => 'doacao'),'d.id_usuario = u.id_usuario',array('valorDoado'=>'SUM(d.valor)'))
            ->group('id_usuario')
            ->order('valorDoado')
            ->query()->fetchAll();

        return $usuarios[0];
    }

    //Busca o usu�rio pelo id retornando seus dados
    public static function findById($id)
    {
        $usuario = self::getDefaultAdapter()->select()
            ->from(array('u' => TB_USUARIO), array('*'))
            ->where('id_usuario = ?', $id)
            ->query()->fetch();

        return $usuario;
    }
}