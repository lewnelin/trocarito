<?php

class InstituicaoController extends Controller
{

    //Função pública do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->login = new Login();
        $this->usuario = new Usuario();
        $this->instituicao = new Instituicao();
        $this->categoria = new Categoria();
    }

    public function indexAction()
    {
        $this->set('listaInstituicao', $this->instituicao->fetchAll('fl_ativo = "1"'));

        $this->display('index');
    }

    public function autenticarAction()
    {
        $this->login->setLogin($_POST["login"]);
        $this->login->setSenha(md5($_POST["senha"]));

        try {
            $this->login->autenticar();
        } catch (LoginOutOfTimeException $eTime) {
            $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Permissão Negada<i class="glyphicon glyphicon-exclamation-sign"></i></b><br> O Usuário não pode acessar o sistema neste horário.'));
        }

        $tpUsuario = $this->usuario->fetchByLogin($_POST["login"], md5($_POST["senha"]));

        if ($tpUsuario) {
            $this->login->autenticar();
            $this->redir(array("modulo" => "dashboard", "controller" => "index", 'action' => 'index'));

        } else {
            $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Login ou Senha Incorreto <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></b><br> O login ou senha digitados não pertence a nenhuma conta.'));
        }
    }

    public function logoutAction()
    {
        $this->login->logout();
        $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'));
    }

    //Função pública responsável por adicionar um novo usuário
    public function cadastrarAction()
    {
        if ($this->_isPost && $this->valida(array('nome', 'email', 'senha'))) {
            $usuarioT = $this->usuario->getAdapter();
            $usuarioT->beginTransaction();

            $usuario = $this->usuario->createRow();
            $usuario->nome = $this->_helper->filters($_POST['nome']);
            $usuario->email = $this->_helper->filters($_POST['email']);
            $usuario->senha = $this->_helper->filters(md5($_POST['senha']));
            $usuario->fl_admin = 0;
            $usuario->save();

            $usuarioT->commit();
            $this->set('success', '<div class="alert alert-success ">Cadastro realizado com sucesso!</div>');
            $this->display('index');
            exit;
        }
        $this->display('cadastrar');
    }

    //Função pública responsável por adicionar um novo usuário
    public function editarAction()
    {

        $id = $this->_helper->filters($_GET['id'], 'onlyNumber');

        $instituicao = $this->instituicao->fetchRow('id_instituicao = ' . $id);

        if (!$instituicao) {
            $this->set('fail', '<div class="alert alert-danger ">Instituição não encontrada!</div>');
            $this->indexAction();
            exit;
        }

        $categoria = $this->categoria->fetchAll();
        $usuario = $this->usuario->fetchRow('id_usuario = ' . $instituicao['id_usuario']);
        $this->set('listaCategoria', $categoria);
        $this->set('usuario', $usuario);
        $this->set('instituicao', $instituicao);

        if ($this->_isPost && $this->valida(array('nomeRepresentante', 'email', 'nomeInstituicao', 'categoria', 'descricao'))) {
            $usuarioT = $this->usuario->getAdapter();
            $usuarioT->beginTransaction();

            $usuario->nome = $this->_helper->filters($_POST['nomeRepresentante']);
            $usuario->email = $this->_helper->filters($_POST['email']);

            if ($_POST['senha'] != '')
                $usuario->senha = $this->_helper->filters($_POST['senha'], 'md5');
            $usuario->save();

            $instituicao->id_categoria = $this->_helper->filters($_POST['categoria']);
            $instituicao->nome = $this->_helper->filters($_POST['nomeInstituicao']);
            $instituicao->descricao = $this->_helper->filters($_POST['descricao']);
            $instituicao->save();

            $usuarioT->commit();
            $this->set('success', '<div class="alert alert-success ">Alteração realizada com sucesso!</div>');
            $this->indexAction();
            exit;
        }
        $this->display('editar');
    }

    /**
     * Remove os registros selecionados
     */
    public function deletarAction()
    {

        $id = $this->_helper->filters($_GET['id'], 'onlyNumber');

        $instituicao = $this->instituicao->fetchRow('id_instituicao = ' . $id . ' AND fl_ativo = "1"');

        if (!$instituicao) {
            $this->set('fail', '<div class="alert alert-danger ">Instituição não encontrada!</div>');
            $this->indexAction();
            exit;
        }

        $instituicao->fl_ativo = 0;
        $instituicao->save();

        $this->set('success', '<div class="alert alert-success ">Deletado com sucesso!</div>');
        $this->indexAction();
        exit;


    }

    private function valida($campos, $view = '')
    {
        $valid = true;
        foreach ($campos as $campo) {
            if ($_POST[$campo] == '') {
                $valid = false;
                $this->set($campo, '<div style="color: red"> O campo <strong>' . $campo . '</strong> é obrigatório.</div>');
            }
        }

        if (!$valid) {
            $this->display(($view != '') ? $this->action : $view);
        } else {
            return $valid;
        }
    }
}
