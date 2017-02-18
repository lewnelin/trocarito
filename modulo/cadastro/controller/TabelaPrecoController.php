<?php
//Biblioteca para leitura do arquivo excel
require_once dirname(__FILE__) . '/../../../vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

class TabelaPrecoController extends Controller
{

    //Funcao publica do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->tb_tabela_preco = new TabelaPreco();
        $this->tb_tabela_preco_lotes = new TabelaPrecoLotes();
        $this->lotes = new Lote();
        $this->empreendimentos = new Empreendimento();

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {

        $this->redir(array("modulo" => "cadastro", "controller" => "tabelaPreco", 'action' => 'listar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function listarAction()
    {
        if (Helper::verificaAjax()) {
            //verifica se o usuario tem o perfil de usuario de empreendimento
            if (Login::getUsuario()) {
                $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
                $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());
            }

            //Buscando a lista de tabelas de preco
            $listaTabela = $this->tb_tabela_preco->getAdapter()->select()->from(array('ttp' => TB_TABELA_PRECO), array('id_tabela_preco', 'nm_tabela', 'ds_tabela', 'id_empreendimento', 'fl_padrao'))->joinLeft(array('ttpl' => TB_TABELA_PRECO_LOTES), 'ttp.id_tabela_preco = ttpl.id_tabela_preco', array('qt_lotes' => 'COUNT(ttpl.id_lote)', 'id_lote'))->joinLeft(array('l' => TB_LOTES), 'ttpl.id_lote = l.id', array('id_empreendimento', 'lote', 'quadra', 'area'));

            if (Login::getUsuario() && count($idsUsuarioEmpreendimento) > 0) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaTabela = $listaTabela->join(array('e' => TB_EMPREENDIMENTO), "e.id = ttp.id_empreendimento AND e.id IN({$idsUsuarioEmpreendimento})", array("nm_empreendimento"));
            } else {
                $listaTabela = $listaTabela->joinLeft(array('e' => TB_EMPREENDIMENTO), "e.id = ttp.id_empreendimento", array("nm_empreendimento"));
            }
            $listaTabela = $listaTabela->group('id_tabela_preco')->query()->fetchAll();

            if (count($listaTabela) > 0 && $listaTabela[0]['id_tabela_preco']) {
                foreach ($listaTabela as $tabela) {

                    $acoes = '<div align="center">';
                    $acoes .= '<a value="' . $tabela['id_tabela_preco'] . '" data-toggle="tooltip" title="VISUALIZAR" class="btn btn-default btn-xs btn-visualizar2" style="margin-right:5px"><i class="fa fa-eye"></i> </a>';
                    $acoes .= '<a href="?m=cadastro&c=tabelaPreco&a=editar&id=' . $tabela['id_tabela_preco'] . '" data-toggle="tooltip" title="EDITAR" class="btn btn-default btn-xs" style="margin-right:5px"><i class="fa fa-edit"></i> </a>';
                    $acoes .= '</div>';

                    $checkbox = '<div align="center" >';
                    $checkbox .= '<input type="checkbox" class="rows-check" value="' . $tabela['id_tabela_preco'] . '" id="' . $tabela['id_tabela_preco'] . '" nomeExibidoNoDeletar="' . $tabela['nm_tabela'] . '" name="linhas[]">';
                    $checkbox .= '</div>';

                    $padrao = ($tabela['fl_padrao'] == 1) ? '<div align="center"><span class="label label-success">Sim</span></div>' : '<div align="center"><span class="label label-danger">Não</span></div>';

                    $listaTabelas[] = array('checkbox' => $checkbox, 'id_tabela_preco' => $tabela['id_tabela_preco'], 'empreendimento' => $tabela['nm_empreendimento'], 'nm_tabela' => $tabela['nm_tabela'], 'padrao' => $padrao, 'ds_descricao' => $tabela['ds_tabela'], 'qt_lotes' => $tabela['qt_lotes'], 'acoes' => $acoes);
                }

                echo json_encode(array('draw' => 1, 'recordsTotal' => count($listaTabelas), 'recordsFiltered' => count($listaTabelas), 'data' => $listaTabelas));
            } else {
                echo json_encode(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array()));
            }


        } else {

            $this->display('listar');

        }
    }

