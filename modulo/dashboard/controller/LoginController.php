<?php
class LoginController extends Controller {

    protected $tb_log_acesso;

    //Função pública do construtor
    public function __construct($request)
    {
        parent::__construct($request);

        $this->tb_log_acesso = new LogAcesso();
        $this->login = new Login();
        $this->tb_usuario = new Usuario();
        $this->tb_acl = new ACL();
        $this->tb_action = new Action();
        $this->tb_modulo = new Modulo();
    }

    public function indexAction(){
        $this->display('index');
    }

    public function autenticarAction() {
        if ($this->_isPost) {

            $this->login->setLogin($_POST["txt_login"]);
            $this->login->setSenha(md5($_POST["txt_senha"]));

            try {
                $this->login->autenticar();
            } catch (LoginOutOfTimeException $eTime){
                $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Permissão Negada<i class="glyphicon glyphicon-exclamation-sign"></i></b><br> O Usuário não pode acessar o sistema neste horário.'));
            } catch (LoginNotMatchException $eMatch){}

            $tpUsuario = $this->tb_usuario->fetchByLogin($_POST["txt_login"], md5($_POST["txt_senha"]));

            if ($tpUsuario) {

                if ($tpUsuario->getSuper() != '1') {

                    $listaAclUsuario = $this->tb_acl->fetchAll('perfilId = '. $tpUsuario->getPerfilId());

                    $temPermissao = 0;

                    foreach ($listaAclUsuario as $aclUsuario) {

                        $actionAcl = $this->tb_action->fetchRow('id = '. $aclUsuario->actionId);
                        $controllerAction = $this->tb_controller->fetchRow('id = '. $actionAcl->controllerId);
                        $moduloController = $this->tb_modulo->fetchRow('id = '. $controllerAction->moduloId);

                        if ($moduloController->fl_painel_venda == '1') {
                            $temPermissao = 1;
                        }
                    }

                    $cliente = $this->tb_cliente->fetchRow();

                    if ($cliente['fl_situacao'] == 'S') {
                        $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Acesso expirado.<i class="glyphicon glyphicon-exclamation-sign"></i></b><br> Por favor, entre em contato com o suporte AcadeOne.'));
                    } elseif ($cliente['fl_situacao'] == 'M') {
                        $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Sistema em Manutenção <i class="glyphicon glyphicon-wrench"></i></b><br> Por favor, aguarde ou entre em contato com o suporte AcadeOne.'));
                    }

                    if ($temPermissao == 0) {
                        $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Permissão Negada <i class="glyphicon glyphicon-wrench"></i></b><br> O Usuário não possui acesso ao painel de vendas.'));
                    } else {
                        $this->login->autenticar();
                        $this->adicionarlogAction();
                        $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", 'action' => 'index'));
                    }

                } else {
                    $this->login->autenticar();
                    $this->adicionarlogAction();
                    $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", 'action' => 'index'));
                }

            } else {
                $this->redir(array("modulo" => "dashboard", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Login ou Senha Incorreto <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></b><br> O login ou senha digitados não pertence a nenhuma conta.'));
            }

        }

    }

    public function logoutAction(){
        $this->login->logout();
        $this->redir(array());
    }


    //Função pública responsável por adicionar um novo usuário
    public function adicionarlogAction()
    {
        //Carrega os campos da pessoa caso ela já exista
        $l = $this->tb_log_acesso->createRow();
        $l->id_usuario =$this->login->getUsuario()->getId();
        $l->dt_acesso = date("Y/m/d");
        $l->hr_acesso = date("H:i:s");
        $l->ip_acesso = $_SERVER['REMOTE_ADDR'];
        $l->fl_painel_vendas = '1';
        $l->save();
    }
}
