<?php

class ComissaoController extends Controller
{

    public function __construct($request)
    {
        parent::__construct($request);

        $this->parcelaComissaoCorretor = new ParcelaComissaoCorretor();
        $this->parcelaComissaoImobiliaria = new ParcelaComissaoImobiliaria();
        $this->contrato = new Contrato();
        $this->cliente = new Cliente();
        $this->empreendimento = new Empreendimento();

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {
        $this->redir(array("modulo" => "relatorio", "controller" => "comissao", 'action' => 'gerar'));
    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function gerarAction()
    {
        $this->set('clienteAcade', $this->cliente->getCliente());
        $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

        if ($_POST && $this->validar()) {
            $tpParcelas = array();
            if (count($_POST['tp_parcela'])) {
                foreach ($_POST['tp_parcela'] as $tipo) {
                    if ($tipo == 'G' || $tipo == 'Q') {
                        $tpParcelas[] = '"' . $tipo . '"';
                    } else {
                        $tpParcelas[] = '"' . $tipo . '", "' . $tipo . 'E"';
                    }
                }
                $tpParcelas = '(' . implode(',', $tpParcelas) . ')';
            }

            $instanceEmpreendimento = new Empreendimento();

            $relatorio['empreendimento'] = $instanceEmpreendimento->getDefaultAdapter()->select()
                ->from(array('e' => TB_EMPREENDIMENTO), array('e.nm_empreendimento', 'e.rgi', 'e.id', 'e.porcAdmin'))
                ->join(array('c' => Db_Cidade::TABLE_NAME), 'e.cd_cidade = c.id', array('cidade_nome' => 'c.nome', 'cidade_uf' => 'c.uf'))
                ->where('e.id = ?', $_POST['emp'])->query()->fetch();

            //Busca os corretores do empreendimento
            $instanceCorretor = new CorretorEmpreendimento();
            $instanceCorretor = $instanceCorretor->getAdapter()->select()
                ->from(array('ce' => TB_CORRETOR_EMPREENDIMENTO), array('*'))
                ->join(array('p' => TB_PESSOA), 'ce.id_corretor = p.id', array('p.nm_pessoa'))
                ->joinLeft(array('i' => TB_PESSOA_JURIDICA), 'ce.id_imobiliaria = i.id_pessoa', array('i.nm_fantasia'))
                ->where('ce.id_empreendimento = ?', $_POST['emp']);

            //testa se foi selecionado algum contrato ou se está sendo selecionado "todos"
            if ($_POST['corretor'] != '*') {
                $instanceCorretor->where('p.id = ?', $_POST['corretor']);
            }

            $relatorio['corretores'] = $instanceCorretor->query()->fetchAll();
            //Seta os valores do quadro resumo ao final do relatório para acrescimos
            $parcela_quantidade = 0;
            $parcela_valor = 0;
            $totalComissao = 0;

            foreach ($relatorio['corretores'] as $k => &$corretor) {
                $instanceContrato = new Contrato();
                $instanceContrato = $instanceContrato->getDefaultAdapter()->select()
                    ->from(array('c' => TB_CONTRATO), array('id_contrato' => 'c.id', 'id_lote' => 'c.id_lote', 'nr_parcela',
                        'nr_parcela_sinal', 'nr_intercalada', 'nr_parcela_entrega'))
                    ->join(array('l' => TB_LOTES), 'c.id_lote = l.id', array('l.quadra', 'l.lote'))
                    ->join(array('p' => TB_PESSOA), 'c.id_pessoa = p.id', array('p.nm_pessoa', 'p.endereco', 'p.nm_bairro', 'id_pessoa' => 'p.id'));

                $contratos = $instanceContrato->where('l.id_empreendimento = ?', $relatorio['empreendimento']['id'])
                    ->where('c.id_corretor = ?', $corretor['id_corretor'])
                    ->order(array('p.nm_pessoa', 'l.lote', 'l.quadra'))
                    ->query()->fetchAll();

                $count = count($contratos);

                if ($count) {
                    $tpParcelasCorretor = CorretorEmpreendimento::getArrayTiposParcela($corretor['id_corretor'], $_POST['emp']);

                    //percorre todos os contratos selecionados
                    foreach ($contratos as $key => &$contrato) {
                        $parcela_quitacao_quantidade_contrato = 0;
                        $hasSinal = ($contrato['nr_parcela_sinal'] > 0);

                        //Busca todas as parcelas do contrato de acordo com os parametros utilizando a data de pagamento
                        $parcelas = Parcela::parcelaByContrato($contrato['id_contrato'], $_POST['dataDe'], $_POST['dataAte'], $tpParcelas, 1);
                        $parcelasHistorico = Parcela::parcelaHistoricoByContrato($contrato['id_contrato'], $_POST['dataDe'], $_POST['dataAte'], $tpParcelas, 1);

                        $contrato['parcelas'] = array_merge($parcelasHistorico, $parcelas);

                        if (count($contrato['parcelas']) == 0) {
                            unset($contratos[$key]);
                            continue;
                        }

                        foreach ($contrato['parcelas'] as $kp => &$parc) {
                            //Teste para calculo das comissoes referentes a cada tipo
                            switch ($corretor['tp_comissao']) {
                                case 'PL' :
                                    if ($parc['tp_parcela'] == 'S')
                                        $parc['vl_comissao'] = (Contrato::getVlContrato($contrato['id_contrato']) * $corretor['pc_comissao'] * 0.01) / (int)$contrato['nr_parcela_sinal'];
                                    break;
                                case 'P' :
                                    if (in_array($parc['tp_parcela'], $tpParcelasCorretor))
                                        $parc['vl_comissao'] = $parc['vl_parcela'] * ($corretor['pc_comissao'] * 0.01);
                                    break;
                                case 'F' :
                                    if ($hasSinal) {
                                        if ($parc['tp_parcela'] == 'S' && $parc['id_parcela'] == '1')
                                            $parc['vl_comissao'] = $corretor['pc_comissao'];
                                    } else {
                                        if ($parc['tp_parcela'] == 'N' && $parc['id_parcela'] == '1')
                                            $parc['vl_comissao'] = $corretor['pc_comissao'];
                                    }
                                    break;
                            }
                            if (isset($parc['vl_comissao'])) {
                                $totalComissao += $parc['vl_comissao'];
                                $parcela_valor += $parc['vl_parcela'];
                                $parcela_quantidade++;
                            } else
                                unset($contrato['parcelas'][$kp]);
                        }
                        if(count($contrato['parcelas']) == 0) {
                            unset($contratos[$key]);
                        }

                        // Quantidade total de parcelas do contrato
                        $contrato['total']['quitacao'] = $parcela_quitacao_quantidade_contrato;
                    }
                    if (count($contratos) > 0) {
                        $corretor['contratos'] = $contratos;
                    } else {
                        unset($relatorio['corretores'][$k]);
                    }
                } else {
                    unset($relatorio['corretores'][$k]);
                }
            }

            //verifica se existem parcelas para não exibir o relatório em branco
            if (!isset($parcela_quantidade) || $parcela_quantidade == 0) {
                $this->_helper->addMensagem('index', 'Não exitem parcelas a serem exibidas!');
                $this->indexAction();
                exit;
            }

            $relatorio['total']['parcela'] = $parcela_quantidade;
            $relatorio['total']['valor'] = $parcela_valor;
            $relatorio['total']['comissao'] = $totalComissao;
            $relatorio['todos'] = ($_POST['corretor'] == '*') ? true : false;
            $relatorio['periodoPagamento'] = $_POST['dataDe'] . " até " . $_POST['dataAte'];
            $this->set('relatorio', $relatorio);
            $this->display('gerar');


            $css = array(
                "public/libs/bootstrap/css/bootstrap.min.css",
                "public/libs/font-awesome/css/font-awesome.min.css",
                "public/libs/pace/pace.css",
                "public/libs/jquery-datatables/css/dataTables.bootstrap.css",
                "public/css/style.css",
                "public/css/style-responsive.css",
                "public/css/style_painel.css");

            $this->displayMPDF('relatorio_de_comissao_' . date('d/m/Y') . '.pdf', $css);

        } else {
            $this->display('form');
        }
    }

    private function validar()
    {
        $campos = array('emp', 'corretor');
        $valid = true;

        foreach ($campos as $cp) {
            if ($_POST[$cp] == "") {
                $valid = false;

                switch ($cp) {
                    case "emp":
                        $msg = "Escolha um empreendimento.";
                        break;
                    case "corretor":
                        $msg = "Escolha um corretor.";
                        break;
                }
                $this->_helper->addMensagem($cp, $msg);
            }
        }

        return $valid;
    }

    /**
     * Ação responsável por pesquisar os corretores de um emprendimento e retornar em JSON os dados
     */
    public function corretor_by_empAction()
    {
        if ($_POST['emp']) {
            $usuario = Login::getUsuario();
            if ($usuario->getPerfilId() != 9) {
                $options = array(
                    array('id' => '*', 'text' => 'Todos')
                );
                $corretores = $this->empreendimento->findCorretoresByEmp($_POST['emp']);
            } else {
                $options = array();
                $corretores = new CorretorEmpreendimento();
                $corretores = $corretores->getCorretorEmpreendimentoDados($usuario->getId(), $_POST['emp']);
            }
        }

        if (is_array($corretores))
            foreach ($corretores as $corretor) {
                array_push($options, array(
                    'id' => $corretor['id'],
                    'text' => $corretor['nm_pessoa']
                ));
            }

        echo json_encode($options);

    }

    /**
     * Ação responsável por pesquisar os corretores de um emprendimento e retornar em JSON os dados
     */
    public function findContratoByEmpreendimentoAction()
    {
        $valid = true;
        $nrContratos = 0;
        //Organiza em um array os tipos de parcela selecionados no filtro
        $tpParcelas = array();
        $parcelasTipo = explode(',', $_POST['tp_parcela']);
        if (count($parcelasTipo)) {
            foreach ($parcelasTipo as $tipo) {
                if ($tipo == 'G' || $tipo == 'Q')
                    $tpParcelas[] = '"' . $tipo . '"';
                else
                    $tpParcelas[] = '"' . $tipo . '", "' . $tipo . 'E"';
            }
            $tpParcelas = '(' . implode(',', $tpParcelas) . ')';
        }

        $instanceEmpreendimento = new Empreendimento();
        $relatorio['empreendimento'] = $instanceEmpreendimento->getDefaultAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('e.nm_empreendimento', 'e.id'))
            ->where('e.id = ?', $_POST['cdEmpreendimento'])->query()->fetch();

        //Busca os corretores do empreendimento
        $instanceCorretor = new CorretorEmpreendimento();
        $instanceCorretor = $instanceCorretor->getAdapter()->select()
            ->from(array('ce' => TB_CORRETOR_EMPREENDIMENTO), array('*'))
            ->join(array('p' => TB_PESSOA), 'ce.id_corretor = p.id', array('p.nm_pessoa'))
            ->where('ce.id_empreendimento = ?', $_POST['cdEmpreendimento']);

        //testa se foi selecionado algum contrato ou se está sendo selecionado "todos"
        if ($_POST['cdCorretor'] != '*') {
            $instanceCorretor->where('p.id = ?', $_POST['cdCorretor']);
        }
        $relatorio['corretores'] = $instanceCorretor->query()->fetchAll();

        foreach ($relatorio['corretores'] as $k => &$corretor) {
            $instanceContrato = new Contrato();
            $contratos = $instanceContrato->getDefaultAdapter()->select()
                ->from(array('c' => TB_CONTRATO), array('id_contrato' => 'c.id', 'id_lote' => 'c.id_lote',
                    'nr_parcela', 'nr_parcela_sinal', 'nr_intercalada', 'nr_parcela_entrega'))
                ->join(array('l' => TB_LOTES), 'c.id_lote = l.id', array('l.quadra', 'l.lote'))
                ->join(array('p' => TB_PESSOA), 'c.id_pessoa = p.id', array('p.nm_pessoa', 'p.endereco', 'p.nm_bairro', 'id_pessoa' => 'p.id'))
                ->where('l.id_empreendimento = ?', $relatorio['empreendimento']['id'])
                ->where('c.id_corretor = ?', $corretor['id_corretor'])
                ->order(array('p.nm_pessoa', 'l.lote', 'l.quadra'))
                ->query()->fetchAll();

            if (count($contratos))
                //percorre todos os contratos selecionados e busca todas as parcelas do contrato
                foreach ($contratos as $key => &$contrato) {
                    $parcelas = Parcela::parcelaByContrato($contrato['id_contrato'], $_POST['dataDe'], $_POST['dataAte'], $tpParcelas, 1);
                    $parcelasHistorico = Parcela::parcelaHistoricoByContrato($contrato['id_contrato'], $_POST['dataDe'], $_POST['dataAte'], $tpParcelas, 1);

                    if (!count(array_merge($parcelasHistorico, $parcelas)) == 0)
                        $nrContratos++;
                }
        }
        if ($nrContratos == 0)
            $valid = false;

        echo json_encode($valid);
    }
}