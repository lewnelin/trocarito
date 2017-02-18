<?php

class Lote extends Zend_Db_Table_Abstract
{

    protected $_name = TB_LOTES;

    protected $id;
    protected $id_lote;
    protected $nr_proposta;
    protected $dt_contrato;
    protected $fl_avista;
    protected $nr_parcela;
    protected $vl_parcela;
    protected $inclui_sinal_contrato;
    protected $dt_primeira_parcela;
    protected $nr_parcela_sinal;
    protected $vl_sinal;
    protected $dt_sinal;
    protected $nr_intercalada;
    protected $vl_intercalada;
    protected $fr_intercalada;
    protected $dt_intercalada;
    protected $acrescimo;
    protected $desconto;
    protected $dt_assinatura;
    protected $id_pessoa;
    protected $id_corretor;
    protected $fl_gerado;
    protected $id_banco;
    protected $dt_fim_empreendimento;
    protected $fl_sinal_agregado;
    protected $vl_parcela_entrega;
    protected $nr_parcela_entrega;
    protected $fl_distrato;
    protected $dt_parcela_entrega;
    protected $dt_reajuste;
    protected $obs;
    protected $log;
    protected $fl_coinc_intercalada;
    protected $fl_aprovar_contrato;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdLote()
    {
        return $this->id_lote;
    }

    /**
     * @param mixed $id_lote
     */
    public function setIdLote($id_lote)
    {
        $this->id_lote = $id_lote;
    }

    /**
     * @return mixed
     */
    public function getNrProposta()
    {
        return $this->nr_proposta;
    }

    /**
     * @param mixed $nr_proposta
     */
    public function setNrProposta($nr_proposta)
    {
        $this->nr_proposta = $nr_proposta;
    }

    /**
     * @return mixed
     */
    public function getDtContrato()
    {
        return $this->dt_contrato;
    }

    /**
     * @param mixed $dt_contrato
     */
    public function setDtContrato($dt_contrato)
    {
        $this->dt_contrato = $dt_contrato;
    }

    /**
     * @return mixed
     */
    public function getFlAvista()
    {
        return $this->fl_avista;
    }

    /**
     * @param mixed $fl_avista
     */
    public function setFlAvista($fl_avista)
    {
        $this->fl_avista = $fl_avista;
    }

    /**
     * @return mixed
     */
    public function getNrParcela()
    {
        return $this->nr_parcela;
    }

    /**
     * @param mixed $nr_parcela
     */
    public function setNrParcela($nr_parcela)
    {
        $this->nr_parcela = $nr_parcela;
    }

    /**
     * @return mixed
     */
    public function getVlParcela()
    {
        return $this->vl_parcela;
    }

    /**
     * @param mixed $vl_parcela
     */
    public function setVlParcela($vl_parcela)
    {
        $this->vl_parcela = $vl_parcela;
    }

    /**
     * @return mixed
     */
    public function getIncluiSinalContrato()
    {
        return $this->inclui_sinal_contrato;
    }

    /**
     * @param mixed $inclui_sinal_contrato
     */
    public function setIncluiSinalContrato($inclui_sinal_contrato)
    {
        $this->inclui_sinal_contrato = $inclui_sinal_contrato;
    }

    /**
     * @return mixed
     */
    public function getDtPrimeiraParcela()
    {
        return $this->dt_primeira_parcela;
    }

    /**
     * @param mixed $dt_primeira_parcela
     */
    public function setDtPrimeiraParcela($dt_primeira_parcela)
    {
        $this->dt_primeira_parcela = $dt_primeira_parcela;
    }

    /**
     * @return mixed
     */
    public function getNrParcelaSinal()
    {
        return $this->nr_parcela_sinal;
    }

    /**
     * @param mixed $nr_parcela_sinal
     */
    public function setNrParcelaSinal($nr_parcela_sinal)
    {
        $this->nr_parcela_sinal = $nr_parcela_sinal;
    }

