<?php

class Cidade extends Zend_Db_Table_Abstract
{

    protected $_name = TB_CIDADE;

    /**
     * Lista todas cidades
     *
     * @return array
     */
    public function listar()
    {

        return $this->getDefaultAdapter()->select()->from(array('c' => TB_CIDADE), array('*'))->query()->fetchAll();

    }

    /**
     * Busca todas informações da cidade pelo ID
     *
     * @param $idCidade
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function getCidadeById($idCidade)
    {
        return $this->fetchRow('id = ' . $idCidade);
    }

}