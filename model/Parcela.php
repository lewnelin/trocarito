<?php

class Parcela extends Zend_Db_Table_Abstract
{

    protected $_name = TB_PARCELA;
    static $parcelaInstacia;

    public static function parcelaByIdContrato($idContrato,$idParcela)
    {
        $parcelas = self::getInstance();
        $parcelas = $parcelas->getAdapter()->select()->
        from(array('p' => TB_PARCELA), array('*'))->
        where('p.id_contrato = ?', $idContrato)->
        where('p.id_parcela = ?', $idParcela)->query()->fetch();

        return $parcelas;
    }

    /**
     * Busca todas parcelas pagas de um contrato na tabela de parcela
     *
     * @param $contrato - id do contrato
     * @param bool $dt_inicial - data inicial das parcelas
     * @param bool $dt_final - data final das parcelas
     * @param bool $tp_parcela - tipos de parcelas
     * @param int $paga - se é pra pesquisar parcelas pagas ou não pagas
     * @param bool $tipoPg - tipo de pagamento
     * @return Contrato|Zend_Db_Select
     */
    public static function parcelaByContrato($contrato, $dt_inicial = false, $dt_final = false, $tp_parcela = false, $paga = 0, $tipoPg = false, $dtCredito = false)
    {
        $parcelas = self::getInstance();
        $parcelas = $parcelas->getAdapter()->select()
            ->from(array('p' => TB_PARCELA), array('id_parcela_contrato', 'id_parcela', 'tp_parcela', 'dt_parcela',
                'acrescimo', 'desconto', 'vl_parcela', 'dt_pagamento', 'dt_credito', 'vl_juros', 'vl_multa',
                'vl_total_pago', 'tp_baixa', 'id_negociacao'))
            ->where('p.id_contrato = ?', $contrato)
            ->where('p.pago = "?"', $paga);

        if ($tp_parcela) {
            $parcelas = $parcelas->where('p.tp_parcela IN ' . $tp_parcela);
        }
        if ($tipoPg && $paga == 1) {
            $parcelas = $parcelas->where('p.tp_baixa IN ' . $tipoPg);
        }

        //testa se possui um período
        if ($dt_inicial && $dt_final) {
            if ($paga == 1) {
                //testa se é por data de credito ou pagamento
                if ($dtCredito == 'C') {
                    $parcelas = $parcelas->where('p.dt_credito BETWEEN "' . Helper::getInputDate($dt_inicial) . '" AND "' . Helper::getInputDate($dt_final) . '" ');
                } elseif (is_array($dtCredito)) {
                    $parcelas = $parcelas->where('p.dt_credito BETWEEN "' . Helper::getInputDate($dtCredito['de']) . '" AND "' . Helper::getInputDate($dtCredito['ate']) . '" AND ' .
                        'p.dt_pagamento BETWEEN "' . Helper::getInputDate($dt_inicial) . '" AND "' . Helper::getInputDate($dt_final) . '" ');
                } else {
                    $parcelas = $parcelas->where('p.dt_pagamento BETWEEN "' . Helper::getInputDate($dt_inicial) . '" AND "' . Helper::getInputDate($dt_final) . '" ');
                }
            } else {
                $parcelas = $parcelas->where('p.dt_parcela BETWEEN "' . Helper::getInputDate($dt_inicial) . '" AND "' . Helper::getInputDate($dt_final) . '" ');
            }
        }
        $parcelas = $parcelas->order('dt_parcela')->query()->fetchAll();

        foreach ($parcelas as &$parcela) {
            $parcela['dt_parcela'] = Helper::dataParaBrasil($parcela['dt_parcela']);
        }

        return $parcelas;

    }

    /**
     * Busca todas parcelas pagas de um contrato na tabela de parcela historico
     * @param $contrato
     * @param bool $dt_inicial
     * @param bool $dt_final
     * @param bool $tp_parcela
     * @param int $paga
     * @param bool $tipoPg
     * @param bool $dtCredito
     * @return Contrato|Zend_Db_Select
     */
    public static function parcelaHistoricoByContrato($contrato, $dt_inicial = false, $dt_final = false, $tp_parcela = false, $paga = 0, $tipoPg = false, $dtCredito = false)
    {
        $parcelas = self::getInstance();
        $parcelas = $parcelas->getAdapter()->select()
            ->from(array('p' => TB_PARCELA_HISTORICO), array('id_parcela_contrato', 'id_parcela', 'tp_parcela', 'dt_parcela',
                'acrescimo', 'desconto', 'vl_parcela', 'dt_pagamento', 'dt_credito', 'vl_juros', 'vl_multa', 'vl_total_pago',
                'tp_baixa', 'id_negociacao'))
            ->where('p.id_contrato = ?', $contrato)
            ->where('p.pago = "?"', $paga);

        //testa se existe tipos específicos de parcela
        if ($tp_parcela) {
            $parcelas = $parcelas->where('p.tp_parcela IN ' . $tp_parcela);
        }
        //testa se é de pagas
        if ($tipoPg && $paga == 1) {
            $parcelas = $parcelas->where('p.tp_baixa IN ' . $tipoPg);
        }
        //testa se possui um período
        if ($dt_inicial && $dt_final) {
            if ($paga == 1) {
                //testa se é por data de credito ou de pagamento
                if ($dtCredito == 'C') {
                    $parcelas = $parcelas->where('p.dt_credito BETWEEN "' . Helper::dataParaAmericano($dt_inicial) . '" AND "' . Helper::dataParaAmericano($dt_final) . '" ');
                } elseif (is_array($dtCredito)) {
                    $parcelas = $parcelas->where('p.dt_credito BETWEEN "' . Helper::getInputDate($dtCredito['de']) . '" AND "' . Helper::getInputDate($dtCredito['ate']) . '" AND ' .
                        'p.dt_pagamento BETWEEN "' . Helper::getInputDate($dt_inicial) . '" AND "' . Helper::getInputDate($dt_final) . '" ');
                } else {
                    $parcelas = $parcelas->where('p.dt_pagamento BETWEEN "' . Helper::dataParaAmericano($dt_inicial) . '" AND "' . Helper::dataParaAmericano($dt_final) . '" ');
                }
            } else {
                $parcelas = $parcelas->where('p.dt_parcela BETWEEN "' . Helper::dataParaAmericano($dt_inicial) . '" AND "' . Helper::dataParaAmericano($dt_final) . '" ');
            }
        }
        $parcelas = $parcelas->order('dt_parcela')->query()->fetchAll();

        foreach ($parcelas as &$parcela) {
            $parcela['dt_parcela'] = Helper::dataParaBrasil($parcela['dt_parcela']);
        }

        return $parcelas;

    }

    //seleciona a parcela pelo id
    public static function parcelaById($id)
    {
        $parcela = self::getInstance();
        $parcela = $parcela->getAdapter()->select()->
        from(array('p' => TB_PARCELA), array('*'))->where('p.id_parcela_contrato = ?', $id)->
        query()->fetch();
        $parcela['dt_parcela'] = Helper::dataParaBrasil($parcela['dt_parcela']);

        return $parcela;
    }

    /**
     * @return Contrato
     */
    public static function getInstance()
    {
        if (!self::$parcelaInstacia) {
            self::$parcelaInstacia = new Parcela();
        }
        return self::$parcelaInstacia;
    }

}