<?php

class ContratoPessoa extends Zend_Db_Table_Abstract {

    protected $_name = TB_CONTRATO_PESSOA;

    /**
     * Adiciona um contrato pesoa na tabela
     *
     * @param $dados = array com campos e valores para serem adicionados
     * @return mixed
     */
    public function addContratoPessoa($dados) {

        $row = $this->createRow();

        foreach ($dados as $campo => $valor) {
            $row->$campo = $valor;
        }

        return $row->save();

    }

}