<?php

class TabelaPreco extends Zend_Db_Table_Abstract {

    protected $_name = TB_TABELA_PRECO;

    /**
     * Busca uma tabela de preço pelo ID
     * @param $id
     * @return mixed|Zend_Db_Adapter_Abstract
     */
    public static function getTabelaById($id){
        $tabela = self::getDefaultAdapter();
        $tabela = $tabela->select()->from(array('tp' => TB_TABELA_PRECO))->where('id_tabela_preco = "' . $id . '"')
            ->query()->fetch();

        return $tabela;
    }

    /**
     * Busca a tabela padrão do empreendimento
     * @param $idEmpreendimento
     * @return mixed|Zend_Db_Adapter_Abstract
     */
    public static function getTabelaPadraoByEmpreendimento($idEmpreendimento){
        $tabela = self::getDefaultAdapter();
        $tabela = $tabela->select()->from(array('tp' => TB_TABELA_PRECO))->where('fl_padrao = 1 AND id_empreendimento = "' . $idEmpreendimento . '"')
            ->query()->fetch();

        return $tabela;
    }

    public static function getTabelaPadraoByLote($idLote){
        $tabela = self::getDefaultAdapter();
        $tabela = $tabela->select()->from(array('tp' => TB_TABELA_PRECO))
            ->join(array('tpl' => TB_TABELA_PRECO_LOTES), 'tpl.id_tabela_preco = tp.id_tabela_preco')
            ->where('tp.fl_padrao = 1')
            ->where('tpl.id_lote = ' . $idLote)
            ->query()->fetch();

        return $tabela;
    }
}
