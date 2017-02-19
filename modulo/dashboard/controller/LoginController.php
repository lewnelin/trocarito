<?php

class LoginController extends Controller
{

    //Função pública do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->login = new Login();
        $this->tb_usuario = new Usuario();
    }

    public function indexAction()
    {
        $this->display('index');
    }

    public function autenticarAction()
    {
        if ($this->_isPost) {

            $this->login->setLogin($_POST["txt_login"]);
            $this->login->setSenha(md5($_POST["txt_senha"]));

            try {
                $this->login->autenticar();
            } catch (LoginOutOfTimeException $eTime) {
                $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Permissão Negada<i class="glyphicon glyphicon-exclamation-sign"></i></b><br> O Usuário não pode acessar o sistema neste horário.'));
            } catch (LoginNotMatchException $eMatch) {
            }

            $tpUsuario = $this->tb_usuario->fetchByLogin($_POST["txt_login"], md5($_POST["txt_senha"]));

            if ($tpUsuario) {
                    $this->login->autenticar();
                    $this->redir(array("modulo" => "dashboard", "controller" => "index", 'action' => 'index'));

            } else {
                $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Login ou Senha Incorreto <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></b><br> O login ou senha digitados não pertence a nenhuma conta.'));
            }

        }

    }

    public function logoutAction()
    {
        $this->login->logout();
        $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'));
    }


    //Função pública responsável por adicionar um novo usuário
    public function adicionarlogAction()
    {
        //Carrega os campos da pessoa caso ela já exista
        $l = $this->tb_log_acesso->createRow();
        $l->id_usuario = $this->login->getUsuario()->getId();
        $l->dt_acesso = date("Y/m/d");
        $l->hr_acesso = date("H:i:s");
        $l->ip_acesso = $_SERVER['REMOTE_ADDR'];
        $l->fl_painel_vendas = '1';
        $l->save();
    }
}
