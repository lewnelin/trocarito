<?php

class ParametroComissaoController extends Controller
{

    //Funcao publica do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->empreendimentos = new Empreendimento();
        $this->parametroComissao = new ParametroComissao();

    }

    /**
     * Redireciona para a ação de listar
     */
    public function indexAction()
    {

        $this->redir(array("modulo" => "cadastro", "controller" => "parametroComissao", 'action' => 'listar'));

    }

    /**
     * Exibe tela de listar
     */
    public function listarAction()
    {

        if (Helper::verificaAjax()) {

            $listaTabela = ParametroComissao::getParametrosUsuario();

            if (count($listaTabela) > 0) {
                foreach ($listaTabela as $parametro) {

                    $acoes = '<div align="center">';
                    $acoes .= '<a value="' . $parametro['id_parametro_comissao'] . '" data-toggle="modal" data-target="#modalVisualizar" title="VISUALIZAR" class="btn btn-default btn-xs btn-visualizar" style="margin-right:5px"><i class="fa fa-eye"></i> </a>';
                    $acoes .= '<a href="?m=cadastro&c=parametroComissao&a=editar&id=' . $parametro['id_parametro_comissao'] . '" data-toggle="tooltip" title="EDITAR" class="btn btn-default btn-xs" style="margin-right:5px"><i class="fa fa-edit"></i> </a>';
                    $acoes .= '</div>';

                    //Monta o checkbox para seleção
                    $checkbox = '<div align="center" >';
                    $checkbox .= '<input type="checkbox" class="rows-check" value="' . $parametro['id_parametro_comissao'] . '" id="' . $parametro['id_parametro_comissao'] . '" nomeExibidoNoDeletar="' . $parametro['nm_empreendimento'] . '" name="linhas[]">';
                    $checkbox .= '</div>';

                    switch ($parametro['tp_local_insidencia']) {
                        case 'S':
                            $insidencia = 'Sinal';
                            break;
                        case 'P':
                            $insidencia = 'Parcela';
                            break;
                        case 'TI':
                            $insidencia = 'Total do Imóvel';
                            break;
                        default:
                            $insidencia = '';
                            break;
                    }

                    $listaTabelas[] = array(
                        'checkbox' => $checkbox,
                        'parametro' => $parametro['id_parametro_comissao'],
                        'empreendimento' => $parametro['nm_empreendimento'],
                        'nome' => $parametro['nm_parametro_comissao'],
                        'comissao' => ($parametro['tp_comissao'] == 'F') ? 'Fixo' : 'Percentual',
                        'insidencia' => $insidencia,
                        'acoes' => $acoes
                    );
                }

                echo json_encode(array(
                    'draw' => 1,
                    'recordsTotal' => count($listaTabelas),
                    'recordsFiltered' => count($listaTabelas),
                    'data' => $listaTabelas
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
     * Exibe tela de adicionar
     */
    public function adicionarAction()
    {
        //Buscando a lista de empreendimentos para o select, apenas os empreendimentos do usuário.
        $listaEmpreendimentos = $this->empreendimentos->getEmpreendimentosUsuario();

        $this->set('listaEmpreendimentos', $listaEmpreendimentos);

        if ($this->_isPost) {

            try {
                //criar registro na tabela de logo de empreendimento
                $transacao = $this->parametroComissao->getAdapter();

                $transacao->beginTransaction();

                $parametroComissao = $this->parametroComissao->createRow();
                $parametroComissao->id_empreendimento = $_POST['Empreendimento'];
                $parametroComissao->nm_parametro_comissao = $_POST['nmParametro'];
                $parametroComissao->tp_comissao = $_POST['tpComissao'];
                $parametroComissao->tp_local_insidencia = $_POST['tpInsidencia'];
                $parametroComissao->vl_corretor = $_POST['vlCorretor'];
                $parametroComissao->vl_coordenador = $_POST['vlCoordenador'];
                $parametroComissao->vl_indicador = $_POST['vlIndicador'];
                $parametroComissao->vl_imobiliaria = $_POST['vlImobiliaria'];
                $parametroComissao->vl_outros = $_POST['vlOutros'];
                $parametroComissao->qt_parcela_comissao = $_POST['qtParcelas'];
                $parametroComissao->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');

                //Salva o logo na tabela
                $parametroComissao->save();

            } catch (Exception $e) {
                $transacao->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'parametroComissao', "action" => "adicionar"), array("msgFail" => 'Não foi possível parâmetro de comissão.'));
                exit;
            }

            $transacao->commit();

            $this->redir(array('modulo' => 'cadastro', 'controller' => 'parametroComissao', "action" => "listar"), array("msgSuccess" => 'Parâmetro de comissão cadastrado com sucesso.'));
        }

        $this->display("adicionar");
    }

    /**
     * Exibe tela de editar
     */
    public function editarAction()
    {

        if ($this->_isPost) {

            try {
                //busca registro na tabela de parametros de comissao
                $transacao = $this->parametroComissao->getAdapter();

                $transacao->beginTransaction();

                $parametroComissao = $this->parametroComissao->fetchRow('id_parametro_comissao = ' . $_POST['idParametro']);

                $parametroComissao->nm_parametro_comissao = $_POST['nmParametro'];
                $parametroComissao->tp_comissao = $_POST['tpComissao'];
                $parametroComissao->tp_local_insidencia = $_POST['tpInsidencia'];
                $parametroComissao->vl_corretor = $_POST['vlCorretor'];
                $parametroComissao->vl_coordenador = $_POST['vlCoordenador'];
                $parametroComissao->vl_indicador = $_POST['vlIndicador'];
                $parametroComissao->vl_imobiliaria = $_POST['vlImobiliaria'];
                $parametroComissao->vl_outros = $_POST['vlOutros'];
                $parametroComissao->qt_parcela_comissao = $_POST['qtParcelas'];
                $parametroComissao->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');

                //Salva no banco com as alterações
                $parametroComissao->save();

            } catch (Exception $e) {
                $transacao->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'parametroComissao', "action" => "editar"), array("msgFail" => 'Não foi possível editar o parâmetro de comissão.'));
                exit;
            }

            $transacao->commit();

            $this->redir(array('modulo' => 'cadastro', 'controller' => 'parametroComissao', "action" => "listar"), array("msgSuccess" => 'Parâmetro de comissão alterado com sucesso.'));

        } else {

            $parametro = $this->parametroComissao->fetchRow('id_parametro_comissao = ' . $_GET['id'])->toArray();
            $empreendimento = $this->empreendimentos->fetchRow('id = ' . $parametro['id_empreendimento']);

            switch ($parametro['tp_local_insidencia']) {
                case 'S':
                    $insidencia = 'Sinal';
                    break;
                case 'P':
                    $insidencia = 'Parcela';
                    break;
                case 'TI':
                    $insidencia = 'Total do Imóvel';
                    break;
                default:
                    $insidencia = '';
                    break;
            }
            $parametro['insidencia'] = $insidencia;

            $parametro['comissao'] = ($parametro['tp_comissao'] == 'F') ? 'Fixo' : 'Percentual';

            $this->set("empreendimento", $empreendimento);
            $this->set("parametro", $parametro);

        }

        $this->display("editar");

    }

    /*
     * Exibe o modal de visualizar
     */
    public function visualizarAction()
    {
        $parametro = $this->parametroComissao->fetchRow('id_parametro_comissao = ' . $_POST['id'])->toArray();
        $empreendimento = $this->empreendimentos->fetchRow('id = ' . $parametro['id_empreendimento']);

        switch ($parametro['tp_local_insidencia']) {
            case 'S':
                $insidencia = 'Sinal';
                break;
            case 'P':
                $insidencia = 'Parcela';
                break;
            case 'TI':
                $insidencia = 'Total do Imóvel';
                break;
            default:
                $insidencia = '';
                break;
        }
        $parametro['insidencia'] = $insidencia;

        $parametro['comissao'] = ($parametro['tp_comissao'] == 'F') ? 'Fixo' : 'Percentual';

        $this->set("empreendimento", $empreendimento);
        $this->set("parametro", $parametro);

        $this->display("visualizar");
    }

    /**
     * Remove os registros selecionados
     */
    public function deletarAction()
    {
        $registros = $_POST['linhas'];

        try {
            //Apaga todos os registros na tabela de parametros
            foreach ($registros as $registro) {
                $this->parametroComissao->delete('id_parametro_comissao = ' . $registro);
            }
        } catch (Exception $e) {
            $this->redir(array('modulo' => 'cadastro', 'controller' => 'parametroComissao', "action" => "listar"), array("msgFail" => $e->getMessage()));
        }

        $this->redir(array('modulo' => 'cadastro', 'controller' => 'parametroComissao', "action" => "listar"), array("msgSuccess" => 'Registros removidos com sucesso!'));

    }

    /**
     * Verifica se já existe um parâmetro de comissão do empreendimento e qual seu tipo
     */
    public function verificaParametroAction(){
        $parametroComissao = null;

        try{
            $parametroComissao = $this->parametroComissao->fetchRow('id_empreendimento = ' . $_GET['id']);
        } catch (Exception $e){}

        echo json_encode($parametroComissao['tp_parametro_comissao']);
    }
}