    /**
     * @return mixed
     */
    public function getVlSinal()
    {
        return $this->vl_sinal;
    }

    /**
     * @param mixed $vl_sinal
     */
    public function setVlSinal($vl_sinal)
    {
        $this->vl_sinal = $vl_sinal;
    }

    /**
     * @return mixed
     */
    public function getDtSinal()
    {
        return $this->dt_sinal;
    }

    /**
     * @param mixed $dt_sinal
     */
    public function setDtSinal($dt_sinal)
    {
        $this->dt_sinal = $dt_sinal;
    }

    /**
     * @return mixed
     */
    public function getNrIntercalada()
    {
        return $this->nr_intercalada;
    }

    /**
     * @param mixed $nr_intercalada
     */
    public function setNrIntercalada($nr_intercalada)
    {
        $this->nr_intercalada = $nr_intercalada;
    }

    /**
     * @return mixed
     */
    public function getVlIntercalada()
    {
        return $this->vl_intercalada;
    }

    /**
     * @param mixed $vl_intercalada
     */
    public function setVlIntercalada($vl_intercalada)
    {
        $this->vl_intercalada = $vl_intercalada;
    }

    /**
     * @return mixed
     */
    public function getFrIntercalada()
    {
        return $this->fr_intercalada;
    }

    /**
     * @param mixed $fr_intercalada
     */
    public function setFrIntercalada($fr_intercalada)
    {
        $this->fr_intercalada = $fr_intercalada;
    }

    /**
     * @return mixed
     */
    public function getDtIntercalada()
    {
        return $this->dt_intercalada;
    }

    /**
     * @param mixed $dt_intercalada
     */
    public function setDtIntercalada($dt_intercalada)
    {
        $this->dt_intercalada = $dt_intercalada;
    }

    /**
     * @return mixed
     */
    public function getAcrescimo()
    {
        return $this->acrescimo;
    }

    /**
     * @param mixed $acrescimo
     */
    public function setAcrescimo($acrescimo)
    {
        $this->acrescimo = $acrescimo;
    }

    /**
     * @return mixed
     */
    public function getDesconto()
    {
        return $this->desconto;
    }

    /**
     * @param mixed $desconto
     */
    public function setDesconto($desconto)
    {
        $this->desconto = $desconto;
    }

    /**
     * @return mixed
     */
    public function getDtAssinatura()
    {
        return $this->dt_assinatura;
    }

    /**
     * @param mixed $dt_assinatura
     */
    public function setDtAssinatura($dt_assinatura)
    {
        $this->dt_assinatura = $dt_assinatura;
    }