    /*
    * Exibe tela de adicionar nova tabela de preços
    * se há requisicao ajax, faz consulta no banco e exibe listagem de quadras ou lotes
    * se há dados na global $_POST, criar nova tabela de preço e novos relacionamento entre a tabela de preço e os lotes
    */
    public function adicionarAction()
    {
        //Buscando a lista de empreendimentos para o select
        $listaEmpreendimentos = $this->empreendimentos->getEmpreendimentosUsuario();
        $this->set('listaEmpreendimentos', $listaEmpreendimentos);

        if ($this->_isPost) {
            try {
                $error = false;

                //criar tabela preço
                $transacaoTabelaPrecoCriar = $this->tb_tabela_preco->getAdapter();
                $transacaoTabelaPrecoCriar->beginTransaction();

                //Realiza a análise do arquivo para importar para a datatable
                $file = $_FILES['arquivoCadastro'];

                try {
                    $data = PHPExcel_IOFactory::load($file['tmp_name']);

                    $sheets = $data->getWorksheetIterator();
                    foreach ($sheets as $sheet) {
                        $array[] = $sheet->toArray();
                    }

                } catch (Exception $e) {
                    $error = true;
                    $transacaoTabelaPrecoCriar->rollBack();
                    $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "adicionar"), array("msgFail" => 'Não foi Possível importar o arquivo.'));
                }


                if (!$error) {

                    $tb_tb_preco = $this->tb_tabela_preco->createRow();
                    $tb_tb_preco->nm_tabela = $_POST['nm_tabela'];
                    $tb_tb_preco->ds_tabela = $_POST['ds_tabela'];
                    $tb_tb_preco->id_empreendimento = $_POST['Empreendimento'];
                    $tb_tb_preco->fl_padrao = (isset($_POST['fl_padrao']) && $_POST['fl_padrao'] == 'on') ? 1 : 0;

                    //Salva tabela de preço
                    $id_tabela_preco = $tb_tb_preco->save();

                    //Modifica a tabela padrão caso tenha sido selecionada nova tabela padrão
                    if (isset($_POST['fl_padrao']) && $_POST['fl_padrao'] == 'on') {
                        $auxTabela = $this->tb_tabela_preco->getDefaultAdapter();
                        try {
                            $auxTabela->update(array('tp' => TB_TABELA_PRECO), array('fl_padrao' => 0),
                                'id_tabela_preco != ' . $id_tabela_preco . ' AND id_empreendimento = ' . $_POST['Empreendimento']);
                        } catch (Exception $e) {
                            $transacaoTabelaPrecoCriar->rollBack();
                            $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "adicionar"), array("msgFail" => 'Não foi possível modificar tabela padrão.'));
                        }
                    }

                    $aux = array();
                    foreach ($array as $folha) {
                        foreach ($folha as $k => $lote) {
                            try {
                                $auxLote = Lote::getLotesEmpreendimentoQuadraNumero($_POST['Empreendimento'], $lote[0], $lote[1]);
                            } catch (Exception $e) {
                            }

                            if (isset($auxLote) && $auxLote) {
                                $auxLote['vl_total'] = $lote[2];
                                $auxLote['vl_sinal'] = $lote[3];
                                $auxLote['vl_parcela'] = $lote[4];
                                $auxLote['vl_intercalada'] = $lote[5];
                                $auxLote['qt_intercalada'] = $lote[6];
                                $auxLote['dt_atualizacao'] = Helper::dataAbreviadaFromExcel($lote[7]);

                                $aux[] = $auxLote;
                                $auxLote = null;
                            }
                        }
                    }


