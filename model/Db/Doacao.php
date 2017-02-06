<?php
/**
 * Created by PhpStorm.
 * User: Jefferson Bessa
 * Date: 06/02/2017
 * Time: 14:08
 */

class Doacao extends Zend_Db_Table_Abstract {

      private $id_doacao;
      private $id_instituicao;
      private $id_usuario;
      private $data;
      private $valor;
      private $tipo;

      CONST TABLE_NAME = "Doacao";

    /**
     * @return mixed
     */
    public function getIdDoacao()
    {
        return $this->id_doacao;
    }

    /**
     * @param mixed $id_doacao
     */
    public function setIdDoacao($id_doacao)
    {
        $this->id_doacao = $id_doacao;
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
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return mixed
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param mixed $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }



}