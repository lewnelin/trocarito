<?php

class CorretorEmpreendimento extends Zend_Db_Table_Abstract
{

    protected $_name = TB_CORRETOR_EMPREENDIMENTO;

    /**
     * Seleciona um ou varios corretor empreendimento de um empreendimento
     *
     * @param $idEmpreedimento = id do empreendimento
     * @return array = lista com informações de todos corretores empreendimento
     * false = caso não tenha encontrado nada
     */
    public function getCorretoresByIdEmpreendimento($idEmpreedimento, $idCorretor = false)
    {
        $corretores = $this->getDefaultAdapter()->select()
            ->from(array('ce' => TB_CORRETOR_EMPREENDIMENTO), array('id_corretor', 'id_empreendimento'))
            ->join(array('p' => TB_PESSOA), 'ce.id_corretor = p.id', array('nm_pessoa'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'ce.id_empreendimento = e.id', array('nm_empreendimento', 'id'))
            ->where('e.id = ' . $idEmpreedimento);

        if($idCorretor)
            $corretores->where('ce.id_corretor = ?',$idCorretor);

        return $corretores->query()->fetchAll();
    }

    /**
     * Seleciona um corretor empreendimento de um corretor especifico
     *
     * @param $idCorretor
     * @return array = lista com informações do corretor empreendimento
     * false = caso não tenha encontrado nada
     */
    public function findCorretorEmpreendimento($idCorretor, $idEmpreendimento)
    {
        return ($idCorretor) ? $this->fetchRow('id_corretor = ' . $idCorretor . ' AND id_empreendimento = ' . $idEmpreendimento . ' AND id_imobiliaria is not null') : false;
    }

    /**
     * Seleciona um ou varios corretor empreendimento de um empreendimento
     *
     * @param $idEmpreedimento = id do empreendimento
     * @return array = lista com informações de todos corretores empreendimento
     * false = caso não tenha encontrado nada
     */
    public static function getCorretorEmpreendimentoDados($idCorretor, $idEmpreedimento)
    {
        return self::getDefaultAdapter()->select()
            ->from(array('ce' => TB_CORRETOR_EMPREENDIMENTO), array('*'))
            ->join(array('p' => TB_PESSOA), 'ce.id_corretor = p.id', array('nm_pessoa'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'ce.id_empreendimento = e.id', array('nm_empreendimento', 'id'))
            ->where('e.id = ' . $idEmpreedimento)
            ->where('ce.id_corretor = ' . $idCorretor)
            ->query()->fetchAll();
    }

    /**
     * Monta um array com os tipos de parcela do corretor pelo empreendimento
     * @param $id
     * @param $idEmpreendimento
     * @return array
     */
    public static function getArrayTiposParcela($id, $idEmpreendimento)
    {
        $corretorEmpreendimento = self::getDefaultAdapter()->select()
            ->from(array('ce' => TB_CORRETOR_EMPREENDIMENTO), array('*'))
            ->where('ce.id_corretor = ?', $id)
            ->where('ce.id_empreendimento = ?', $idEmpreendimento)
            ->query()->fetch();

        $arrayParcelas = array();
        if ($corretorEmpreendimento['fl_parcela_normal'] == '1')
            $arrayParcelas[] = 'N';
        if ($corretorEmpreendimento['fl_parcela_sinal'] == '1')
            $arrayParcelas[] = 'S';
        if ($corretorEmpreendimento['fl_parcela_intercalada'] == '1')
            $arrayParcelas[] = 'I';
        if ($corretorEmpreendimento['fl_parcela_chave'] == '1')
            $arrayParcelas[] = 'C';
        if ($corretorEmpreendimento['fl_parcela_negociada'] == '1')
            $arrayParcelas[] = 'G';
        if ($corretorEmpreendimento['fl_parcela_quitacao'] == '1')
            $arrayParcelas[] = 'Q';

        return $arrayParcelas;
    }

}