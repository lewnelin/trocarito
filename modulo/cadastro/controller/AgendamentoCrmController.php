<?php

class AgendamentoCrmController extends Controller
{

    //Funcao publica do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->indicacao = new Indicacao();
        $this->indicacaoContato = new IndicacaoContato();
        $this->empreendimento = new Empreendimento();
        $this->pessoa = new Pessoa();
        $this->tabelaAgrupada = new TabelaAgrupada();
        $this->usuario = new Usuario();

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {

        $this->redir(array ("modulo" => "cadastro",
            "controller" => "agendamentoCrm",
            'action' => 'listar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function listarAction()
    {
        if (Helper::verificaAjax()) {
            $listaIndicacao = $this->indicacaoContato->getIndicacoesAgrupadasContatos();

            if (count($listaIndicacao) > 0) {
                foreach ($listaIndicacao as $indicacaoContato) {
                    $acoes = '<div align="center">';
                    $acoes .= '<span data-toggle="tooltip" title="VISUALIZAR" ><a value="' . $indicacaoContato['id_indicacao'] . '" data-toggle="modal" data-target="#modalVisualizar" title="VISUALIZAR" class="btn btn-default btn-xs btn-visualizar" style="margin-right:5px"><i class="fa fa-eye"></i> </a></span>';
                    $acoes .= '<a target="_blank" href="?m=cadastro&c=agendamentoCrm&a=contato&id=' . $indicacaoContato['id_indicacao'] . '" data-toggle="tooltip" title="ADICIONAR CONTATO" class="btn btn-default btn-xs" style="margin-right:5px"><i class="icon-user-add"></i> </a>';
                    $acoes .= '</div>';

                    $divObsInicio = '';
                    $divObsFim = '';
                    if (!isset($indicacaoContato['ds_contato']) || $indicacaoContato['ds_contato'] == '') {
                        $divObsInicio = '<div style="color: red">';
                        $divObsFim = '</div>';
                    }

                    $dtContato = $indicacaoContato['dt_contato'];
                    $divDtContato = '<div hidden>' . $dtContato . '</div>' . Helper::getDate($dtContato);

                    $listaIndicacoes[] = array ('idIndicacaoContrato' => $divObsInicio . $indicacaoContato['id_indicacao_contato'] . $divObsFim,
                        'dataContato' => $divObsInicio . $divDtContato . $divObsFim,
                        'horaContato' => $divObsInicio . $indicacaoContato['hr_contato'] . $divObsFim,
                        'indicado' => $divObsInicio . $indicacaoContato['nm_indicado'] . $divObsFim,
                        'cliente' => $divObsInicio . $indicacaoContato['nmPessoaCliente'] . $divObsFim,
                        'responsavel' => $divObsInicio . $indicacaoContato['login'] . $divObsFim,
                        'tipoContato' => $divObsInicio . $indicacaoContato['dsTipoContato'] . $divObsFim,
                        'acoes' => $acoes);
                }

                echo json_encode(array ('draw' => 1,
                    'recordsTotal' => count($listaIndicacoes),
                    'recordsFiltered' => count($listaIndicacoes),
                    'data' => $listaIndicacoes));
            } else {
                echo json_encode(array ('draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => array ()));
            }
        } else {
            $this->display('listar');
        }
    }

    /**
     * Função responsável por realizar a inserção da indicação
     */
    public function adicionarAction()
    {

        if ($this->_isPost) {
            try {
                $transacaoIndicacao = $this->indicacao->getAdapter();
                $transacaoIndicacao->beginTransaction();

                $indicacao = $this->indicacao->createRow();
                $indicacao->id_empreendimento = $this->_helper->filters($_POST['idEmpreendimento']);
                $indicacao->id_cliente = $this->_helper->filters($_POST['idCliente']);
                $indicacao->id_usuario = Login::getUsuario()->getId();
                $indicacao->nm_indicado = $this->_helper->filters($_POST['nmIndicado']);
                $indicacao->nr_telefone = $this->_helper->filters($_POST['nrTelefone']);
                $indicacao->ds_email = $this->_helper->filters($_POST['dsEmail']);
                $indicacao->dt_fechamento = null;
                $indicacao->fl_status = 1;
                $indicacao->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');
                $idIndicacao = $indicacao->save();

                $indicacaoContato = $this->indicacaoContato->createRow();
                $indicacaoContato->id_indicacao = $idIndicacao;
                $indicacaoContato->id_usuario = Login::getUsuario()->getId();
                $indicacaoContato->id_tipo_contato = $this->_helper->filters($_POST['idTipoContato']);
                $indicacaoContato->dt_contato = date('Y-m-d');
                $indicacaoContato->hr_contato = date('H:i:s');
                $indicacaoContato->ds_contato = $this->_helper->filters($_POST['dsContato']);
                $indicacaoContato->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s') . ' - ' . 'I';
                $idIndicacaoContato = $indicacaoContato->save();

                $transacaoIndicacao->commit();

                $this->redir(array ('modulo' => 'cadastro',
                    'controller' => 'agendamentoCrm',
                    "action" => "listar"), array ("msgSuccess" => 'Agendamento CRM <b>' . $idIndicacaoContato . '</b> cadastrado com sucesso.'));
            } catch (Exception $e) {
                $transacaoIndicacao->rollBack();
                $this->redir(array ('modulo' => 'cadastro',
                    'controller' => 'agendamentoCrm',
                    "action" => "adicionar"), array ("msgFail" => $e->getMessage()));
            }
        } else {
            //Buscando a lista de empreendimento para o select
            $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario(false));

            $this->set('listaTipoContato', $this->tabelaAgrupada->getTabelaByCodigo(13));

            $this->set('listaPessoas', $this->pessoa->fetchAll());

            $this->set('usuario', $this->pessoa->find(Login::getUsuario()->getId())->current());

            $this->display("adicionar");
        }

    }

    /**
     * Função responsável por cadastrar/alterar os contatos da indicação, usada na função ajax/salvar
     */
    public function contatoAction()
    {
        if ($this->_isPost) {
            try {
                //buscar tabela preço
                $transacaoContIndicacao = $this->indicacaoContato->getDefaultAdapter();
                $transacaoContIndicacao->beginTransaction();

                //Entra no if quando é adicionado um novo contato através do contato
                if ($_POST['novoContato']) {
                    $indicacaoContato = $this->indicacaoContato->createRow();
                } else {
                    $indicacaoContato = $this->indicacaoContato->fetchRow('id_indicacao_contato = ' . $_POST['idUltimaIndicacaoContato']);
                }

                $indicacaoContato->id_indicacao = $_GET['id'];
                $indicacaoContato->id_usuario = Login::getUsuario()->getId();
                $indicacaoContato->id_tipo_contato = $this->_helper->filters($_POST['idTipoContato']);
                $indicacaoContato->dt_contato = $this->_helper->filters($_POST['dtContato'], 'date');
                $indicacaoContato->hr_contato = $this->_helper->filters($_POST['hrContato']);
                $indicacaoContato->ds_contato = $this->_helper->filters($_POST['dsContato']);
                $indicacaoContato->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s') . ' - ' . 'A';
                $indicacaoContato->save();

                if (isset($_POST['fl_status'])) {
                    $indicacao = $this->indicacao->fetchRow('id_indicacao = ' . $_GET['id']);
                    $indicacao->dt_fechamento = $this->_helper->filters($_POST['dtContato'], 'date');
                    $indicacao->fl_status = 0;
                    $indicacao->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');
                    $indicacao->save();
                }

                $transacaoContIndicacao->commit();

                if ($_POST['novoContato']) {
                    $this->redir(array ('modulo' => 'cadastro',
                        'controller' => 'agendamentoCrm',
                        "action" => "listar"), array ("msgSuccess" => 'Contato cadastrado com sucesso.'));
                } else {
                    $this->redir(array ('modulo' => 'cadastro',
                        'controller' => 'agendamentoCrm',
                        "action" => "listar"), array ("msgSuccess" => 'Contato editado com sucesso.'));
                }

            } catch (Exception $e) {
                $transacaoContIndicacao->rollBack();
                $this->redir(array ('modulo' => 'cadastro',
                    'controller' => 'agendamentoCrm',
                    "action" => "adicionar"), array ("msgFail" => $e->getMessage()));
            }
        } else {
            $listaIndicacaoContato = $this->indicacaoContato->getIndicacoesContatos($_GET['id']);

            if (!$listaIndicacaoContato) $this->redir(array ('modulo' => 'cadastro',
                'controller' => 'agendamentoCrm',
                "action" => "listar"), array ("msgWarning" => 'Essa indicação não existe.'));

            $this->set('listaContatosIndicacao', $listaIndicacaoContato);

            $this->set('listaTipoContato', $this->tabelaAgrupada->getTabelaByCodigo(13));

            $this->set('usuario', $this->pessoa->find(Login::getUsuario()->getId())->current());

            $this->display("contato");
        }
    }

    /*
     * Exibe tabela de preços para o modal de visualizar
     */
    public function visualizarAction()
    {
        $this->set('listaContatosIndicacao', $this->indicacaoContato->getIndicacoesContatos($_POST['id']));

        $this->set('usuario', $this->pessoa->find(Login::getUsuario()->getId())->current());

        $this->display("visualizar");
    }

    /*
     * Busca e exibe em formato json os dados necessarios para o modal de informacoes de lotes
     */
    public function findPessoasByNomeAction()
    {
        $dados = array ();

        //Buscando a lista de lotes para exibir na tela
        $lista = $this->pessoa->findPessoasByNmpessoa($_GET['term']);

        if ($lista) foreach ($lista as $pessoa) {

            $nrCpfCnpj = ($pessoa['nr_cnpj']) ? $pessoa['nr_cnpj'] : $pessoa['nr_cpf'];

            $dados[] = array ('text' => $pessoa['nm_pessoa'] . ' (' . $nrCpfCnpj . ')',
                'value' => $pessoa['idPessoa'],
                'info' => $pessoa);
        }

        echo json_encode($dados);
    }

}
