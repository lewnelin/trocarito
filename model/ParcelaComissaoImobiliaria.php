<?php

class ParcelaComissaoImobiliaria extends Zend_Db_Table_Abstract {

    protected $_name = TB_PARCELA_COMISSAO_IMOBILIARIA;

    /**
     * Pesquisa todas as parcelas imobiliaria de um contrato ou em um periodo de datas
     *
     * @param $idContrato = id do contrato
     * @param null $dataDe = Data em que vai ser utilizada para consultar a partir da mesma
     * @param null $dataAte = Data em que vai ser utilizada para consultar a ate da mesma
     * @return array|Zend_Db_Select = retorna lista com todas parcelas
     */
    public function findParcelasImobiliariaByContrato($idContrato) {

        return $this->getAdapter()->select()
            ->from(array('pci' => TB_PARCELA_COMISSAO_IMOBILIARIA), array('vlParcelaSomado' => 'SUM(vl_parcela)', 'nrParcelaSomado' => 'COUNT(nr_parcela)'))
            ->where('pci.id_contrato = ' . $idContrato)
            ->query()->fetchAll();

    }

}