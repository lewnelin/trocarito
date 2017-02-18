<?php

class Representante extends Zend_Db_Table_Abstract {

    protected $_name = TB_REPRESENTANTE;

    /**
     * Salva o representante na tabela
     *
     * @param $dados = informação dos campos e valores
     * @return mixed = retorna <false> caso dê errado ou o ID caso tenha dado certo
     */
    public function saveRepresentante($dados) {

        $row = $this->createRow();

        foreach ($dados as $campo => $valor) {
            $row->$campo = $valor;
        }

        return $row->save();

    }

}

