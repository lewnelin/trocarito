<?php

class IndicacaoController extends Controller
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

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {

        $this->redir(array("modulo" => "cadastro", "controller" => "indicacao", 'action' => 'listar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function listarAction()
    {

        if (Helper::verificaAjax()) {

            $listaIndicacao = $this->indicacao->getIndicacoes();

            if (count($listaIndicacao) > 0) {
                foreach ($listaIndicacao as $indicacao) {

                    $acoes = '<div align="center">';
                    $acoes .= '<a value="' . $indicacao['id_indicacao'] . '" data-toggle="modal" data-target="#modalVisualizar" title="VISUALIZAR" class="btn btn-default btn-xs btn-visualizar" style="margin-right:5px"><i class="fa fa-eye"></i> </a>';
                    $acoes .= '<a href="?m=cadastro&c=indicacao&a=editar&id=' . $indicacao['id_indicacao'] . '" data-toggle="tooltip" title="EDITAR" class="btn btn-default btn-xs" style="margin-right:5px"><i class="fa fa-edit"></i> </a>';
                    $acoes .= '</div>';

                    $dtContato = $indicacao['dt_contato'];
                    $divDtContato = '<div hidden>' . $dtContato . '</div>' . Helper::getDate($dtContato);

                    $listaIndicacoes[] = array(
                        'idIndicacaoContrato' => '<div align="center" >' . $indicacao['id_indicacao'] . '</div>',
                        'dataIndicacao' => $divDtContato,
                        'indicado' => $indicacao['nm_indicado'],
                        'cliente' => ($indicacao['nmPessoaCliente']) ? $indicacao['nmPessoaCliente'] : '-----',
                        'responsavel' => $indicacao['login'],
                        'ultimoTipoContato' => $indicacao['dsTipoContato'],
                        'acoes' => $acoes
                    );
                }

                echo json_encode(array(
                    'draw' => 1,
                    'recordsTotal' => count($listaIndicacoes),
                    'recordsFiltered' => count($listaIndicacoes),
                    'data' => $listaIndicacoes
                ));
            } else {
                echo json_encode(array(
                    'draw' => 0,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => array()
                ));
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
                //criar Indicação
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
                $indicacaoContato->id_tipo_contato = 131;
                $indicacaoContato->dt_contato = $this->_helper->filters($_POST['dtContato'],'date');
                $indicacaoContato->hr_contato = $this->_helper->filters($_POST['hrContato']);
                $indicacaoContato->ds_contato = $this->_helper->filters($_POST['dsContato']);
                $indicacaoContato->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s') . ' - ' . 'I';
                $idIndicacaoContato = $indicacaoContato->save();

                $transacaoIndicacao->commit();

                $this->redir(array('modulo' => 'cadastro', 'controller' => 'indicacao', "action" => "listar"), array("msgSuccess" => 'Indicação ' . $idIndicacaoContato . ' cadastrada com sucesso.'));

            } catch (Exception $e) {

                $transacaoIndicacao->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'indicacao', "action" => "adicionar"), array("msgFail" => $e->getMessage()));

            }
        } else {

            //Buscando a lista de empreendimento para o select
            $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario(false));

            $this->set('listaPessoas', $this->pessoa->fetchAll());

            $this->set('usuario', $this->pessoa->find(Login::getUsuario()->getId())->current());

            $this->display("adicionar");

        }

    }


    /**
     * Função responsável por realizar a inserção da indicação
     */
    public function editarAction()
    {

        if ($this->_isPost) {
            try {
                //editar Indicação
                $transacaoIndicacao = $this->indicacao->getAdapter();
                $transacaoIndicacao->beginTransaction();

                $indicacao = $this->indicacao->fetchRow('id_indicacao = ' . $_POST['idIndicacao']);

                $indicacao->id_cliente = $this->_helper->filters($_POST['idCliente']);
                $indicacao->nm_indicado = $this->_helper->filters($_POST['nmIndicado']);
                $indicacao->nr_telefone = $this->_helper->filters($_POST['nrTelefone']);
                $indicacao->ds_email = $this->_helper->filters($_POST['dsEmail']);
                $indicacao->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');
                $indicacao->save();

                $indicacaoContato = $this->indicacaoContato->fetchRow('id_indicacao_contato = ' . $_POST['idContatoIndicacao']);
                $indicacaoContato->dt_contato = $this->_helper->filters($_POST['dtContato'],'date');
                $indicacaoContato->hr_contato = $this->_helper->filters($_POST['hrContato']);
                $indicacaoContato->ds_contato = $this->_helper->filters($_POST['dsContato']);
                $indicacaoContato->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s') . ' - ' . 'I';
                $indicacaoContato->save();

                $transacaoIndicacao->commit();

                $this->redir(array('modulo' => 'cadastro', 'controller' => 'indicacao', "action" => "listar"), array("msgSuccess" => 'Indicação modificada com sucesso.'));

            } catch (Exception $e) {

                $transacaoIndicacao->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'indicacao', "action" => "listar"), array("msgFail" => $e->getMessage()));

            }
        } else {

            $indicacao = $this->indicacao->fetchRow('id_indicacao = ' . $_GET['id'])->toArray();
            $indicacaoContatos = $this->indicacaoContato->fetchAll('id_indicacao = ' . $_GET['id'],'dt_contato ASC');

            $indicacao['dtIndicacao'] = $indicacaoContatos[0]['dt_contato'];
            $indicacao['hrIndicacao'] = $indicacaoContatos[0]['hr_contato'];
            $indicacao['obs'] = $indicacaoContatos[0]['ds_contato'];
            $indicacao['idContato'] = $indicacaoContatos[0]['id_indicacao_contato'];

            $this->set('indicacao', $indicacao);


            //Buscando a lista de empreendimento para o select
            $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario(false));
            $this->set('idEmpreendimento', $indicacao['id_empreendimento']);

            if($indicacao['id_cliente']) {
                $cliente = $this->pessoa->find($indicacao['id_cliente'])->toArray();
            } else {
                $cliente = array(0 => array());
            }
            $this->set('listaPessoas', $cliente);
            $this->set('cliente', $cliente[0]);

            $this->set('usuario', $this->pessoa->find(Login::getUsuario()->getId())->current());

            $this->display("editar");

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
        $dados = array();

        //Buscando a lista de lotes para exibir na tela
        $lista = $this->pessoa->findPessoasByNmpessoa($_GET['term']);

        if ($lista)
            foreach ($lista as $pessoa) {

                $nrCpfCnpj = ($pessoa['nr_cnpj']) ? $pessoa['nr_cnpj'] : $pessoa['nr_cpf'];

                $dados[] = array(
                    'text' => $pessoa['nm_pessoa'] . ' (' . $nrCpfCnpj . ')',
                    'value' => $pessoa['idPessoa'],
                    'info' => $pessoa
                );
            }

        echo json_encode($dados);
    }

}
