<?php

class ParcelaComissaoCorretor extends Zend_Db_Table_Abstract {

    protected $_name = TB_PARCELA_COMISSAO_CORRETOR;

    /**
     * Pesquisa todas as parcelas corretor de um contrato ou em um periodo de datas
     *
     * @param $idContrato = id do contrato
     * @param null $dataDe = Data em que vai ser utilizada para consultar a partir da mesma
     * @param null $dataAte = Data em que vai ser utilizada para consultar a ate da mesma
     * @return array|Zend_Db_Select = retorna lista com todas parcelas
     */
    public function findParcelasCorretorByContrato($idContrato, $dataDe = null, $dataAte = null) {

        return $this->getAdapter()->select()
            ->from(array('pcc' => TB_PARCELA_COMISSAO_CORRETOR), array('vlParcelaSomado' => 'SUM(vl_parcela)', 'nrParcelaSomado' => 'COUNT(nr_parcela)'))
            ->where('pcc.id_contrato = ' . $idContrato)
            ->query()->fetchAll();

    }

}