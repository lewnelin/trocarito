<?php

class Contrato extends Zend_Db_Table_Abstract {

    protected $_name = TB_CONTRATO;
    static $contratoInstacia;

    /**
     * @return Contrato
     */
    public static function getInstance()
    {
        if (!self::$contratoInstacia) {
            self::$contratoInstacia = new Contrato();
        }
        return self::$contratoInstacia;
    }

    /**
     * Recebe o where e executa a busca na tabela de contrato
     *
     * @param $where
     * @return null|Zend_Db_Table_Row_Abstract
     */
    private function findContrato($where) {

        return $this->fetchRow($where);

    }

    public static function getNextMonth($data, $level = 1, $monthInc = true) {
        $data = explode('-', $data);
        $data['year'] = (int) $data[0];
        $data['month'] = (int) $data[1];
        $data['day'] = (int) $data[2];

        if (!$data['year'] || !$data['month'] || !$data['day'])
            return false;

        if ($monthInc)
            $data['month'] = $data['month'] + $level;
        while ($data['month'] > 12) {
            $data['month'] = $data['month'] - 12;
            $data['year'] = $data['year'] + 1;
        }
        if (checkdate($data['month'], $data['day'], $data['year'])) {
            $month = str_pad($data['month'], 2, '0', STR_PAD_LEFT);
            $day = str_pad($data['day'], 2, '0', STR_PAD_LEFT);
            $year = str_pad($data['year'], 2, '0', STR_PAD_LEFT);
            return $year . '-' . $month . '-' . $day;
        } else {
            $data['day'] = $data['day'] - 1;
            $d = $data['year'] . '-' . $data['month'] . '-' . $data['day'];
            return self::getNextMonth($d, $level, false);
        }
    }

    public static function getParcelas($dtInicialParcela, $qtdParcela, $frequencia = 1) {

        if (!$dtInicialParcela && !$qtdParcela) {
            return false;
        } else if ($frequencia < 1) {
            return false;
        }

        if (strstr($dtInicialParcela, '/')) {
            $dtParcelaNext = Helper::getInputDate($dtInicialParcela);
        }
        if (strstr($dtInicialParcela, '-')) {
            $dtParcelaNext = $dtInicialParcela;
        }

        for ($i = 0; $i <= $qtdParcela; $i++) {
            if ($i != 0) {
                $dtParcelaNext = Contrato::getNextMonth($dtParcelaNext, $frequencia);
            }
            $arrayParcelas[$i] = $dtParcelaNext;
        }

        return $arrayParcelas;
    }

    public function cadastrarContrato($dados) {

        $contrato = $this->createRow();

        foreach ($dados as $campo => $valor) {
            $contrato->$campo = $valor;
        }

        return $contrato->save();

    }

    /**
     * Recebe o idContrato e busca o contrato que não foi distratado
     *
     * @param $idContrato
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function findContratoSemDistrato($idContrato) {

        return $this->findContrato('id_lote = ' . $idContrato . ' and fl_distrato = "0"');

    }

    /**
     * Pesquisa os contratos do empreendimento de forma reduzida
     * @param $idEmpreendimento
     * @param null $dataDe
     * @param null $dataAte
     * @return array
     */
    public function findByEmpreendimento($idEmpreendimento, $dataDe = null, $dataAte = null, $aprovado = true) {

        $listaContrato = $this->getAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('nm_empreendimento'))
            ->join(array('l' => TB_LOTES), 'e.id = l.id_empreendimento', array('id', 'quadra', 'lote'))
            ->join(array('c' => TB_CONTRATO), 'l.id = c.id_lote', array('idContrato' => 'id', '*'))
            ->where('c.fl_distrato = "0"')
            ->where('e.id = ' . $idEmpreendimento);

        if($aprovado){
            $listaContrato->where('c.fl_aprovar_contrato = "1"');
        }

        if ($dataDe) {
            $listaContrato->where('c.dt_contrato >= "' . Helper::getInputDate($dataDe) . '"');
        }

        if ($dataAte) {
            $listaContrato->where('c.dt_contrato <= "' . Helper::getInputDate($dataAte) . '"');
        }

        return $listaContrato->order(array('c.dt_contrato', 'quadra', 'lote'))->query()->fetchAll();

    }

    /**
     * Pesquisa os contratos do empreendimento de um ou mais corretores
     * Apenas se foram fechados
     * @param $idContrato
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function findContratoByEmpreendimento($idEmpreendimento, $idCorretor = null, $dataDe = null, $dataAte = null) {

        $listaContrato = $this->getAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('nm_empreendimento'))
            ->join(array('l' => TB_LOTES), 'e.id = l.id_empreendimento', array('id', 'quadra', 'lote'))
            ->join(array('c' => TB_CONTRATO), 'l.id = c.id_lote', array('idContrato' => 'id', '*'))
            ->joinLeft(array('cor' => TB_CORRETOR_EMPREENDIMENTO), 'e.id = cor.id_empreendimento AND c.id_corretor = cor.id_corretor', array('id_imobiliaria'))
            ->join(array('pco' => TB_PESSOA), 'c.id_corretor = pco.id', array('nm_corretor' => 'nm_pessoa'))
            ->join(array('pcl' => TB_PESSOA), 'c.id_pessoa = pcl.id', array('nm_cliente' => 'nm_pessoa'))
            ->joinLeft(array('pj' => TB_PESSOA_JURIDICA), 'cor.id_imobiliaria = pj.id_pessoa', 'nm_fantasia')
            ->where('c.fl_aprovar_contrato = "1"')
            ->where('c.fl_distrato = "0"')
            ->where('e.id = ' . $idEmpreendimento);

        if ($idCorretor && $idCorretor != '*') {
            $listaContrato->where('pco.id = "'. $idCorretor . '"');
        }

        if ($dataDe) {
            $listaContrato->where('c.dt_contrato >= "' . Helper::getInputDate($dataDe) . '"');
        }

        if ($dataAte) {
            $listaContrato->where('c.dt_contrato <= "' . Helper::getInputDate($dataAte) . '"');
        }

        return $listaContrato->order(array('c.dt_contrato', 'quadra', 'lote'))->query()->fetchAll();

    }

    /**
     * Retorna o valor total do contrato de acordo com os dados cadastrais (sem considerar as parcelas)
     * @param $id
     * @return float|int
     */
    public static function getVlContrato($id)
    {
        $contrato = self::getInstance()->fetchRow('id = ' . $id);

        $valorTotal = ((int)$contrato->nr_parcela * (float)$contrato->vl_parcela)
            + ((int)$contrato->nr_intercalada * (float)$contrato->vl_intercalada)
            + ((int)$contrato->nr_parcela_entrega * (float)$contrato->vl_parcela_entrega);
        if ($contrato->inclui_sinal_contrato == 1) {
            $valorTotal += (float)$contrato->vl_sinal;
        }

        if ($contrato->fl_itens_contrato == '1') {
            $itensContrato = ContratoItens::getInstance()->fetchAll('id_contrato = ' . $id);
            foreach ($itensContrato as $item) {
                $valorTotal += (int)$item['qt_parcelas'] * (float)$item['vl_parcela'];
            }
        }
        return $valorTotal;
    }

}