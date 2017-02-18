<?php

class HistoricoCrmController extends Controller
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

        $this->redir(array("modulo" => "cadastro",
            "controller" => "historicoCrm",
            'action' => 'listar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function listarAction()
    {
        if (Helper::verificaAjax()) {
            $listaIndicacao = $this->indicacaoContato->getIndicacoesHistorico();

            if (count($listaIndicacao) > 0) {
                foreach ($listaIndicacao as $indicacaoContato) {
                    $acoes = '<div align="center">';
                    $acoes .= '<span data-toggle="tooltip" title="VISUALIZAR" ><a value="' . $indicacaoContato['id_indicacao'] . '" data-toggle="modal" data-target="#modalVisualizar" title="VISUALIZAR" class="btn btn-default btn-xs btn-visualizar" style="margin-right:5px"><i class="fa fa-eye"></i> </a></span>';
                    $acoes .= '<span data-toggle="tooltip" title="ESTORNAR" > <a onclick="return confirm_click();" href="?m=cadastro&c=historicoCrm&a=estornar&id=' . $indicacaoContato['id_indicacao'] . '" class="btn btn-default btn-xs" style="margin-right:5px"> <i class="fa fa-undo"></i></a> </span>';
                    $acoes .= '</div>';

                    $divObsInicio = '';
                    $divObsFim = '';
                    if (!isset($indicacaoContato['ds_contato']) || $indicacaoContato['ds_contato'] == '') {
                        $divObsInicio = '<div style="color: red">';
                        $divObsFim = '</div>';
                    }

                    $dtContato = $indicacaoContato['dt_contato'];
                    $divDtContato = '<div hidden>' . $dtContato . '</div>' . Helper::getDate($dtContato);

                    $listaIndicacoes[] = array('idIndicacaoContrato' => $divObsInicio . $indicacaoContato['id_indicacao_contato'] . $divObsFim,
                        'dataContato' => $divObsInicio . $divDtContato . $divObsFim,
                        'horaContato' => $divObsInicio . $indicacaoContato['hr_contato'] . $divObsFim,
                        'indicado' => $divObsInicio . $indicacaoContato['nm_indicado'] . $divObsFim,
                        'cliente' => $divObsInicio . $indicacaoContato['nmPessoaCliente'] . $divObsFim,
                        'responsavel' => $divObsInicio . $indicacaoContato['login'] . $divObsFim,
                        'tipoContato' => $divObsInicio . $indicacaoContato['dsTipoContato'] . $divObsFim,
                        'acoes' => $acoes);
                }

                echo json_encode(array('draw' => 1,
                    'recordsTotal' => count($listaIndicacoes),
                    'recordsFiltered' => count($listaIndicacoes),
                    'data' => $listaIndicacoes));
            } else {
                echo json_encode(array('draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => array()));
            }
        } else {
            $this->display('listar');
        }
    }

    /*
     * Exibe tabela de preços para o modal de visualizar
     */
    public function visualizarAction()
    {
        $this->set('listaContatosIndicacao', $this->indicacaoContato->getIndicacoesContatosHistorico($_POST['id']));

        $this->set('usuario', $this->pessoa->find(Login::getUsuario()->getId())->current());

        $this->display("visualizar");
    }

    /*
     * Estorna o fechamento da indicação setando o flag para ativo
     */
    public function estornarAction()
    {
        try {
            $indicacao = $this->indicacao->fetchRow('id_indicacao = ' . $_GET['id']);
            $indicacao->fl_status = 1;

            $indicacao->save();

            $this->redir(array ('modulo' => 'cadastro',
                'controller' => 'historicoCrm',
                "action" => "listar"), array ("msgSuccess" => 'Estorno realizado com sucesso.'));

        } catch (Exception $e) {
            $this->redir(array ('modulo' => 'cadastro',
                'controller' => 'historicoCrm',
                "action" => "listar"), array ("msgFail" => $e->getMessage()));
        }
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

            $dados[] = array('text' => $pessoa['nm_pessoa'] . ' (' . $nrCpfCnpj . ')',
                'value' => $pessoa['idPessoa'],
                'info' => $pessoa);
        }

        echo json_encode($dados);
    }

}
