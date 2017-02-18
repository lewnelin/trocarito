<?php

class Indicacao extends Zend_Db_Table_Abstract
{

    protected $_name = TB_INDICACAO;

    /**
     * Retorna todas indicações que possuirem no primeiro contato uma indicação como tipo de contato
     *
     * @return array
     */
    public function getIndicacoes($id = null, $ultimoContato = false)
    {
        $infoIndicacao = $this->getAdapter()->select()->from(array('i' => TB_INDICACAO), array('*'))//Pega apenas o ultimo contato da indicação para exibir o tipo de contato
            ->join(array('ic' => TB_INDICACAO_CONTATO), 'ic.id_indicacao_contato = (SELECT MAX(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', null)->join(array('tc' => TB_AGRUPADA), 'tc.idCampo = ic.id_tipo_contato AND tc.idTabela = 13', array('dsTipoContato' => 'descricao'))//Pega apenas o primeiro contato da indicação que o tipo de contato foi uma indicação
            ->join(array('ic2' => TB_INDICACAO_CONTATO), 'ic2.id_tipo_contato = "131" AND ic2.id_indicacao_contato = (SELECT MIN(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', 'dt_contato')->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'))->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento'))->join(array('u' => TB_USUARIO), 'u.id = i.id_usuario', 'login')->where('i.fl_status = "1"');

        // Se não for super-usuario, exibe apenas as indicações feitas pelo usuário atual
        if (!Login::getUsuario()->getSuper()) $infoIndicacao->where('i.id_usuario = ' . Login::getUsuario()->getId());

        if ($id) $infoIndicacao->where('i.id_indicacao = ' . $id);

        $infoIndicacao = $infoIndicacao->query()->fetchAll();

        return $infoIndicacao;
    }

    /**
     * Busca lista de indicações do empreendimento
     * @param $idEmpreendimento
     * @return array
     */
    public static function getIndicacoesByEmpreendimento($idEmpreendimento)
    {
        $indicacoes = self::getDefaultAdapter()->select()->from(array('i' => TB_INDICACAO, array('*')))//Pega apenas o ultimo contato da indicação para exibir o tipo de contato
            ->join(array('ic' => TB_INDICACAO_CONTATO), 'ic.id_indicacao_contato = (SELECT MAX(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', null)->join(array('tc' => TB_AGRUPADA), 'tc.idCampo = ic.id_tipo_contato AND tc.idTabela = 13', array('dsTipoContato' => 'descricao'))//Pega apenas o primeiro contato da indicação que o tipo de contato foi uma indicação
            ->join(array('ic2' => TB_INDICACAO_CONTATO), 'ic2.id_tipo_contato = "131" AND ic2.id_indicacao_contato = (SELECT MIN(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', 'dt_contato')->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'))->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento'))->where('i.fl_status = "1"')->where('e.id = ' . $idEmpreendimento)->query()->fetchAll();

        return $indicacoes;
    }

    /**
     * Busca lista de indicações do empreendimento
     * @param $idEmpreendimento
     * @return array
     */
    public static function getIndicacoesByEmpreendimentoUsuarioPeriodo($idEmpreendimento, $idUsuario, $dtDe, $dtAte, $tipoCaptacao)
    {
        $indicacoes = self::getDefaultAdapter()->select()
            ->from(array('i' => TB_INDICACAO, array('id_indicacao')))
            //Busca todos os dados necessarios de contrato
            ->joinLeft(array('c' => TB_CONTRATO), 'c.id = i.id_contrato',
                array('idContrato' => 'id',
                    'dt_contrato',
                    'id_corretor',
                    'nr_parcela',
                    'nr_parcela_sinal',
                    'nr_intercalada',
                    'nr_parcela_entrega',
                    'vl_sinal',
                    'vl_intercalada',
                    'vl_parcela',
                    'vl_parcela_entrega',
                    'acrescimo',
                    'desconto')
            )
            ->joinLeft(array('l' => TB_LOTES), 'c.id_lote = l.id', array('lote', 'quadra'))
            //Pega o nome do corretor
            ->joinLeft(array('cor' => TB_PESSOA), 'cor.id = c.id_corretor', array('nmPessoaCorretor' => 'nm_pessoa'))
            //Pega apenas o primeiro contato da indicação que o tipo de contato foi uma indicação
            ->join(array('ic' => TB_INDICACAO_CONTATO), 'ic.id_tipo_contato = "131" AND ic.id_indicacao_contato = (SELECT MIN(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', 'dt_contato')
            ->joinLeft(array('pc' => TB_PESSOA), 'pc.id = i.id_usuario', array('nmPessoaCaptador' => 'nm_pessoa'))
            ->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento'))
            ->where('e.id = ' . $idEmpreendimento)
            ->where('i.fl_status = "'.$tipoCaptacao.'"');

        //Se não foi selecionado Todos
        if ($idUsuario != '*')
            $indicacoes = $indicacoes->where('i.id_usuario = ' . $idUsuario);

        $indicacoes = $indicacoes
            ->where('ic.dt_contato >= "' . $dtDe . '"')
            ->where('ic.dt_contato <= "' . $dtAte . '"')
            ->order(array('nmPessoaCaptador', 'dt_contato'))
            ->query()->fetchAll();

        return $indicacoes;
    }

    /**
     * Busca lista de captadores do empreendimento
     * @param $idEmpreendimento
     * @return array
     */
    public static function getCaptadorByEmpreendimento($idEmpreendimento)
    {
        $captadores = self::getDefaultAdapter()->select()->from(array('i' => TB_INDICACAO, array('*')))//Pega apenas o primeiro contato da indicação que o tipo de contato foi uma indicação
            ->join(array('ic2' => TB_INDICACAO_CONTATO), 'ic2.id_tipo_contato = "131" AND ic2.id_indicacao_contato = (SELECT MIN(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', 'dt_contato')->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento'))->join(array('p' => TB_PESSOA), 'p.id = i.id_usuario', array('nm_pessoa'))->where('e.id = ' . $idEmpreendimento)->group('p.id')->query()->fetchAll();

        return $captadores;
    }

}
