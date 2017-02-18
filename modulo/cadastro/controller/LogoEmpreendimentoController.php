<?php

class LogoEmpreendimentoController extends Controller
{

    //Funcao publica do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->logoEmpreendimento = new LogoEmpreendimento();
        $this->lotes = new Lote();
        $this->empreendimentos = new Empreendimento();

    }

    /**
     * Redireciona para a ação de listar
     */
    public function indexAction()
    {

        $this->redir(array("modulo" => "cadastro", "controller" => "logoEmpreendimento", 'action' => 'listar'));

    }

    /**
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
            $listaTabela = $this->logoEmpreendimento->getAdapter()->select()
                ->from(array('le' => TB_LOGO_EMPREENDIMENTO), array(
                        'id_logo_empreendimento',
                        'id_empreendimento',
                        'ds_path_logo',
                        'ds_log'
                    )
                );

            if (Login::getUsuario() && count($idsUsuarioEmpreendimento) > 0) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaTabela = $listaTabela->join(array('e' => TB_EMPREENDIMENTO), "le.id_empreendimento = e.id AND e.id IN({$idsUsuarioEmpreendimento})", array(
                    'id',
                    'nm_empreendimento',
                    'bairro',
                    'cd_cidade'));
            } else {
                $listaTabela = $listaTabela->join(array('e' => TB_EMPREENDIMENTO), 'le.id_empreendimento = e.id', array(
                    'id',
                    'nm_empreendimento',
                    'bairro',
                    'cd_cidade'));
            }

            $listaTabela = $listaTabela->joinLeft(array('cid' => TB_CIDADE), 'e.cd_cidade = cid.id', array('cidade' => 'CONCAT(nome," - ",uf)'))
                ->query()->fetchAll();

            if (count($listaTabela) > 0 && $listaTabela[0]['id']) {
                foreach ($listaTabela as $logo) {

                    $acoes = '<div align="center">';
                    $acoes .= '<a value="' . $logo['id'] . '" data-toggle="modal" data-target="#modalVisualizar" title="VISUALIZAR" class="btn btn-default btn-xs btn-visualizar" style="margin-right:5px"><i class="fa fa-eye"></i> </a>';
                    $acoes .= '<a href="?m=cadastro&c=logoEmpreendimento&a=editar&id=' . $logo['id'] . '" data-toggle="tooltip" title="EDITAR" class="btn btn-default btn-xs" style="margin-right:5px"><i class="fa fa-edit"></i> </a>';
                    $acoes .= '</div>';

                    //Monta o checkbox para seleção
                    $checkbox = '<div align="center" >';
                    $checkbox .= '<input type="checkbox" class="rows-check" value="' . $logo['id'] . '" id="' . $logo['id'] . '" nomeExibidoNoDeletar="' . $logo['nm_empreendimento'] . '" name="linhas[]">';
                    $checkbox .= '</div>';

                    $listaTabelas[] = array(
                        'checkbox' => $checkbox,
                        'codigo' => $logo['id'],
                        'empreendimento' => $logo['nm_empreendimento'],
                        'cidade' => $logo['cidade'],
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
     * Exibe tela de adicionar novo logo para empreendimento
     */
    public function adicionarAction()
    {
        //Buscando a lista de empreendimentos para o select, apenas os empreendimentos que não possuem logo.
        $listaEmpreendimentos = $this->empreendimentos->getEmpreendimentosUsuarioLogo();

        $this->set('listaEmpreendimentos', $listaEmpreendimentos);

        if ($this->_isPost) {

            //Realiza a análise e carrega o arquivo na pasta
            try {

                //criar registro na tabela de logo de empreendimento
                $transacao = $this->logoEmpreendimento->getAdapter();
                $idLogo = $transacao->select()->from(array('le' => TB_LOGO_EMPREENDIMENTO), 'id_logo_empreendimento')
                    ->order('id_logo_empreendimento desc')->query()->fetch();
                $idLogo = (int)$idLogo['id_logo_empreendimento'];

                $transacao->beginTransaction();

                $file = $_FILES['arquivoCadastro'];
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filepath = 'upload/cadastro/logoEmpreendimento/' . 'IMG_' . $idLogo . '_' . date('dmYHis') . '.' . $ext;
                move_uploaded_file($file['tmp_name'], $filepath);

                $logoEmpreendimento = $this->logoEmpreendimento->createRow();
                $logoEmpreendimento->id_empreendimento = $_POST['Empreendimento'];
                $logoEmpreendimento->ds_path_logo = $filepath;
                $logoEmpreendimento->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');

                //Salva o logo na tabela
                $logoEmpreendimento->save();

            } catch (Exception $e) {
                $transacao->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'logoEmpreendimento', "action" => "adicionar"), array("msgFail" => 'Não foi possível salvar logo do empreendimento.'));
                exit;
            }

            $transacao->commit();

            $this->redir(array('modulo' => 'cadastro', 'controller' => 'logoEmpreendimento', "action" => "listar"), array("msgSuccess" => 'Logo cadastrada com sucesso.'));
        }

        $this->display("adicionar");
    }

    /**
     * Exibe tela de editar logo
     */
    public function editarAction()
    {

        if ($this->_isPost) {

            //Realiza a análise e carrega o arquivo na pasta
            try {
                //criar registro na tabela de logo de empreendimento
                $transacao = $this->logoEmpreendimento->getAdapter();
                $logo = $this->logoEmpreendimento->fetchRow('id_empreendimento = ' . $_POST['empreendimentoHidden']);
                $idLogo = (int)$logo['id_logo_empreendimento'];

                $transacao->beginTransaction();

                $file = $_FILES['arquivoCadastro'];

                if ($file['name'] != '') {
                    //Delete a imagem do sistema
                    $path = $logo['ds_path_logo'];
                    unlink($path);

                    //Salva nova imagem
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filepath = 'upload/cadastro/logoEmpreendimento/' . 'IMG_' . $idLogo . '_' . date('dmYHis') . '.' . $ext;
                    move_uploaded_file($file['tmp_name'], $filepath);

                    $logoEmpreendimento = $this->logoEmpreendimento->fetchRow('id_empreendimento = ' . $_POST['empreendimentoHidden']);
                    $logoEmpreendimento->ds_path_logo = $filepath;
                    $logoEmpreendimento->ds_log = Login::getUsuario()->getLogin() . ' - ' . date('d/m/Y H:i:s' . ' - ' . 'I');

                    //Salva o logo na tabela
                    $logoEmpreendimento->save();
                }

            } catch (Exception $e) {
                $transacao->rollBack();
                $this->redir(array('modulo' => 'cadastro', 'controller' => 'logoEmpreendimento', "action" => "editar"), array("msgFail" => 'Não foi possível salvar logo do empreendimento.'));
                exit;
            }

            $transacao->commit();

            $this->redir(array('modulo' => 'cadastro', 'controller' => 'logoEmpreendimento', "action" => "listar"), array("msgSuccess" => 'Logo alterada com sucesso.'));

        } else {

            $empreendimento = $this->empreendimentos->fetchRow('id = ' . $_GET['id']);

            $image = $this->logoEmpreendimento->fetchRow('id_empreendimento = ' . $_GET['id']);
            $image = $image['ds_path_logo'];

            $this->set("empreendimento", $empreendimento);
            $this->set("image", $image);
        }

        $this->display("editar");

    }

    /*
     * Exibe tabela de preços para o modal de visualizar
     */
    public function visualizarAction()
    {
        $empreendimento = $this->empreendimentos->fetchRow('id = ' . $_POST['id']);

        $image = $this->logoEmpreendimento->fetchRow('id_empreendimento = ' . $_POST['id']);
        $image = $image['ds_path_logo'];

        $this->set("empreendimento", $empreendimento);
        $this->set("image", $image);

        $this->display("visualizar");
    }

    /**
     * Remove os registros selecionados
     */
    public function deletarAction()
    {
        $registros = $_POST['linhas'];

        try {
            foreach ($registros as $registro) {
                $logo = $this->logoEmpreendimento->fetchRow('id_empreendimento = ' . $registro);

                //Delete a imagem do sistema
                $path = $logo['ds_path_logo'];
                unlink($path);

                //Apaga todos os registros na tabela de logos
                $this->logoEmpreendimento->delete('id_empreendimento = ' . $registro);
            }
        } catch (Exception $e) {
            $this->redir(array('modulo' => 'cadastro', 'controller' => 'logoEmpreendimento', "action" => "listar"), array("msgFail" => $e->getMessage()));
        }

        $this->redir(array('modulo' => 'cadastro', 'controller' => 'logoEmpreendimento', "action" => "listar"), array("msgSuccess" => 'Registros removidos com sucesso!'));

    }

}
