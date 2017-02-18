<?php

class TabelaAgrupada extends Zend_Db_Table_Abstract
{

    protected $_name = TB_AGRUPADA;

    /**
     * Retorna lista de uma das tabelas agrupadas através do codigo da tabela
     *
     * @param $codigo
     * @return array
     */
    public function getTabelaByCodigo($codigo)
    {
        return $this->getDefaultAdapter()->select()
            ->from(array('c' => TB_AGRUPADA), array('*'))
            ->where('idTabela = ' . $codigo)
            ->where('fixo = 1')
            ->query()->fetchAll();

    }

}
