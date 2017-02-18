<?php

class DashboardController extends Controller
{

    //Função pública do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->contrato = new Contrato();
        $this->lote = new Lote();
        $this->banco = new Banco();
        $this->empreendimento = new Empreendimento();
        $this->logoEmpreendimento = new LogoEmpreendimento();
        $this->corretorEmpreendimento = new CorretorEmpreendimento();
        $this->reservaLote = new ReservaLote();
        $this->pessoa = new Pessoa();
        $this->sinal = new Sinal();
        $this->cidade = new Cidade();
        $this->tabelaAgrupada = new TabelaAgrupada();
        $this->pessoaFisica = new PessoaFisica();
        $this->pessoaJuridica = new PessoaJuridica();
        $this->parametroComissao = new ParametroComissao();
        $this->parcelaComissaoCorretor = new ParcelaComissaoCorretor();
        $this->parcelaComissaoImobiliaria = new ParcelaComissaoImobiliaria();
        $this->representante = new Representante();
        $this->contratoPessoa = new ContratoPessoa();
        $this->cliente = new Cliente();
    }

    /**
     * Função publica que é acessada assim que chama o controller
     */
    public function indexAction()
    {
        $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", 'action' => 'painelVenda'));
    }

    /**
     * Função resposável por exibir todas informações da tela inicial
     */
    public function painelVendaAction()
    {
        if (Helper::verificaAjax()) {

            $infoLotes = array();

            $idEmpreendimento = isset($_GET['empreendimento']) ? $_GET['empreendimento'] : null;

            if ($idEmpreendimento) {

                $quadra = (isset($_GET['quadra']) && $_GET['quadra'] != '*' && $_GET['quadra'] != 'null') ? $_GET['quadra'] : null;
                $tabelaPreco = (isset($_GET['tabelaPreco']) && $_GET['tabelaPreco'] != '*' && $_GET['tabelaPreco'] != 'null') ? $_GET['tabelaPreco'] : null;

                $listaLotes = $this->lote->getLotesEmpreendimento($idEmpreendimento, $quadra, $_GET['ultimoId'], $tabelaPreco);

                if ($listaLotes) {

                    $statusAtual = (isset($_GET['status']) && $_GET['status'] != '*') ? $_GET['status'] : null;

                    foreach ($listaLotes as $lotes) {

                        $idContrato = $lotes['idContrato'];
                        $reservadoLote = $lotes['reservadoLote'];
                        $reservadoReservaLote = $lotes['reservadoReservaLote'];
                        $flAprovarContrato = $lotes['fl_aprovar_contrato'];

                        //Se nao houver contrato e não estiver reservado o status é Disponivel
                        if (!$idContrato && !$reservadoLote && !$reservadoReservaLote) {
                            $status = 'Disponivel';
                            $codigo = 'D';
                            $class = 'green-1';

                            //Se houver contrato e o contrato tiver sido aprovado o status é Vendido
                        } else if ($idContrato && $flAprovarContrato) {
                            $status = 'Vendido';
                            $codigo = 'V';
                            $class = 'pink-2';

                            //Se houver contrato e o contrato naõ tiver sido aprovado o status é Negociação
                        } else if ($idContrato && !$flAprovarContrato) {
                            $status = 'Negociação';
                            $codigo = 'N';
                            $class = 'blue-2';

                            //Se não houver contrato e não estiver reservado o status é Reservado
                        } else if (!$idContrato && ($reservadoLote || $reservadoReservaLote)) {
                            $status = 'Reservado';
                            $codigo = 'R';
                            $class = 'yellow-2';

                            //Faz o calculo da data de reserva, caso estiver expirado, automaticamente o lote mudará para disponivel
                            //Lotes com reserva tecnica não entram na condição
                            if ($lotes['temp_reserva'] && $lotes['data_reserva'] && !$reservadoLote) {

                                $timeZone = new DateTimeZone('UTC');

                                //Data da reserva somado à quantidade de dias de reserva
                                $dataExpiracao = date('d/m/Y', strtotime("+" . $lotes['temp_reserva'] . " days", strtotime($lotes['data_reserva'])));

                                $status = 'Reservado até ' . $dataExpiracao;

                                //Data atual em time
                                $dataAtualTime = DateTime::createFromFormat('d/m/Y', date('d/m/Y', strtotime("now")), $timeZone);

                                //Data de expiração em time
                                $dataExpiracaoTime = DateTime::createFromFormat('d/m/Y', $dataExpiracao, $timeZone);

                                if ($dataExpiracaoTime < $dataAtualTime) {
                                    $this->reservaLote->alterarReservaLote($lotes['idLote']);
                                    $status = 'Disponível';
                                    $codigo = 'D';
                                    $class = 'green-1';
                                }

                            }
                        }

                        //Ignora o loop caso o status seja diferente do escolhido
                        if ($statusAtual && ($codigo != $statusAtual)) {
                            continue;
                        }

                        $lotes['status'] = $status;
                        $lotes['codigo'] = $codigo;
                        $lotes['class'] = $class;

                        $infoLotes[] = $lotes;

                    }

                    //Separa apenas os primeiros 36 registros para serem exibidos
                    $infoLotes = array_slice($infoLotes, 0, 36);

                    //Busca apenas as informações do ultimo lote
                    $ultimoRegistro = end($infoLotes);
                }

            }

            echo json_encode(array($infoLotes, $ultimoRegistro));

        } else {

            $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

            $this->display('painelVenda');

        }

    }

    public function reservarLoteAction()
    {
        $usuario = Login::getUsuario();
        if ($usuario->getPerfilId() != 9)
            $corretores = $this->corretorEmpreendimento->getCorretoresByIdEmpreendimento($_GET['idEmpreedimento']);
        else
            $corretores = $this->corretorEmpreendimento->getCorretoresByIdEmpreendimento($_GET['idEmpreedimento'], $usuario->getId());

        $this->set('listaCorretorEmpreendimento', $corretores);
        $this->set('infoLote', $this->lote->getLotesByIdLote($_GET['idLote']));

        if ($this->_isPost) {
            $transacaoReservaLote = $this->reservaLote->getAdapter();
            $transacaoReservaLote->beginTransaction();
            try {
                $reserva = $this->reservaLote->fetchRow('cod_lote = ' . $_GET['idLote'] . ' AND reservado = 1');

                if (!$reserva) {
                    $rl = $this->reservaLote->createRow();
                    $rl->cod_lote = $_GET['idLote'];
                    $rl->reservado = 1;
                    $rl->comprado = 0;
                    $rl->corretor = $_POST['idCorretor'];
                    $rl->data_compra = null;
                    $rl->data_reserva = date("Y-m-d", strtotime("now"));
                    $rl->telefone_pessoa = Helper::apenasNumeros($_POST['nrTelefone']);
                    $rl->nome_pessoa = $_POST['nmCliente'];
                    $rl->cpf_pessoa = $_POST['nrCpf'];
                    $rl->observacao = $_POST['dsObservacao'];
                    $rl->save();

                    $transacaoReservaLote->commit();

                    $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "painelVenda"),
                        array("msg" => "ok", "idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'],
                            "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'], "statusAtual" => $_GET['statusAtual']));
                } else {
                    $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "painelVenda"),
                        array('permissao' => 'O lote selecionado já foi reservado!',"idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'],
                            "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'], "statusAtual" => $_GET['statusAtual']));
                }
            } catch (Exception $e) {
                $transacaoReservaLote->rollBack();
                $this->redir(array("modulo" => "dashboard", "controller" => "dashboard"),
                    array('permissao' => $e->getMessage(), "idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'],
                        "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'], "statusAtual" => $_GET['statusAtual']));
            }
        }

        $this->display('reservarLote');
    }

    //cancelar lote
    public function cancelarReservaAction()
    {
        $this->set('infoLote', $this->lote->getLotesByIdLote($_GET['idLote']));

        if ($this->_isPost) {
            try {
                $transacaoReservaLote = $this->reservaLote->getAdapter();
                $transacaoReservaLote->beginTransaction();

                $rl = $this->reservaLote->getReservaLote($_GET['idReserva']);
                $rl->reservado = 0;
                $rl->data_cancelamento = date("Y-m-d");
                $rl->obs_cancelamento = $_POST['obs_cancelamento'];
                $rl->id_usuario_cancelou = Login::getUsuario()->getId();
                $rl->save();

                $transacaoReservaLote->commit();

                $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "painelVenda"),
                    array("msg" => "ok", "idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'],
                        "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'], "statusAtual" => $_GET['statusAtual']));
            } catch (Exception $e) {
                $transacaoReservaLote->rollBack();
                $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "reservarLote"),
                    array('msg' => 'fail', 'exception' => $e->getMessage()));
            }

        }

        $this->display('cancelarReserva');

    }

    /**
     * Função responsável por realizar a venda do lote.
     */
    public function venderLoteAction()
    {
        $this->set('listaContas', $this->banco->listarContasEmpreendimento($_GET['idEmpreedimento']));
        $this->set('parametroComissao', $this->parametroComissao->getParametroEmpreendimento($_GET['idEmpreedimento']));

        $infoLote = $this->lote->getLotesByIdLote($_GET['idLote'], $_GET['tabelaPrecoAtual']);

        $this->set('infoLote', $infoLote);

        $this->set('listaCorretorEmpreendimento', $this->corretorEmpreendimento->getCorretoresByIdEmpreendimento($_GET['idEmpreedimento']));

        //Cria a lista de indicações com o nome do indicado e do indicador
        $indicacoes = Indicacao::getIndicacoesByEmpreendimento($_GET['idEmpreedimento']);
        $auxIndicacoes = array();
        foreach ($indicacoes as $i) {
            $pessoa = new Pessoa;
            if ($i['id_cliente'] != '') {
                $i['indicadoPor'] = $pessoa->findNomeById($i['id_cliente']);
            } else {
                $i['indicadoPor'] = $pessoa->findNomeById($i['id_usuario']);
            }
            $auxIndicacoes[] = $i;
        }
        $indicacoes = $auxIndicacoes;
        $this->set('indicacoes', $indicacoes);

        if ($this->_isPost) {

            if ($this->validar()) {

                //verifica se o lote já não foi cadastrado
                if (!$this->contrato->findContratoSemDistrato($_GET['idLote'])) {

                    $adicionais = false;
                    if (isset($_POST['vlNormalAdicional']) && isset($_POST['qtNormalAdicional']))
                        foreach ($_POST['vlNormalAdicional'] as $k => $adicional)
                            if ($adicional && $_POST['qtNormalAdicional'][$k])
                                $adicionais = true;
                    if (isset($_POST['vlIntercaladaAdicional']) && isset($_POST['qtIntercaladaAdicional']))
                        foreach ($_POST['vlIntercaladaAdicional'] as $k => $adicional)
                            if ($adicional && $_POST['qtIntercaladaAdicional'][$k])
                                $adicionais = true;
                    if (isset($_POST['vlChaveAdicional']) && isset($_POST['qtChaveAdicional']))
                        foreach ($_POST['vlChaveAdicional'] as $k => $adicional)
                            if ($adicional && $_POST['qtChaveAdicional'][$k])
                                $adicionais = true;

                    //Cria um array com todos os dados para inclusao do contrato
                    $dados = array('id_lote' => $this->_helper->filters($this->_helper->filters($_GET['idLote'])),
                        'id_pessoa' => $this->_helper->filters($_POST['idCliente']),
                        'id_banco' => $this->_helper->filters($_POST['cdBanco']),
                        'id_corretor' => $this->_helper->filters($_POST['idCorretor']),
                        'nr_proposta' => $this->_helper->filters($_POST['nrProposta']),
                        'desconto' => $this->_helper->filters($_POST['vlDesconto'], 'money'),
                        'fl_aprovar_contrato' => 0,
                        'fl_itens_contrato' => ($adicionais) ? '1' : '0',
                        'dt_contrato' => date('Y-m-d'),
                        'dt_reajuste' => $this->_helper->filters($_POST['dtParcelaNormal'], 'date'),
                        //Parâmetro de comissão
                        'id_parametro_comissao' => $this->_helper->filters($_POST['parametroComissao']),

                        //Parcelas Normais
                        'vl_parcela' => $this->_helper->filters($_POST['vlParcelaNormal'], 'money'),
                        'nr_parcela' => $this->_helper->filters($_POST['qtdParcelaNormal']),
                        'dt_primeira_parcela' => $this->_helper->filters($_POST['dtParcelaNormal'], 'date'),

                        //Parcelas Sinais
                        'vl_sinal' => $this->_helper->filters($_POST['vlSinal'], 'money'),
                        'nr_parcela_sinal' => $this->_helper->filters($_POST['qtdSinal']),
                        'dt_sinal' => $this->_helper->filters($_POST["data_1"], 'date'),

                        //Parcelas Intercaladas
                        'vl_intercalada' => $this->_helper->filters($_POST['vlIntercalada'], 'money'),
                        'nr_intercalada' => $this->_helper->filters($_POST['qtdIntercalada']),
                        'fr_intercalada' => $this->_helper->filters($_POST['nrFrequencia']),
                        'dt_intercalada' => $this->_helper->filters($_POST['dtIntercalada'], 'date'),

                        //Parcelas Chaves
                        'vl_parcela_entrega' => $this->_helper->filters($_POST['vlChave'], 'money'),
                        'nr_parcela_entrega' => $this->_helper->filters($_POST['qtdParcChave']),
                        'dt_parcela_entrega' => $this->_helper->filters($_POST['dtParcChave'], 'date'),

                        //Reajuste das parcelas
                        'fl_reajustavel_mensais' => isset($_POST['fl_reajustavel_mensais']) ? 1 : 0,
                        'fl_reajustavel_intercaladas' => isset($_POST['fl_reajustavel_intercaladas']) ? 1 : 0,
                        'fl_reajustavel_chaves' => isset($_POST['fl_reajustavel_chaves']) ? 1 : 0,

                        'log' => Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:m:s') . ' - i');

                    //Se não tiver sido preenchido todos campos das parcelas sinais não salva o flIncluirContratoSinal
                    if ($_POST['qtdSinal'] && $_POST['vlSinal'] && $_POST["data_1"]) {
                        $dados['inclui_sinal_contrato'] = $this->_helper->filters($_POST['flIncluirContratoSinal']);
                    }

                    //Se não tiver sido preenchido todos campos das parcelas intercaladas não salva o flCoincidirIntercaladas
                    if ($_POST['vlIntercalada'] && $_POST['qtdIntercalada'] && $_POST['nrFrequencia'] && $_POST['dtIntercalada']) {
                        $dados['fl_coinc_intercalada'] = $this->_helper->filters($_POST['flCoincidirIntercaladas']);
                    }

                    try {

                        $transaction = $this->contrato->getAdapter();

                        $transaction->beginTransaction();

                        $idContrato = $this->contrato->cadastrarContrato($dados);

                        if ($_POST['outrosCompradores']) {
                            foreach ($_POST['outrosCompradores'] as $cdOutroComprador) {

                                $dados = array('id_contrato' => $this->_helper->filters($idContrato),
                                    'id_pessoa' => $this->_helper->filters($cdOutroComprador));

                                $this->contratoPessoa->addContratoPessoa($dados);
                            }
                        }

                        //Cria os registros das parcelas adicionais (normais, intercaladas e chave)
                        if (isset($_POST['vlNormalAdicional']) && isset($_POST['qtNormalAdicional'])) {
                            foreach ($_POST['vlNormalAdicional'] as $k => $adicional) {
                                if ($adicional && $_POST['qtNormalAdicional'][$k]) {
                                    {
                                        $itemContrato = new ContratoItens();
                                        $itemContrato = $itemContrato->createRow();
                                        $itemContrato->id_contrato = $idContrato;
                                        $itemContrato->tp_parcela = 'N';
                                        $itemContrato->vl_parcela = $this->_helper->filters($adicional, 'money');
                                        $itemContrato->qt_parcelas = $this->_helper->filters($_POST['qtNormalAdicional'][$k]);
                                        $itemContrato->fl_reajustavel = (isset($_POST['reajustavelNormalAdicional'][$k]) && $_POST['reajustavelNormalAdicional'] == 'on') ? '1' : '0';
                                        $itemContrato->ds_log = (Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:m:s') . ' - i');
                                        $itemContrato->save();
                                    }
                                }
                            }
                        }
                        if (isset($_POST['vlIntercaladaAdicional']) && isset($_POST['qtIntercaladaAdicional'])) {
                            foreach ($_POST['vlIntercaladaAdicional'] as $k => $adicional) {
                                if ($adicional && $_POST['qtIntercaladaAdicional'][$k]) {
                                    $itemContrato = new ContratoItens();
                                    $itemContrato = $itemContrato->createRow();
                                    $itemContrato->id_contrato = $idContrato;
                                    $itemContrato->tp_parcela = 'I';
                                    $itemContrato->vl_parcela = $this->_helper->filters($adicional, 'money');
                                    $itemContrato->qt_parcelas = $this->_helper->filters($_POST['qtIntercaladaAdicional'][$k]);
                                    $itemContrato->dt_parcela = $this->_helper->filters($_POST['dtIntercaladaAdicional'][$k], 'date');
                                    $itemContrato->nr_frequencia = $this->_helper->filters($_POST['nrIntercaladaAdicional'][$k]);
                                    $itemContrato->fl_reajustavel = (isset($_POST['reajustavelIntercaladaAdicional'][$k]) && $_POST['reajustavelIntercaladaAdicional'] == 'on') ? '1' : '0';
                                    $itemContrato->ds_log = (Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:m:s') . ' - i');
                                    $itemContrato->save();
                                }
                            }
                        }
                        if (isset($_POST['vlChaveAdicional']) && isset($_POST['qtChaveAdicional'])) {
                            foreach ($_POST['vlChaveAdicional'] as $k => $adicional) {
                                if ($adicional && $_POST['qtChaveAdicional'][$k]) {
                                    $itemContrato = new ContratoItens();
                                    $itemContrato = $itemContrato->createRow();
                                    $itemContrato->id_contrato = $idContrato;
                                    $itemContrato->tp_parcela = 'C';
                                    $itemContrato->vl_parcela = $this->_helper->filters($adicional, 'money');
                                    $itemContrato->qt_parcelas = $this->_helper->filters($_POST['qtChaveAdicional'][$k]);
                                    $itemContrato->fl_reajustavel = (isset($_POST['reajustavelChaveAdicional'][$k]) && $_POST['reajustavelChaveAdicional'] == 'on') ? '1' : '0';
                                    $itemContrato->ds_log = (Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:m:s') . ' - i');
                                    $itemContrato->save();
                                }
                            }
                        }

                        //Fecha a indicação selecionada ao criar o contrato
                        if (isset($_POST['idIndicacao']) && $_POST['idIndicacao'] != '') {
                            $indicacao = new Indicacao();
                            $indicacao = $indicacao->fetchRow('id_indicacao = ' . $_POST['idIndicacao']);
                            $indicacao->id_contrato = $idContrato;
                            $indicacao->dt_fechamento = $this->_helper->filters(date('d/m/Y'), 'date');
                            $indicacao->fl_status = 0;
                            $indicacao->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');
                            $indicacao->save();
                        }

                        for ($i = 1; $i <= $_POST['qtdSinal']; $i++) {
                            $rowSinal = $this->sinal->createRow();
                            $rowSinal->valor = $this->_helper->filters($_POST["valor_$i"], 'money');
                            $rowSinal->dtVencimento = $this->_helper->filters($_POST["data_$i"], 'date');
                            $rowSinal->parcela = $i;
                            $rowSinal->idContrato = $idContrato;
                            $rowSinal->save();
                        }

                        //Verifica se existem reservas do lote para serem canceladas
                        $idLote = $_GET['idLote'];
                        $reserva = $this->reservaLote->fetchRow('cod_lote = ' . $idLote . ' AND reservado = 1');

                        if($reserva) {
                            $reserva->reservado = 0;
                            $reserva->save();
                        }

                        $transaction->commit();

                        $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "painelVenda"), array("idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'], "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'], "statusAtual" => $_GET['statusAtual']));

                    } catch (Exception $e) {
                        $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "venderLote"), array('msg' => 'fail', 'idLote' => $_GET['idLote'], 'idEmpreedimento' => $_GET['idEmpreedimento'], 'exception' => $e->getMessage()));
                    }

                } else {
                    $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", "action" => "venderLote"), array('msg' => 'fail', 'idLote' => $_GET['idLote'], 'idEmpreedimento' => $_GET['idEmpreedimento'], 'exception' => 'O lote ' . $infoLote['lote'] . 'já está vendido'));
                }
            }
        }

        $this->display('venderLote');

    }


    /**
     * Função responsável por gerar a proposta de venda em PDF
     */
    public function gerarPropostaAction()
    {
        $cliente = $this->cliente->getCliente();

        $this->set('clienteAcade', array($cliente, $this->cidade->getCidadeById($cliente['cd_municipio'])));

        $infoLote = $this->lote->getLotesByIdLote($_GET['idLote']);

        //Busca os dados de logo do empreendimento
        $idEmpreendimento = $infoLote['idEmpreendimento'];
        $logoEmpreendimento = $this->logoEmpreendimento->fetchRow('id_empreendimento = ' . $idEmpreendimento);
        $imageLogo = $logoEmpreendimento['ds_path_logo'];

        //Busca os dados de parceria do empreendimento
        $parcerias = $this->empreendimento->getParcerias($idEmpreendimento);
        $infoLote['parcerias'] = $parcerias;

        $sinal = new Sinal();
        $sinal = $sinal->fetchAll('idContrato = ' . $infoLote['idContrato']);

        $infoLote['sinal'] = $sinal;

        $infoLote['qtdParcSinal'] = ($infoLote['nrParcelaSinalC'] > 0) ? $infoLote['nrParcelaSinalC'] : '0';
        $infoLote['vlTotalParcSinal'] = ($infoLote['vlSinalC']) ? ($infoLote['vlSinalC']) : '0,00';
        $infoLote['dtParcSinal'] = ($infoLote['dtSinalC']) ? Helper::getDate($infoLote['dtSinalC']) : '-----';

        $valorAdicional = 0;
        if ($infoLote['flAdicionais'] == '1') {
            $parcelasAdicionais = ContratoItens::getInstance()->fetchAll('id_contrato = ' . $infoLote['idContrato']);
            if ($parcelasAdicionais) {
                foreach ($parcelasAdicionais as $item) {
                    $valorAdicional += (float)$item->vl_parcela * (int)$item->qt_parcelas;
                    $infoLote['adicionais'][$item->tp_parcela][] = $item->toArray();
                    if (!isset($infoLote['adicionaisValor'][$item->tp_parcela]))
                        $infoLote['adicionaisValor'][$item->tp_parcela] = 0;
                    $infoLote['adicionaisValor'][$item->tp_parcela] += (float)$item->vl_parcela * (int)$item->qt_parcelas;
                }
            }
        }

        $infoLote['qtdParcEntrega'] = ($infoLote['nrParcelaEntregaC'] > 0) ? $infoLote['nrParcelaEntregaC'] : '0';
        $infoLote['vlParcEntrega'] = ($infoLote['vlParcelaEntregaC']) ? ($infoLote['vlParcelaEntregaC']) : '0,00';
        $infoLote['vlTotalParcEntrega'] = ($infoLote['vlParcelaEntregaC'] && $infoLote['nrParcelaEntregaC']) ? ($infoLote['vlParcelaEntregaC'] * $infoLote['nrParcelaEntregaC']) : '0,00';
        if (isset($infoLote['adicionaisValor']['C']))
            $infoLote['vlTotalParcEntrega'] += $infoLote['adicionaisValor']['C'];
        $infoLote['dtParcEntrega'] = ($infoLote['dtParcelaEntregaC']) ? Helper::getDate($infoLote['dtParcelaEntregaC']) : '-----';

        $infoLote['qtdParcIntercalada'] = ($infoLote['nrIntercaladaC'] > 0) ? $infoLote['nrIntercaladaC'] : '0';
        $infoLote['vlParcIntercalada'] = ($infoLote['vlIntercaladaC']) ? ($infoLote['vlIntercaladaC']) : '0,00';
        $infoLote['vlTotalParcIntercalada'] = ($infoLote['vlIntercaladaC'] && $infoLote['nrIntercaladaC']) ? ($infoLote['vlIntercaladaC'] * $infoLote['nrIntercaladaC']) : '0,00';
        if (isset($infoLote['adicionaisValor']['I']))
            $infoLote['vlTotalParcIntercalada'] += $infoLote['adicionaisValor']['I'];
        $infoLote['dtParcIntercalada'] = ($infoLote['dtIntercaladaC']) ? Helper::getDate($infoLote['dtIntercaladaC']) : '-----';

        $infoLote['qtdParcNormal'] = ($infoLote['nrParcelaC'] > 0) ? $infoLote['nrParcelaC'] : '0';
        $infoLote['vlParcNormal'] = ($infoLote['vlParcelaC']) ? ($infoLote['vlParcelaC']) : '0,00';
        $infoLote['vlTotalParcNormal'] = ($infoLote['vlParcelaC'] && $infoLote['nrParcelaC']) ? ($infoLote['vlParcelaC'] * $infoLote['nrParcelaC']) : '0,00';
        if (isset($infoLote['adicionaisValor']['N']))
            $infoLote['vlTotalParcNormal'] += $infoLote['adicionaisValor']['N'];
        $infoLote['dtParcNormal'] = ($infoLote['dtPrimeiraParcelaC']) ? Helper::getDate($infoLote['dtPrimeiraParcelaC']) : '-----';

        $infoLote['vlTotalProposta'] = ((float)$infoLote['vlTotalParcNormal'] + (float)$infoLote['vlTotalParcIntercalada'] + (float)$infoLote['vlTotalParcEntrega'] + (float)$infoLote['vlTotalParcSinal']);

        $this->set('infoLote', $infoLote);
        $this->set('imageLogo', $imageLogo);

        $this->displayMPDF('relatorio_de_comissao_' . date('d/m/Y') . '.pdf');

    }

    /**
     * Função responsável pelas validações
     *
     */
    private function validar()
    {

        //Verificando se as datas das parcelas estão corretas
        if ($_POST['qtdParcelaNormal'] && $_POST['dtParcelaNormal'] && $_POST['qtdIntercalada'] && $_POST['dtIntercalada'] && $_POST['nrFrequencia'] == 1 && $_POST['flCoincidirIntercaladas'] == 0) {

            $listaParcNormal = Contrato::getParcelas($_POST['dtParcelaNormal'], $_POST['qtdParcelaNormal']);
            $listaParcInterc = Contrato::getParcelas($_POST['dtIntercalada'], $_POST['qtdIntercalada']);

            $primeiraParcNormal = strtotime(current($listaParcNormal));
            $ultimaParcNormal = strtotime(end($listaParcNormal));
            $primeiraParcInterc = strtotime(current($listaParcInterc));
            $ultimaParcInterc = strtotime(end($listaParcInterc));

            if ($primeiraParcInterc < $primeiraParcNormal && $primeiraParcNormal <= $ultimaParcInterc) {
                $this->_helper->addMensagem('Data da 1ª Parcela', 'A primeira data das parcelas normais não pode ser menor ou igual a ultima data das parcelas intercaladas, quando a frequencia é 1 e a opção de não coincidir parcelas estiver marcada.');
            } else if ($primeiraParcNormal < $primeiraParcInterc && $primeiraParcInterc <= $ultimaParcNormal) {
                $this->_helper->addMensagem('Data da 1ª Intercalada', 'A primeira data das parcelas intercaladas não pode ser menor ou igual a ultima data das parcelas normais, quando a frequencia é 1 e a opção de não coincidir parcelas estiver marcada.');
            } else if ($primeiraParcNormal == $primeiraParcInterc) {
                $this->_helper->addMensagem('Data da 1ª Parcela', 'As primeiras datas das parcelas intercaladas e normais não podem ser iguais, quando a frequencia é 1 e a opção de não coincidir parcelas estiver marcada.');
            }
        }

        if (count($_POST) && is_array($_POST)) {
            $campos = $_POST;

            foreach ($campos as $ind => $campo) {

                if ($_POST[$ind] == "") {
                    switch ($ind) {
                        case 'cdBanco' :
                            $msg = "Selecione algum Banco.";
                            $this->_helper->addMensagem('Banco', $msg);
                            break;
                        case 'idCliente' :
                            $msg = "Selecione algum cliente.";
                            $this->_helper->addMensagem('Cliente', $msg);
                            break;
                        case 'idCorretor' :
                            $msg = "Selecione algum corretor.";
                            $this->_helper->addMensagem('Corretor', $msg);
                            break;
                    }
                }

                if ($_POST[$ind]) {
                    switch ($ind) {
                        case 'vlIntercalada':
                            $_POST['qtdIntercalada'] = (strlen(trim($_POST['qtdIntercalada'])) != 0 ? (int)$_POST['qtdIntercalada'] : NULL);
                            $_POST['nrFrequencia'] = (strlen(trim($_POST['nrFrequencia'])) != 0 ? (int)$_POST['nrFrequencia'] : NULL);
                            $_POST['qtdParcelaNormal'] = (int)$_POST['qtdParcelaNormal'];
                            if ($_POST['qtdIntercalada'] === 0 || $_POST['nrFrequencia'] === 0) {
                                $msg = "A quantidade de parcela e a frequencia tem que ser numérico e maior que 0 (zero).";
                                $this->_helper->addMensagem('Valor da Parc. Intercalada', $msg);
                            } elseif ($_POST['qtdIntercalada'] === NULL || $_POST['nrFrequencia'] === NULL) {
                                $msg = "Ao preencher a intercalada, é necessário que preencha quantidade de parcela e frequência.";
                                $this->_helper->addMensagem('Valor da Parc. Intercalada', $msg);
                            }
                            break;

                        case 'vlSinal':
                            $_POST['qtdSinal'] = (strlen(trim($_POST['qtdSinal'])) != 0 ? (int)$_POST['qtdSinal'] : NULL);
                            $_POST['qtdParcelaNormal'] = (int)$_POST['qtdParcelaNormal'];
                            if ($_POST['qtdSinal'] == 0) {
                                $msg = "A quantidade de parcela tem que ser numérico e maior que 0 (zero).";
                                $this->_helper->addMensagem('Valor da Parc. Sinal', $msg);
                            } elseif ($_POST['qtdSinal'] === NULL) {
                                $msg = "Ao preencher o sinal, é necessario que preencha quantidade de parcelas.";
                                $this->_helper->addMensagem('Valor da Parc. Sinal', $msg);
                            }
                            if ($_POST['qtdSinal']) {
                                //compara se a soma das parcelas bate com o total do sinal
                                $sinal = Helper::getInputMoney($_POST['vlSinal']);

                                $valor = 0;
                                for ($i = 1; $i <= $_POST['qtdSinal']; $i++) {
                                    $valor += Helper::getInputMoney($_POST['valor_' . $i]);
                                }

                                if ((string)$valor != (string)$sinal) {
                                    $msg = "A soma dos valores parcelados do sinal não confere com o valor total do sinal.";
                                    $this->_helper->addMensagem('Valor da Parc. Sinal', $msg);
                                }
                                $msg = "Verifique as datas das parcelas de nº: ";
                                for ($i = 1; $i <= $_POST['qtdSinal']; $i++) {
                                    $data = explode("/", $_POST["data_$i"]);
                                    $d = $data[0];
                                    $m = $data[1];
                                    $y = $data[2];

                                    if (!checkdate($m, $d, $y)) {
                                        $msg = $msg . "$i,";
                                    }
                                }

                                $msg = rtrim($msg, ",");

                                if ($msg <> "Verifique as datas das parcelas de nº: ") $this->_helper->addMensagem('Valor da Parc. Sinal', $msg);
                            }

                            break;

                        case 'vlChave':
                            $_POST['qtdParcChave'] = (strlen(trim($_POST['qtdParcChave'])) != 0 ? (int)$_POST['qtdParcChave'] : NULL);
                            $_POST['qtdParcelaNormal'] = (int)$_POST['qtdParcelaNormal'];
                            if ($_POST['vlChave'] && $_POST['qtdParcChave'] > 0 && $_POST['vlParcelaNormal'] && $_POST['qtdParcelaNormal'] > 0) {
                                $msg = 'A quantidade de parcela de chaves tem que ser menor ou igual a quantidade de parcela do valor normal.';
                                $_POST['qtdParcChave'] > $_POST['qtdParcelaNormal'] ? $this->_helper->addMensagem($ind, $msg) : '';
                            } elseif ($_POST['qtdParcChave'] === 0) {
                                $msg = "A quantidade de parcela tem que ser numericos e maior que 0 (zero).";
                                $this->_helper->addMensagem('Valor da Parc. Chave', $msg);
                            } elseif ($_POST['qtdParcChave'] === NULL) {
                                $msg = "Ao preencher o valor das chaves, é necessario que preencha quantidade de parcela.";
                                $this->_helper->addMensagem('Valor da Parc. Chave', $msg);
                            }
                            break;

                        case 'dtParcelaNormal':
                            if (isset($_POST['dtParcelaNormal']) && (strtotime(Helper::getInputDate($_POST['dtParcelaNormal'])) < strtotime(Helper::getInputDate(isset($_POST['data']))))) {

                                $msg = "A data da 1ª parcela tem que ser maior ou igual a data de contrato.";

                                $this->_helper->addMensagem('Data da Parc. Normal', $msg);
                            }
                            break;

                        case 'vlParcelaNormal':
                            if ($_POST['vlParcelaNormal'] == '0,00') {
                                $msg = "Valor da parcela inválida.";
                                $this->_helper->addMensagem('Valor da Parc. Normal', $msg);
                            }
                            break;

                        case 'qtdParcelaNormal':
                            if ($_POST['qtdParcelaNormal'] < 1) {
                                $msg = "Quantidade de parcela tem que ser maior que 0 (zero).";
                                $this->_helper->addMensagem('Quantidade de Parc. Normal', $msg);
                            }
                            break;
                    }
                }
            }
        }

        return !(count($this->_helper->getMensagens()) > 0);
    }

    /**
     * Faz validação das datas das parcelas normais e intercaladas via ajax
     */
    public function verificaUltimaDataAction()
    {

        $listaParcNormal = Contrato::getParcelas($_POST['dtParcelaNormal'], $_POST['qtdParcelaNormal']);
        $listaParcInterc = Contrato::getParcelas($_POST['dtIntercalada'], $_POST['qtdIntercalada']);

        $primeiraParcNormal = strtotime(current($listaParcNormal));
        $ultimaParcNormal = strtotime(end($listaParcNormal));
        $primeiraParcInterc = strtotime(current($listaParcInterc));
        $ultimaParcInterc = strtotime(end($listaParcInterc));
        $erro = true;

        if ($primeiraParcInterc < $primeiraParcNormal && $primeiraParcNormal <= $ultimaParcInterc) {
            $msg = 'A data da <b>primeira <u>parcela normal</u></b> não pode ser menor ou igual a <b>ultima data das <u>parcelas intercaladas</u></b>, quando a <b>frequencia é <u>1</u></b> e a opção de <b>coincidir parcelas for <u>não</u></b>.';
        } else if ($primeiraParcNormal < $primeiraParcInterc && $primeiraParcInterc <= $ultimaParcNormal) {
            $msg = 'A data da <b>primeira <u>parcela intercalada</u></b> não pode ser menor ou igual a <b>ultima data das <u>parcelas normais</u></b>, quando a <b>frequencia é <u>1</u></b> e a opção de <b>coincidir parcelas for <u>não</u></b>.';
        } else if ($primeiraParcNormal == $primeiraParcInterc) {
            $msg = 'A data da <b>primeira <u>parcela intercalada</u></b> não pode ser igual a data da <b>primeira <u>parcela normal</u></b>, quando a <b>frequencia é <u>1</u></b> e a opção de <b>coincidir parcelas for <u>não</u></b>.';
        } else {
            $erro = false;
        }

        echo json_encode(array('erro' => $erro, 'mensagem' => $msg));
    }

    /**
     * Recebe o CPF via ajax e pesquisa na tabela de pessoa fisica e retorna as informações da pessoa ou
     */
    public function verificaCpfCnpjAction()
    {
        if (isset($_POST['nrCpf'])) {

            $pessoa = $this->pessoaFisica->findPessoaFisicaByCpf($_POST['nrCpf']);

            if ($pessoa) {
                $pessoa['dt_nascimento'] = Helper::getDate($pessoa['dt_nascimento']);
                $pessoa['enderecoConjuge'] = ($pessoa['nr_cep'] || $pessoa['endereco'] || $pessoa['nm_bairro'] || $pessoa['cd_cidade']) ? true : false;
                $pessoa['valid'] = false;
            } else {
                $pessoa = array('valid' => true);
            }
        } else {

            $pessoa = $this->pessoaJuridica->findPessoaJuridicaByCnpj($_POST['nrCnpj']);

            if ($pessoa) {
                $pessoa = array('valid' => false);
            } else {
                $pessoa = array('valid' => true);
            }

        }

        echo json_encode($pessoa);
    }

    /**
     * Cadastra a pessoa física com todas informações recebidas via ajax
     */
    public function cadastrarPessoaAction()
    {

        try {

            $transaction = $this->pessoa->getAdapter();

            $transaction->beginTransaction();

            //Pessoa
            $dadosPessoa = array(
                'nm_pessoa' => $this->_helper->filters($_POST["nmPessoa"]),
                'email' => $this->_helper->filters($_POST["email"]),
                'nr_telefone' => $this->_helper->filters($_POST["nrTelefone"]),
                'nr_celular' => $this->_helper->filters($_POST["nrCelular"]),
                'nr_fax' => isset($_POST["nrFax"]) ? $this->_helper->filters($_POST["nrFax"]) : null,
                'nr_recado' => $this->_helper->filters($_POST["nrRecado"]),
                'nr_cep' => $this->_helper->filters($_POST["nrCep"]),
                'endereco' => $this->_helper->filters($_POST["endereco"]),
                'nm_bairro' => $this->_helper->filters($_POST["nmBairro"]),
                'ds_referencia' => isset($_POST["dsReferencia"]) ? $this->_helper->filters($_POST["dsReferencia"]) : null,
                'ds_observacao' => $this->_helper->filters($_POST["dsObservacao"]),
                'fl_status' => 1,
                'tp_pessoa' => ($_POST['tpCadastro'] == 'PF') ? 'F' : 'J',
                'dt_cadastro' => date("Y-m-d H:i:s"),
                'cd_cidade' => $this->_helper->filters($_POST["cdCidade"]),
                'log' => (Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s') . ' - i'));

            $idPessoa = $this->pessoa->savePessoa($dadosPessoa);

            if ($_POST['tpCadastro'] == 'PF') {

                if ($_POST["estCivil"] == 'CAS') {

                    //pessoa do conjuge
                    $dadosPessoaConjuge = array(
                        'nm_pessoa' => $this->_helper->filters($_POST["nmPessoaConjuge"]),
                        'email' => null,
                        'nr_telefone' => $this->_helper->filters($_POST["nrTelefoneConjuge"]),
                        'nr_celular' => isset($_POST["nrCelularConjuge"]) ? $this->_helper->filters($_POST["nrCelularConjuge"]) : null,
                        'nr_fax' => isset($_POST["nrFaxConjuge"]) ? $this->_helper->filters($_POST["nrFaxConjuge"]) : null,
                        'nr_recado' => isset($_POST["nrRecadoConjuge"]) ? $this->_helper->filters($_POST["nrRecadoConjuge"]) : null,
                        'ds_referencia' => null,
                        'ds_observacao' => null,
                        'fl_status' => 1,
                        'tp_pessoa' => "F",
                        'dt_cadastro' => date("Y-m-d H:i:s"),
                        'log' => (Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s') . ' - i'),
                        'nr_cep' => ($_POST['utilizarEnderecoConjuge'] == 1) ? $this->_helper->filters($_POST["nrCep"]) : $this->_helper->filters($_POST["nrCepConjuge"]),
                        'endereco' => ($_POST['utilizarEnderecoConjuge'] == 1) ? $this->_helper->filters($_POST["endereco"]) : $this->_helper->filters($_POST["enderecoConjuge"]),
                        'nm_bairro' => ($_POST['utilizarEnderecoConjuge'] == 1) ? $this->_helper->filters($_POST["nmBairro"]) : $this->_helper->filters($_POST["nmBairroConjuge"]),
                        'cd_cidade' => ($_POST['utilizarEnderecoConjuge'] == 1) ? $this->_helper->filters($_POST["cdCidade"]) : $this->_helper->filters($_POST["cdCidadeConjuge"]));

                    $idPessoaConjuge = $this->pessoa->savePessoa($dadosPessoaConjuge);

                    //pessoa fisica do conjuge
                    $dadosPessoaFisicaConjuge = array(
                        'id_pessoa' => $idPessoaConjuge,
                        'nr_cpf' => $this->_helper->filters($_POST["nrCpfConjuge"]),
                        'dt_nascimento' => $this->_helper->filters($_POST["dtNascimentoConjuge"], 'date'),
                        'nm_profissao' => $this->_helper->filters($_POST["nmProfissaoConjuge"]),
                        'nm_nacionalidade' => $this->_helper->filters($_POST["nmNacionalidadeConjuge"]),
                        'nm_pai' => null,
                        'nm_mae' => null,
                        'tp_pessoa' => 'F',
                        'cd_cidade_nasc' => null,
                        'sexo' => $this->_helper->filters($_POST["sexoConjuge"]),
                        'est_civil' => 'CAS',
                        'nr_rg' => $this->_helper->filters($_POST["nrRgConjuge"]),
                        'cd_documento' => $this->_helper->filters($_POST["tpDocumentoConjuge"]),
                        'tp_nacionalidade' => $this->_helper->filters($_POST["estrangeiroConjuge"]));

                    $idPessoaFisicaConjuge = $this->pessoaFisica->savePessoaFisica($dadosPessoaFisicaConjuge);
                }

                //Pessoa Fisica
                $dadosPessoaFisica = array(
                    'id_pessoa' => $idPessoa,
                    'nr_cpf' => $this->_helper->filters($_POST["nrCpf"]),
                    'dt_nascimento' => $this->_helper->filters($_POST["dtNascimento"], 'date'),
                    'nm_profissao' => $this->_helper->filters($_POST["nmProfissao"]),
                    'nm_nacionalidade' => $this->_helper->filters($_POST["nmNacionalidade"]),
                    'nm_pai' => $this->_helper->filters($_POST["nmPai"]),
                    'nm_mae' => $this->_helper->filters($_POST["nmMae"]),
                    'tp_pessoa' => 'F',
                    'cd_cidade_nasc' => $this->_helper->filters($_POST["cdCidadeNascimento"]),
                    'sexo' => $this->_helper->filters($_POST["sexo"]),
                    'est_civil' => $this->_helper->filters($_POST["estCivil"]),
                    'nr_rg' => $this->_helper->filters($_POST["nrRg"]),
                    'cd_documento' => $this->_helper->filters($_POST["tpDocumento"]),
                    'tp_nacionalidade' => $this->_helper->filters($_POST["estrangeiro"]),
                    'cd_conjuge' => isset($idPessoaFisicaConjuge) ? $idPessoaFisicaConjuge : null);

                $idPessoaFisica = $this->pessoaFisica->savePessoaFisica($dadosPessoaFisica);

                //Atualizando o codigo do conjuge igual ao da pessoa fisica criada com o do conjuge
                if ($_POST["estCivil"] == 'CAS') {
                    $dados = array('cd_conjuge' => $idPessoaFisica);
                    $this->pessoaFisica->savePessoaFisica($dados, $idPessoaFisicaConjuge);
                }
            } else {

                $dadosPessoaJuridica = array(
                    'id_pessoa' => $idPessoa,
                    'nr_cnpj' => $this->_helper->filters($_POST['nrCnpj']),
                    'nm_fantasia' => $this->_helper->filters($_POST['nmFantasia']),
                    'nr_inscricao_estadual' => $this->_helper->filters($_POST['nrInscricaoEstadual']),
                    'nr_inscricao_municipal' => null);

                $idPessoaJuridica = $this->pessoaJuridica->savePessoaJuridica($dadosPessoaJuridica);

                if ($_POST["cdRepresentantes"]) {
                    foreach ($_POST["cdRepresentantes"] as $cdRepresentante) {
                        $dadosRepresentante = array('id_pessoa' => $cdRepresentante, 'id_pessoa_juridica' => $idPessoaJuridica);
                        $this->representante->saveRepresentante($dadosRepresentante);
                    }
                }

            }

            $transaction->commit();

            echo json_encode(array('sucess' => 1, 'idPessoa' => $idPessoa, 'nmPessoa' => $_POST["nmPessoa"]));

        } catch (Exception $e) {

            $transaction->rollBack();
            echo json_encode(array('sucess' => 0, 'erro' => $e->getMessage()));
        }

    }

    /**
     * Retorna os formulários de pessoa fisica ou juridica por ajax
     */
    public function exibeViewCadastroAction()
    {

        $this->set('listaCidades', $this->cidade->listar());

        if ($_POST['tpPessoa'] == 'F') {

            $this->set('listaEstadoCivil', $this->tabelaAgrupada->getTabelaByCodigo(3));

            $this->set('listaSexo', $this->tabelaAgrupada->getTabelaByCodigo(2));

            $estrangeiro = array(array('idCampo' => 'B', 'descricao' => 'Brasileiro'), array('idCampo' => 'E', 'descricao' => 'Estrangeiro'));
            $this->set('radioEstrangeiro', $estrangeiro);
            $this->set('radioEstrangeiroConjuge', $estrangeiro);

            $documentos = $this->tabelaAgrupada->getTabelaByCodigo(7);
            foreach ($documentos as $k => $doc) {
                if ($doc['descricao'] == 'CPF') {
                    unset($documentos[$k]);
                }
            }
            $this->set('listaDocumentos', $documentos);
            $this->set('listaDocumentosConjuge', $documentos);

            echo($this->display('pessoaFisicaForm'));

        } else {

            $this->set('listaPessoaFisica', $this->pessoaFisica->findPessoasFisicas());

            echo($this->display('pessoaJuridicaForm'));

        }

    }

    /**
     * Retorna as informações do corretor empreendimento
     */
    public function findCorretorEmpreendimentoAction()
    {
        if ($_POST['idCorretor'] && $_POST['idEmpreendimento']) {
            $infoCorretorEmpreendimento = $this->corretorEmpreendimento->findCorretorEmpreendimento($_POST['idCorretor'], $_POST['idEmpreendimento']);
            echo json_encode(($infoCorretorEmpreendimento) ? true : false);
        }
    }

    /**
     * Retorna a lista de quadras de um empreendimento
     */
    public function listaQuadraEmpreendimentoAction()
    {
        if ($_POST['idEmpreendimento']) {
            $jsonTabelasPreco = array();
            $tabelasPrecoEmpreendimento = $this->empreendimento->listaTabelaPrecoEmpreendimento($_POST['idEmpreendimento']);

            if ($tabelasPrecoEmpreendimento) foreach ($tabelasPrecoEmpreendimento as $tabelaPreco) {
                $jsonTabelasPreco[] = array('id' => $tabelaPreco['id_tabela_preco'], 'text' => $tabelaPreco['nm_tabela']);
            }

            $jsonTabelaPadrao = '';
            $tabelaPadrao = TabelaPreco::getTabelaPadraoByEmpreendimento($_POST['idEmpreendimento']);
            if ($tabelaPadrao) {
                $jsonTabelaPadrao = $tabelaPadrao['id_tabela_preco'];
            }

            $jsonQuadraEmpreendimento[] = array('id' => '*', 'text' => 'Todos');
            $listaQuadraEmpreendimento = $this->empreendimento->listaQuadraEmpreendimento($_POST['idEmpreendimento']);

            if ($listaQuadraEmpreendimento) foreach ($listaQuadraEmpreendimento as $quadraEmpreendimento) {
                $jsonQuadraEmpreendimento[] = array('id' => $quadraEmpreendimento['quadra'], 'text' => 'Quadra ' . $quadraEmpreendimento['quadra']);
            }

            echo json_encode(array('quadra' => $jsonQuadraEmpreendimento, 'tabelaPreco' => $jsonTabelasPreco, 'tabelaPadrao' => $jsonTabelaPadrao));
        }
    }

    /*
     * Busca e exibe em formato json os dados necessarios para o modal de informacoes de lotes
     */
    public function listarInformacoesAction()
    {

        //Buscando a lista de lotes para exibir na tela
        $infoLote = $this->lote->getLotesByIdLote($_GET['lote'], $_GET['tabelaPrecoAtual']);

        echo json_encode(array(
            "lote" => $infoLote['lote'],
            "tamanho" => $infoLote['area'],
            "valor" => $infoLote['valor'],
            "quadra" => $infoLote['quadra'],
            "reservaTecnica" => $infoLote['reservaTecnica'],
            "nome" => ($infoLote['nmCliente']) ? $infoLote['nmCliente'] : $infoLote['nome_pessoa'],
            "corretor" => ($infoLote['nmCorretor']) ? $infoLote['nmCorretor'] : $infoLote['nmCorretorReserva'],
            "cpf" => ($infoLote['nr_cpf']) ? $infoLote['nr_cpf'] : $infoLote['cpf_pessoa'],
            "empreendimento" => $infoLote['nm_empreendimento'],
            "obsReservaTecnica" => $infoLote['obs_reserva'],
            "loteFrente" => $infoLote['frente'],
            "loteDireito" => $infoLote['direita'],
            "loteEsquerdo" => $infoLote['esquerda'],
            "loteFundo" => $infoLote['fundo'],
            "vlTotalTabelaPreco" => $infoLote['vl_total'],
            "vlParcelaTabelaPreco" => $infoLote['vl_parcela'],
            "vlSinalTabelaPreco" => $infoLote['vl_sinal'],
            "vlIntercaladaTabelaPreco" => $infoLote['vl_intercalada'],
            "qtIntercaladaTabelaPreco" => $infoLote['qt_intercalada'],
            "dataAtualizacao" => $this->_helper->getDate($infoLote['dt_atualizacao']),
            "flStatusTabelaPreco" => isset($infoLote['flStatusTabelaPreco']) ? $infoLote['flStatusTabelaPreco'] : '',
            "rgiLote" => $infoLote['rgiLote']));
    }

    /*
     * Busca e exibe em formato json os dados necessarios para o modal de informacoes de lotes
     */
    public function findPessoasFisicasByNmPessoaAction()
    {
        $dados = array();

        //Buscando a lista de lotes para exibir na tela
        $lista = $this->pessoaFisica->findPessoasFisicasByNmPessoa($_GET['term']);

        if ($lista) foreach ($lista as $pessoaFisica) {
            $dados[] = array('text' => $pessoaFisica['nm_pessoa'] . ' (' . $pessoaFisica['nr_cpf'] . ')', 'value' => $pessoaFisica['idPessoa']);
        }

        echo json_encode($dados);
    }

    /*
     * Busca e exibe em formato json os dados necessarios para o modal de informacoes de lotes
     */
    public function findPessoasByNomeAction()
    {
        $dados = array();

        //Buscando a lista de lotes para exibir na tela
        $lista = $this->pessoa->findPessoasByNmpessoa($_GET['term']);

        if ($lista) foreach ($lista as $pessoa) {

            $nrCpfCnpj = ($pessoa['nr_cnpj']) ? $pessoa['nr_cnpj'] : $pessoa['nr_cpf'];

            $dados[] = array('text' => $pessoa['nm_pessoa'] . ' (' . $nrCpfCnpj . ')', 'value' => $pessoa['idPessoa']);
        }

        echo json_encode($dados);
    }

}