    /**
     * @return mixed
     */
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    /**
     * @param mixed $id_pessoa
     */
    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;
    }

    /**
     * @return mixed
     */
    public function getIdCorretor()
    {
        return $this->id_corretor;
    }

    /**
     * @param mixed $id_corretor
     */
    public function setIdCorretor($id_corretor)
    {
        $this->id_corretor = $id_corretor;
    }

    /**
     * @return mixed
     */
    public function getFlGerado()
    {
        return $this->fl_gerado;
    }

    /**
     * @param mixed $fl_gerado
     */
    public function setFlGerado($fl_gerado)
    {
        $this->fl_gerado = $fl_gerado;
    }

    /**
     * @return mixed
     */
    public function getIdBanco()
    {
        return $this->id_banco;
    }

    /**
     * @param mixed $id_banco
     */
    public function setIdBanco($id_banco)
    {
        $this->id_banco = $id_banco;
    }

    /**
     * @return mixed
     */
    public function getDtFimEmpreendimento()
    {
        return $this->dt_fim_empreendimento;
    }

    /**
     * @param mixed $dt_fim_empreendimento
     */
    public function setDtFimEmpreendimento($dt_fim_empreendimento)
    {
        $this->dt_fim_empreendimento = $dt_fim_empreendimento;
    }

    /**
     * @return mixed
     */
    public function getFlSinalAgregado()
    {
        return $this->fl_sinal_agregado;
    }

    /**
     * @param mixed $fl_sinal_agregado
     */
    public function setFlSinalAgregado($fl_sinal_agregado)
    {
        $this->fl_sinal_agregado = $fl_sinal_agregado;
    }

    /**
     * @return mixed
     */
    public function getVlParcelaEntrega()
    {
        return $this->vl_parcela_entrega;
    }

    /**
     * @param mixed $vl_parcela_entrega
     */
    public function setVlParcelaEntrega($vl_parcela_entrega)
    {
        $this->vl_parcela_entrega = $vl_parcela_entrega;
    }

    /**
     * @return mixed
     */
    public function getNrParcelaEntrega()
    {
        return $this->nr_parcela_entrega;
    }

    /**
     * @param mixed $nr_parcela_entrega
     */
    public function setNrParcelaEntrega($nr_parcela_entrega)
    {
        $this->nr_parcela_entrega = $nr_parcela_entrega;
    }

    /**
     * @return mixed
     */
    public function getFlDistrato()
    {
        return $this->fl_distrato;
    }

    /**
     * @param mixed $fl_distrato
     */
    public function setFlDistrato($fl_distrato)
    {
        $this->fl_distrato = $fl_distrato;
    }

    /**
     * @return mixed
     */
    public function getDtParcelaEntrega()
    {
        return $this->dt_parcela_entrega;
    }

    /**
     * @param mixed $dt_parcela_entrega
     */
    public function setDtParcelaEntrega($dt_parcela_entrega)
    {
        $this->dt_parcela_entrega = $dt_parcela_entrega;
    }

    /**
     * @return mixed
     */
    public function getDtReajuste()
    {
        return $this->dt_reajuste;
    }

    /**
     * @param mixed $dt_reajuste
     */
    public function setDtReajuste($dt_reajuste)
    {
        $this->dt_reajuste = $dt_reajuste;
    }

    /**
     * @return mixed
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * @param mixed $obs
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
    }

    /**
     * @return mixed
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param mixed $log
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @return mixed
     */
    public function getFlCoincIntercalada()
    {
        return $this->fl_coinc_intercalada;
    }

    /**
     * @param mixed $fl_coinc_intercalada
     */
    public function setFlCoincIntercalada($fl_coinc_intercalada)
    {
        $this->fl_coinc_intercalada = $fl_coinc_intercalada;
    }

    /**
     * @return mixed
     */
    public function getFlAprovarContrato()
    {
        return $this->fl_aprovar_contrato;
    }

    /**
     * @param mixed $fl_aprovar_contrato
     */
    public function setFlAprovarContrato($fl_aprovar_contrato)
    {
        $this->fl_aprovar_contrato = $fl_aprovar_contrato;
    }


    /**
     * Pesquisa e retorna informaçoes do lote e de tabelas associadas ao lote
     *
     * @param integer $idLote
     * @return array
     */
    public function getLotesByIdLote($idLote, $idTabela = null)
    {
        //Verifica se foi selecionada um tabela de preço ou se vai utilizar a tabela mais recente
        $whereTabelaPreco = '';
        if ($idTabela && $idTabela != 'null') {
            $whereTabelaPreco = ' AND tpl.id_tabela_preco = ' . $idTabela;
        } else {
            $tabelaPadrao = TabelaPreco::getTabelaPadraoByLote($idLote);
            if ($tabelaPadrao) {
                $whereTabelaPreco = ' AND tpl.id_tabela_preco = ' . $tabelaPadrao['id_tabela_preco'];
            }
        }

        //Buscando a lista de lotes para exibir na tela
        return $this->getDefaultAdapter()->select()
            ->from(array('l' => TB_LOTES), array('*', 'idLote' => 'id', 'logLote' => 'log', 'reservaTecnica' => 'reservado', 'rgiLote' => 'rgi'))
            ->joinLeft(array('e' => TB_EMPREENDIMENTO), 'e.id = l.id_empreendimento', array('*', 'idEmpreendimento' => 'id', 'logEmpreendimento' => 'log'))
            ->joinLeft(array('i' => TB_AGRUPADA), 'i.idTabela = 12 AND i.idCampo = e.indice', array('descricaoIndice' => 'descricao'))
            ->joinLeft(array('c' => TB_CONTRATO), 'c.id_lote = l.id AND c.fl_distrato = "0"',
                array('*',
                    'idContrato' => 'id',
                    'logContrato' => 'log',
                    'vlSinalC' => 'vl_sinal',
                    'nrParcelaSinalC' => 'nr_parcela_sinal',
                    'dtSinalC' => 'dt_sinal',
                    'nrParcelaEntregaC' => 'nr_parcela_entrega',
                    'vlParcelaEntregaC' => 'vl_parcela_entrega',
                    'dtParcelaEntregaC' => 'dt_parcela_entrega',
                    'nrIntercaladaC' => 'nr_intercalada',
                    'vlIntercaladaC' => 'vl_intercalada',
                    'dtIntercaladaC' => 'dt_intercalada',
                    'nrParcelaC' => 'nr_parcela',
                    'vlParcelaC' => 'vl_parcela',
                    'dtPrimeiraParcelaC' => 'dt_primeira_parcela',
                    'flAdicionais' => 'fl_itens_contrato'
                )
            )
            ->joinLeft(array('pa' => TB_PARCERIA), 'pa.id_empreendimento = e.id', null)
            ->joinLeft(array('pSocio' => TB_PESSOA), 'pSocio.id = pa.id_pessoa', array('nmPessoaSocio' => 'nm_pessoa', 'enderecoSocio' => 'endereco', 'nmBairroSocio' => 'nm_bairro', 'nrCepSocio' => 'nr_cep'))
            ->joinLeft(array('pjSocio' => TB_PESSOA_JURIDICA), 'pjSocio.id_pessoa = pSocio.id', array('nrCnpjSocio' => 'nr_cnpj'))
            ->joinLeft(array('pfSocio' => TB_PESSOA_FISICA), 'pfSocio.id_pessoa = pSocio.id', array('nrCpfSocio' => 'nr_cpf'))
            ->joinLeft(array('pCliente' => TB_PESSOA), 'c.id_pessoa = pCliente.id', array('nmCliente' => 'nm_pessoa', 'emailC' => 'email', 'enderecoC' => 'endereco', 'bairroC' => 'nm_bairro', 'cepC' => 'nr_cep', 'nrTelefoneC' => 'nr_telefone', 'nrCelularC' => 'nr_celular'))
            ->joinLeft(array('pfCliente' => TB_PESSOA_FISICA), 'c.id_pessoa = pfCliente.id_pessoa', array('nr_cpf', 'nrCpfC' => 'nr_cpf', 'nmNacionalidadeC' => 'nm_nacionalidade', 'nrRgC' => 'nr_rg', 'dtNascimentoC' => 'dt_nascimento', 'nmProfissaoC' => 'nm_profissao', 'estCivilC' => 'est_civil', 'nmPaiC' => 'nm_pai', 'nmMaeC' => 'nm_mae'))
            ->joinLeft(array('cidCliente' => TB_CIDADE), 'cidCliente.id = pCliente.cd_cidade', array('nomeCidC' => 'nome', 'ufCidC' => 'uf'))
            ->joinLeft(array('pClienteConjuge' => TB_PESSOA), 'pClienteConjuge.id = pfCliente.cd_conjuge', array('nmPessoaConj' => 'nm_pessoa', 'emailConj' => 'email'))
            ->joinLeft(array('pfClienteConjuge' => TB_PESSOA_FISICA), 'pfClienteConjuge.id_pessoa = pClienteConjuge.id', array('nrRgConj' => 'nr_rg', 'nrCpfConj' => 'nr_cpf', 'dtNascimentoConj' => 'dt_nascimento', 'nmProfissaoConj' => 'nm_profissao'))
            ->joinLeft(array('pCorretor' => TB_PESSOA), 'c.id_corretor = pCorretor.id', array('nmCorretor' => 'nm_pessoa'))
            ->joinLeft(array('dadosCorretor' => TB_CORRETOR), 'dadosCorretor.id_pessoa = pCorretor.id', array('creciCorretor' => 'cd_creci'))
            ->joinLeft(array('rl' => TB_RESERVA_LOTE), 'rl.reservado = 1 AND rl.cod_lote = l.id', array('*'))
            ->joinLeft(array('pCorretorReserva' => TB_PESSOA), 'rl.corretor = pCorretorReserva.id', array('nmCorretorReserva' => 'nm_pessoa'))
            ->joinLeft(array('tpl' => TB_TABELA_PRECO_LOTES), 'tpl.id_lote = l.id' . $whereTabelaPreco, array('*'))
            ->joinLeft(array('tp' => TB_TABELA_PRECO), 'tp.id_tabela_preco = tpl.id_tabela_preco', array('*'))
            ->joinLeft(array('ce' => TB_CORRETOR_EMPREENDIMENTO), 'ce.id_corretor = rl.corretor AND ce.id_empreendimento = e.id', array('*'))
            ->where('l.id = ' . $idLote)
            ->query()->fetch();
    }

    /**
     * Pesquisa e retorna uma lista com informaçoes de TODOS os lotes e tabelas associadas aos lotes
     *
     * @param $idEmpreendimento = id do empreendimento da tabela EMPREENDIMENTO
     * @param null $nomeQuadra = nome da quadra da tabela LOTES
     * @param null $ultimoIdLote = id do ultimo lote que está na view painelVenda.php
     * @return array = lista de lotes
     */
    public function getLotesEmpreendimento($idEmpreendimento, $nomeQuadra = null, $ultimoIdLote = null, $tabelaPreco = null)
    {
        $whereTabelaPrecoLotes = '';
        if ($tabelaPreco) {
            $whereTabelaPrecoLotes = ' AND id_tabela_preco = ' . $tabelaPreco;
        } else {
            $tabelaPadrao = TabelaPreco::getTabelaPadraoByEmpreendimento($idEmpreendimento);
            if ($tabelaPadrao) {
                $whereTabelaPrecoLotes = ' AND id_tabela_preco = ' . $tabelaPadrao['id_tabela_preco'];
            }
        }

        $listaLotes = $this->getDefaultAdapter()->select()
            ->from(array('l' => TB_LOTES), array('idLote' => 'id', 'lote', 'reservadoLote' => 'reservado', 'area', 'valor', 'quadra'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = l.id_empreendimento', array('idEmpreendimento' => 'id', 'temp_reserva'))
            ->joinLeft(array('c' => TB_CONTRATO), 'c.id_lote = l.id AND c.fl_distrato = "0"', array('idContrato' => 'id', 'fl_distrato', 'fl_aprovar_contrato', 'corretorContrato' => 'id_corretor'))
            ->joinLeft(array('rl' => TB_RESERVA_LOTE), 'rl.cod_lote = l.id AND rl.reservado = 1', array('idReserva' => 'id', 'data_reserva', 'reservadoReservaLote' => 'reservado', 'corretorReserva' => 'corretor'))
            ->joinLeft(array('tpl' => TB_TABELA_PRECO_LOTES), 'tpl.id_lote = l.id' . $whereTabelaPrecoLotes, array('vl_total', 'id_tabela_preco_lotes'))
            ->where('e.id = ' . $idEmpreendimento)
            ->group('l.id')
            ->group('l.quadra');

        if ($nomeQuadra) {
            $listaLotes = $listaLotes->where('l.quadra = "' . $nomeQuadra . '"');
        }

        if ($ultimoIdLote) {
            $listaLotes = $listaLotes->where('l.id > ' . $ultimoIdLote);
        }

        return $listaLotes->order('l.id')->query()->fetchAll();

    }

    /**
     * Função que busca os lotes disponíveis de um empreendimento, com quadra e id opcionais
     * @param $idEmpreendimento
     * @param null $nomeQuadra
     * @param null $ultimoIdLote
     * @param null $tabelaPreco
     * @return PDO_Statement|Zend_Db_Statement
     */
    public function getLotesDisponiveisEmpreendimento($idEmpreendimento, $nomeQuadra = null, $ultimoIdLote = null, $tabelaPreco = null, $reservados = false)
    {
        $whereTabelaPrecoLotes = '';
        if ($tabelaPreco) {
            $whereTabelaPrecoLotes = ' AND id_tabela_preco = ' . $tabelaPreco;
        } else {
            $tabelaPadrao = TabelaPreco::getTabelaPadraoByEmpreendimento($idEmpreendimento);
            if ($tabelaPadrao) {
                $whereTabelaPrecoLotes = ' AND id_tabela_preco = ' . $tabelaPadrao['id_tabela_preco'];
            }
        }
        //busca no model de contrato os ids dos lotes por empreendimento que possuem contratos ativos
        $contratos = (new Contrato())->findByEmpreendimento($idEmpreendimento, null, null, false);
        if (!$contratos)
            $contratos = array(array('id_lote' => 0));
        $arrayContratos = array();
        foreach ($contratos as $contrato) {
            $arrayContratos[] = $contrato['id_lote'];
        }

        $listaLotes = $this->getAdapter()->select()
            ->from(array('l' => TB_LOTES), array('idLote' => 'id', 'lote', 'area', 'valor', 'quadra', 'reservado'))
            ->joinLeft(array('tpl' => TB_TABELA_PRECO_LOTES), 'tpl.id_lote = l.id' . $whereTabelaPrecoLotes, array('vl_total', 'vl_parcela', 'vl_sinal', 'vl_intercalada', 'qt_intercalada', 'dt_atualizacao'))
            ->joinLeft(array('rl' => TB_RESERVA_LOTE), 'rl.cod_lote = l.id AND rl.reservado = "1"', array('reserva' => 'reservado'))
            ->where('l.id_empreendimento = ' . $idEmpreendimento)
            ->where('l.id NOT IN(?)', $arrayContratos);

        if (!$reservados) {
            //constroi lista de lotes reservados (id) pelo id do empreendimento
            $reservaLote = (new ReservaLote())->findReservaEmpreendimento($idEmpreendimento, true);
            if ($reservaLote)
                $listaLotes->where('l.id NOT IN(?)', $reservaLote);
        }

        $listaLotes->group('l.id')
            ->group('l.quadra');

        if ($nomeQuadra && $nomeQuadra != '*') {
            $listaLotes = $listaLotes->where('l.quadra = "' . $nomeQuadra . '"');
        }
        if ($ultimoIdLote) {
            $listaLotes = $listaLotes->where('l.id > ' . $ultimoIdLote);
        }
        $listaLotes = $listaLotes->order('l.id')->query()->fetchAll();

        if (!$reservados)
            foreach ($listaLotes as $k => &$lote) {
                if($lote['reservado'] == '1')
                    unset($listaLotes[$k]);
            }

        return $listaLotes;
    }

    /**
     * Lista todos os lotes de um empreendimento que possuem contrato, assinado ou não
     * @param $idEmpreendimento
     * @param null $nomeQuadra
     * @param null $ultimoIdLote
     * @param null $tabelaPreco
     * @return PDO_Statement|Zend_Db_Statement
     */
    public function getLotesNegociadosEmpreendimento($idEmpreendimento, $nomeQuadra = null, $ultimoIdLote = null, $tabelaPreco = null)
    {
        $whereTabelaPrecoLotes = '';
        if ($tabelaPreco) {
            $whereTabelaPrecoLotes = ' AND id_tabela_preco = ' . $tabelaPreco;
        } else {
            $tabelaPadrao = TabelaPreco::getTabelaPadraoByEmpreendimento($idEmpreendimento);
            if ($tabelaPadrao) {
                $whereTabelaPrecoLotes = ' AND id_tabela_preco = ' . $tabelaPadrao['id_tabela_preco'];
            }
        }

        $listaLotes = $this->getDefaultAdapter()->select()
            ->from(array('l' => TB_LOTES), array('idLote' => 'id', 'lote', 'reservadoLote' => 'reservado', 'area', 'valor', 'quadra'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = l.id_empreendimento', array('idEmpreendimento' => 'id', 'temp_reserva'))
            ->join(array('c' => TB_CONTRATO), 'c.id_lote = l.id AND c.fl_distrato = "0"', array('id_pessoa', 'fl_aprovar_contrato'))
            ->join(array('p' => TB_PESSOA), 'c.id_pessoa = p.id AND c.fl_distrato = "0"', 'p.nm_pessoa')
            ->joinLeft(array('tpl' => TB_TABELA_PRECO_LOTES), 'tpl.id_lote = l.id' . $whereTabelaPrecoLotes, array('vl_total'))
            ->where('e.id = ' . $idEmpreendimento)
            ->group('l.id')
            ->group('l.quadra');

        if ($nomeQuadra) {
            $listaLotes = $listaLotes->where('l.quadra = "' . $nomeQuadra . '"');
        }

        if ($ultimoIdLote) {
            $listaLotes = $listaLotes->where('l.id > ' . $ultimoIdLote);
        }

        return $listaLotes->order('l.id')->query()->fetchAll();

    }

    /**
     * Retorna lotes buscando pelo empreendimento, quadra e número do lote
     * @param $idEmpreendimento
     * @param $quadra
     * @param $numero
     * @return mixed
     */
    public static function getLotesEmpreendimentoQuadraNumero($idEmpreendimento, $quadra, $numero)
    {
        $lote = self::getDefaultAdapter()->select()
            ->from(array('l' => TB_LOTES), array('*'))
            ->joinLeft(array('ttpl' => TB_TABELA_PRECO_LOTES), 'l.id = ttpl.id_lote', array('id_lote', 'id_tabela_preco'))
            ->joinLeft(array('ttp' => TB_TABELA_PRECO), 'ttp.id_tabela_preco = ttpl.id_tabela_preco', array('id_tabela_preco'))
            ->where('l.id_empreendimento = ?', $idEmpreendimento)
            ->where('quadra = ?', $quadra)
            ->where('lote = ?', $numero)
            ->query()->fetch();

        return $lote;
    }


    /**
     * Busca os empreendimentos da tabela de preço pela quadra
     * @param $idEmpreendimento
     * @param $quadra
     * @return array
     */
    public static function getLotesByEmpreendimentoQuadraTabelaPreco($idEmpreendimento, $quadra, $tabela)
    {
        $lotes = self::getDefaultAdapter()->select()
            ->from(array('l' => TB_LOTES), array('*'))
            ->join(array('ttpl' => TB_TABELA_PRECO_LOTES), 'l.id = ttpl.id_lote', array('*'))
            ->join(array('ttp' => TB_TABELA_PRECO), 'ttp.id_tabela_preco = ttpl.id_tabela_preco', array('id_tabela_preco'))
            ->joinLeft(array('c' => TB_CONTRATO), 'c.id_lote = l.id AND c.fl_distrato = "0"', array('idContrato' => 'c.id', 'fl_aprovar_contrato'))
            ->joinLeft(array('rl' => TB_RESERVA_LOTE), 'rl.cod_lote = l.id AND rl.reservado = 1', array('idReserva' => 'rl.id'))
            ->where('l.id_empreendimento = ?', $idEmpreendimento)
            ->where('quadra = ?', $quadra)
            ->where('ttpl.id_tabela_preco = ?', $tabela)
            ->query()->fetchAll();

        return $lotes;
    }

}
