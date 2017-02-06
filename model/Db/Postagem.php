<?php
/**
 * Created by PhpStorm.
 * User: Jefferson Bessa
 * Date: 06/02/2017
 * Time: 14:12
 */

class Postagem extends Zend_Db_Table_Abstract {

    private $id_postagem;
    private $id_instituicao;
    private $conteudo;

    CONST TABLE_NAME = "Postagem";

    /**
     * @return mixed
     */
    public function getIdPostagem()
    {
        return $this->id_postagem;
    }

    /**
     * @param mixed $id_postagem
     */
    public function setIdPostagem($id_postagem)
    {
        $this->id_postagem = $id_postagem;
    }

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
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * @param mixed $conteudo
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }

}