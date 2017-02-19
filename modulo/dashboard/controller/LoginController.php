<?php

class LoginController extends Controller
{

    //Função pública do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->login = new Login();
        $this->usuario = new Usuario();
    }

    public function indexAction()
    {
        if ($this->login->getUsuario())
            $this->redir(array("modulo" => "dashboard", "controller" => "index", 'action' => 'index'));


        if ($this->_isPost && $this->valida(array('login', 'senha'))) {
            $this->autenticarAction();
        }

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
