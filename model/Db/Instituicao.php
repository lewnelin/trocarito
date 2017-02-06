<?php
/**
 * Created by PhpStorm.
 * User: Jefferson Bessa
 * Date: 06/02/2017
 * Time: 13:55
 */

class Instituicao extends  Zend_Db_Table_Abstract {

    private $id_instituicao;
    private $nome;
    private $perfil;
    private $descricao;

    CONST TABLE_NAME = "instituicao";

    /**
     * @return mixed
     */
    public function getIdInstituicao()
    {
        return $this->id_instituicao;
    }

    /**
     * @param mixed $id_instituicao
     */
    public function setIdInstituicao($id_instituicao)
    {
        $this->id_instituicao = $id_instituicao;
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
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * @param mixed $perfil
     */
    public function setPerfil($perfil)
    {
        $this->perfil = $perfil;
    }


}