                    if (count($aux) > 0) {

                        foreach ($aux as $lote) {

                            $tb_tb_preco_lotes = $this->tb_tabela_preco_lotes->createRow();
                            $tb_tb_preco_lotes->id_tabela_preco = $id_tabela_preco;
                            $tb_tb_preco_lotes->id_lote = $lote['id'];
                            $tb_tb_preco_lotes->vl_total = $this->_helper->filters($lote['vl_total'], 'moneyExcel');
                            $tb_tb_preco_lotes->vl_sinal = $this->_helper->filters($lote['vl_sinal'], 'moneyExcel');
                            $tb_tb_preco_lotes->vl_parcela = $this->_helper->filters($lote['vl_parcela'], 'moneyExcel');
                            $tb_tb_preco_lotes->vl_intercalada = $this->_helper->filters($lote['vl_intercalada'], 'moneyExcel');
                            $tb_tb_preco_lotes->qt_intercalada = (int)$lote['qt_intercalada'];

                            if ($lote['dt_atualizacao'] == '') {
                                $tb_tb_preco_lotes->dt_atualizacao = date('Y-m-d');
                            } else {
                                $tb_tb_preco_lotes->dt_atualizacao = $lote['dt_atualizacao'];
                            }
                            $tb_tb_preco_lotes->save();
                        }


                        $transacaoTabelaPrecoCriar->commit();

                        $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "listar"), array("msgSuccess" => 'Tabela de preços ' . $_POST['nm_tabela'] . ' adicionada com sucesso.'));

                    }
                }

                $transacaoTabelaPrecoCriar->commit();

                $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "listar"), array("msgSuccess" => 'Tabela de preços ' . $_POST['nm_tabela'] . ' adicionada com sucesso.'));

            } catch (Exception $e) {

                $transacaoTabelaPrecoCriar->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "adicionar"), array("msgFail" => $e->getMessage()));

            }
        }

        $this->display("adicionar");
    }

    /*
    * Exibe tela de editar tabela de preços
    * se há dados na global $_POST, edita a tabela de preços e os relacionamento entre a tabela de preço e os lotes
    */

    public function editarAction()
    {

        if ($this->_isPost) {
            try {
                $error = false;

                //criar tabela preço
                $transacaoTabelaPrecoCriar = $this->tb_tabela_preco->getAdapter();
                $transacaoTabelaPrecoCriar->beginTransaction();

                //Realiza a análise do arquivo para importar para a datatable
                $file = $_FILES['arquivoCadastro'];

                if ($file['error'] == 0) {
                    try {
                        $data = PHPExcel_IOFactory::load($file['tmp_name']);

                        $sheets = $data->getWorksheetIterator();
                        foreach ($sheets as $sheet) {
                            $array[] = $sheet->toArray();
                        }

                    } catch (Exception $e) {
                        $error = true;
                        $transacaoTabelaPrecoCriar->rollBack();
                        $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "editar"), array("msgFail" => 'Não foi Possível importar o arquivo.', 'id' => $_POST['id_tabela_preco']));
                    }
                }

                if (!$error) {

                    //editar tabela preço
                    $tb_tb_preco = $this->tb_tabela_preco->fetchRow("id_tabela_preco=" . $_GET['id']);

                    $tb_tb_preco->nm_tabela = $_POST['nm_tabela'];
                    $tb_tb_preco->ds_tabela = $_POST['ds_tabela'];

                    if (isset($_POST['fl_padrao'])) {
                        $tb_tb_preco->fl_padrao = ($_POST['fl_padrao'] == 'on') ? 1 : 0;
                    }

                    $id_tabela_preco = $tb_tb_preco->save();

                    if (isset($_POST['lote'])) {
                        $tb_tb_preco_lote = $this->tb_tabela_preco_lotes->fetchRow("id_tabela_preco_lotes = " . $_POST['lote']);

                        if (!$tb_tb_preco_lote) {
                            $tb_tb_preco_lote = $this->tb_tabela_preco_lotes->createRow();
                            $tb_tb_preco_lote->id_tabela_preco = $id_tabela_preco;
                            $tb_tb_preco_lote->id_lote = $this->_helper->filters($_POST['lote']);
                        }

                        $tb_tb_preco_lote->vl_total = $this->_helper->filters($_POST['vl_total'], 'money');
                        $tb_tb_preco_lote->vl_parcela = $this->_helper->filters($_POST['vl_parcela'], 'money');
                        $tb_tb_preco_lote->vl_sinal = $this->_helper->filters($_POST['vl_sinal'], 'money');
                        $tb_tb_preco_lote->vl_intercalada = $this->_helper->filters($_POST['vl_intercalada'], 'money');
                        $tb_tb_preco_lote->qt_intercalada = (int)$_POST['qt_intercalada'];
                        $tb_tb_preco_lote->dt_atualizacao = $this->_helper->filters($_POST['dt_atualizacao'], 'date');
                        $tb_tb_preco_lote->save();
                    }

                    //Modifica a tabela padrão caso tenha sido selecionada nova tabela padrão
                    if (isset($_POST['fl_padrao']) && $_POST['fl_padrao'] == 'on') {
                        $auxTabela = $this->tb_tabela_preco->getDefaultAdapter();
                        try {
                            $auxTabela->update(array('tp' => TB_TABELA_PRECO), array('fl_padrao' => 0),
                                'id_tabela_preco != ' . $id_tabela_preco . ' AND id_empreendimento = ' . $_POST['empreendimentoHidden']);
                        } catch (Exception $e) {
                            $transacaoTabelaPrecoCriar->rollBack();
                            $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "listar"), array("msgFail" => 'Não foi possível modificar tabela padrão.', 'id' => $_POST['id_tabela_preco']));
                        }
                    }

                    //Se existe registros novos a serem inseridos
                    if (isset($array)) {

                        //Trata os valores das folhas da planilha caso seja selecionado um arquivo
                        $aux = array();
                        foreach ($array as $folha) {
                            foreach ($folha as $k => $lote) {
                                try {
                                    $auxLote = Lote::getLotesEmpreendimentoQuadraNumero($_POST['empreendimentoHidden'], $lote[0], $lote[1]);
                                } catch (Exception $e) {
                                }

                                if (isset($auxLote) && $auxLote) {
                                    $auxLote['vl_total'] = $lote[2];
                                    $auxLote['vl_sinal'] = $lote[3];
                                    $auxLote['vl_parcela'] = $lote[4];
                                    $auxLote['vl_intercalada'] = $lote[5];
                                    $auxLote['qt_intercalada'] = $lote[6];
                                    $auxLote['dt_atualizacao'] = Helper::dataAbreviadaFromExcel($lote[7]);

                                    $aux[] = $auxLote;
                                }
                            }
                        }

                        //Se possuir valores válidos deleta os antigos valores e insere os novos.
                        if (count($aux) > 0) {

                            //Busca os registros de precos dos lotes referentes a esta tabela para excluir
                            $this->tb_tabela_preco_lotes->delete('id_tabela_preco = ' . $_GET['id']);

                            foreach ($aux as $lote) {

                                $tb_tb_preco_lotes = $this->tb_tabela_preco_lotes->createRow();
                                $tb_tb_preco_lotes->id_tabela_preco = $_GET['id'];
                                $tb_tb_preco_lotes->id_lote = $lote['id'];
                                $tb_tb_preco_lotes->vl_total = $this->_helper->filters($lote['vl_total'], 'moneyExcel');
                                $tb_tb_preco_lotes->vl_sinal = $this->_helper->filters($lote['vl_sinal'], 'moneyExcel');
                                $tb_tb_preco_lotes->vl_parcela = $this->_helper->filters($lote['vl_parcela'], 'moneyExcel');
                                $tb_tb_preco_lotes->vl_intercalada = $this->_helper->filters($lote['vl_intercalada'], 'moneyExcel');
                                $tb_tb_preco_lotes->qt_intercalada = (int)$lote['qt_intercalada'];

                                if ($lote['dt_atualizacao'] == '') {
                                    $tb_tb_preco_lotes->dt_atualizacao = date('Y-m-d');
                                } else {
                                    $tb_tb_preco_lotes->dt_atualizacao = $lote['dt_atualizacao'];
                                }
                                $tb_tb_preco_lotes->save();
                            }
                        } else {
                            $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "editar"), array("msgFail" => 'Arquivo não possui nenhum registro válido.', 'id' => $_POST['id_tabela_preco']));
                        }

                    }

                    $transacaoTabelaPrecoCriar->commit();
                    $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "listar"), array("msgSuccess" => 'Tabela de preços ' . $_POST['nm_tabela'] . ' editada com sucesso.'));

                }
            } catch (Exception $e) {

                $transacaoTabelaPrecoCriar->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "editar"), array("msgFail" => $e->getMessage(), 'id' => $_POST['id_tabela_preco']));

            }

        } else {
            $tabelaPreco = TabelaPreco::getTabelaById($_GET['id']);
            $empreendimento = Empreendimento::getNomeById($tabelaPreco['id_empreendimento']);
            $tabelaPreco['nm_empreendimento'] = $empreendimento;
            $tb_tb_preco_lote = $this->tb_tabela_preco->getDefaultAdapter()->select()
                ->from(array('ttp' => TB_TABELA_PRECO), array('id_tabela_preco'))
                ->joinLeft(array('ttpl' => TB_TABELA_PRECO_LOTES),'ttpl.id_tabela_preco = ttp.id_tabela_preco', array('*'))
                ->where('ttp.id_tabela_preco = ' . $_GET['id'])->query()->fetchAll();

            $this->set("listaQuadras", $this->empreendimentos->listaQuadraEmpreendimento($tabelaPreco['id_empreendimento']));
            $this->set("tabelaPreco", $tabelaPreco);
            $this->set("tb_tb_preco_lote", $tb_tb_preco_lote);
        }
        $this->display("editar");
    }

    /*
     * Exibe tabela de preços para o modal de visualizar
     */
    public function visualizarAction()
    {
        //Buscando a lista de tabelas de preco
        $listaLotes = $this->tb_tabela_preco->getAdapter()->select()
            ->from(array('ttp' => TB_TABELA_PRECO), array('id_tabela_preco', 'nm_tabela', 'ds_tabela'))
            ->joinLeft(array('ttpl' => TB_TABELA_PRECO_LOTES),'ttpl.id_tabela_preco = ttp.id_tabela_preco', array('*'))
            ->joinLeft(array('l' => TB_LOTES), 'ttpl.id_lote = l.id', array('lote', 'quadra'))
            ->joinLeft(array('e' => TB_EMPREENDIMENTO), 'l.id_empreendimento = e.id', array('nm_empreendimento', 'idEmpreendimento' => 'id'))
            ->where('ttp.id_tabela_preco = ' . $_POST['id'])
            ->order(array("quadra", "lote DESC"))->query()->fetchAll();

        $listaQuadras = array();
        foreach ($listaLotes as $lote) {
            array_push($listaQuadras, $lote['quadra']);
        }
        $listaQuadras = array_unique($listaQuadras);

        $this->set('listaQuadras', $listaQuadras);
        $this->set("listaLotes", $listaLotes);
        $this->set("idTabela", $_POST['id']);
        $this->set("empreendimento", $listaLotes[0]['nm_empreendimento']);
        $this->set("idEmpreendimento", $listaLotes[0]['idEmpreendimento']);
        $this->set("nomeTabela", $listaLotes[0]['nm_tabela']);
        $this->set("descricaoTabela", $listaLotes[0]['ds_tabela']);

        $this->display("visualizar");
    }

    /**
     * Retorna o número de registros válidos contidos na tabela selecionada.
     */
    public function contagemRegistrosAction()
    {
        //Realiza a análise do arquivo para importar para a datatable
        $file = $_FILES[0];

        try {
            $data = PHPExcel_IOFactory::load($file['tmp_name']);

            $sheets = $data->getWorksheetIterator();
            foreach ($sheets as $sheet) {
                $array[] = $sheet->toArray();
            }
        } catch (Exception $e) {
            echo json_encode(0);
            exit;
        }

        $aux = 0;

        foreach ($array as $folha) {
            foreach ($folha as $k => $lote) {
                try {
                    $auxLote = Lote::getLotesEmpreendimentoQuadraNumero($_GET['idEmp'], $lote[0], $lote[1]);
                } catch (Exception $e) {
                }

                if (isset($auxLote) && $auxLote) {
                    if ((float)$lote[2] > 0 && (float)$lote[4] > 0) {
                        $aux++;
                    }
                }
            }
        }

        echo json_encode($aux);
    }

    /**
     * Remove os registros selecionados
     */
    public function deletarAction()
    {
        $registros = $_POST['linhas'];

        try {
            foreach ($registros as $registro) {
                //Apaga todos os registros na tabela de preços de lotes
                $this->tb_tabela_preco_lotes->delete('id_tabela_preco = ' . $registro);
                //Apaga a tabela de preços
                $this->tb_tabela_preco->delete('id_tabela_preco = ' . $registro);
            }
        } catch (Exception $e) {
            $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "editar"), array("msgFail" => $e->getMessage()));
        }
        $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "listar"), array("msgSuccess" => 'Registros removidos com sucesso!'));

    }

    public function buscaLotesByQuadraAction()
    {

        $listaLotes = Lote::getLotesByEmpreendimentoQuadraTabelaPreco($_POST['empreendimento'], $_POST['quadra'], $_POST['tabela']);

        if (count($listaLotes) > 0) {
            foreach ($listaLotes as $lote) {

                //Label de status para mudar a classe e o texto
                if ($lote['fl_aprovar_contrato'] == '1') {
                    $labelLote = 'label-danger';
                    $lote['status'] = 'Vendido';
                } elseif ($lote['fl_aprovar_contrato'] == '0') {
                    $labelLote = 'label-primary';
                    $lote['status'] = 'Em Negociaçao';
                } elseif ($lote['reservado'] == 1 || $lote['idReserva']) {
                    $labelLote = 'label-warning';
                    $lote['status'] = 'Reservado';
                } else {
                    $labelLote = 'label-success';
                    $lote['status'] = 'Disponível';
                }

                $listaAuxLotes[] = array(
                    'quadra' => $lote['quadra'],
                    'lote' => $lote['lote'],
                    'vlTotal' => '<div align="right">' . Helper::getMoney($lote['vl_total']) . '</div>',
                    'vlSinal' => '<div align="right">' . Helper::getMoney($lote['vl_sinal']) . '</div>',
                    'vlParcela' => '<div align="right">' . Helper::getMoney($lote['vl_parcela']) . '</div>',
                    'vlIntercalada' => '<div align="right">' . Helper::getMoney($lote['vl_intercalada']) . '</div>',
                    'qtIntercalada' => '<div align="center">' . $lote['qt_intercalada'] . '</div>',
                    'status' => '<div align="center"><span class="label ' . $labelLote . ' ">' . $lote['status'] . '</span></div>',
                    'dtAtualizacao' => '<div align="center">' . Helper::getDate($lote['dt_atualizacao']) . '</div>',
                    'acoes' => '<div align="center"><a href="?m=cadastro&c=tabelaPreco&a=deletarLoteTabela&id=' . $lote['id_tabela_preco_lotes'] . '" data-toggle="tooltip" title="DELETAR" class="btn btn-default btn-xs" style="margin-right:5px"><i class="fa fa-trash-o"></i> </a></div>',
                );
            }
            echo json_encode(array('draw' => 1, 'recordsTotal' => count($listaAuxLotes), 'recordsFiltered' => count($listaAuxLotes), 'data' => $listaAuxLotes));
        } else {
            echo json_encode(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array()));
        }
    }

    public function findLoteByQuadraAction() {

        $lotes = $this->lotes->getLotesEmpreendimento($_POST['empreendimento'],$_POST['quadra']);

        $jsonLoteQuadra[] = array('id' => '', 'text' => '');

        if ($lotes) {
            foreach ($lotes as $lote) {

                $idLote = $lote['idLote'];

                if (isset($lote['id_tabela_preco_lotes']))
                    $idLote = $lote['id_tabela_preco_lotes'];

                $jsonLoteQuadra[] = array('id' => $idLote, 'text' => 'Lote ' . $lote['lote']);
            }
        }
        echo json_encode(array('lotes' => $jsonLoteQuadra));
    }

    public function findLoteByIdAction() {

        $infoLote = $this->tb_tabela_preco_lotes->fetchRow('id_tabela_preco_lotes = '.$_POST['idLote']);

        $dadosLotes = false;

        if ($infoLote) {
            $dadosLotes = $infoLote;

        }

        echo json_encode(array('dadosLotes' => $dadosLotes, 'vl_total' => Helper::getMoney($dadosLotes['vl_total']), 'vl_sinal' => Helper::getMoney($dadosLotes['vl_sinal']), 'vl_parcela' => Helper::getMoney($dadosLotes['vl_parcela']), 'vl_intercalada' => Helper::getMoney($dadosLotes['vl_intercalada']), 'qt_intercalada' => $dadosLotes['qt_intercalada'], 'dt_atualizacao' => Helper::getDate($dadosLotes['dt_atualizacao'])));
    }

    /**
     * Remove os registros selecionados
     */
    public function deletarLoteTabelaAction()
    {
        $id = $_GET['id'];

        try {
            if ($id) {
                //Apaga todos os registros na tabela de preços de lotes
                $this->tb_tabela_preco_lotes->delete('id_tabela_preco_lotes = ' . $id);
            }
        } catch (Exception $e) {
            $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "editar"), array("msgFail" => $e->getMessage()));
        }
        $this->redir(array('modulo' => 'cadastro', 'controller' => 'tabelaPreco', "action" => "listar"), array("msgSuccess" => 'Registros removidos com sucesso!'));


    }
}
