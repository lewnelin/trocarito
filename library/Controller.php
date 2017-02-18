<?php

class ControllerPrivateException extends Exception
{

    public function __construct()
    {
        $this->code = 0;
        $this->message = 'Ação privada, é necessário estar logado para acessar essa área.';
    }

}

class ControllerNoPrivilegeException extends Exception
{

    public function __construct()
    {
        $this->code = 1;
        $this->message = 'Você não ter permissão de acessar essa área.';
    }

}

class Controller
{

    protected $modulo;
    protected $controller;
    protected $action;
    protected $view;
    protected $_isPost;
    protected $_isGet;
    private $_loginController;
    public $_helper;
    protected $_lastView;
    protected $dados;

    public function __construct($request)
    {


        $mod = $request["modulo"];
        $cot = $request["controller"];
        $act = $request["action"];

        $cot[0] = strtolower($cot[0]);

        $this->modulo = $mod;
        $this->controller = $cot;
        $this->action = $act;

        $this->dados = array();

        $this->_helper = new Helper();

        $this->_isPost = ($_SERVER['REQUEST_METHOD'] == "POST");
        $this->_isGet = ($_SERVER['REQUEST_METHOD'] == "GET");
    }

    private function getInstanceController($controller)
    {

        $instancia = null;
        $controller = ucfirst($controller) . "Controller";
        $controller_path = "modulo/" . $this->modulo . "/controller/{$controller}.php";

        if (file_exists($controller_path) && !is_dir($controller_path)) {
            require_once $controller_path;
        } else {
            throw new Exception("Arquivo do controller não encontrado.");
        }

        if (class_exists($controller)) {
            $request = array("modulo" => $this->modulo, "controller" => $this->controller, "action" => $this->action);
            $instancia = new $controller($request);
            return $instancia;
        } else {
            JScript::alert("Controlador não encontrado!!!");
        }
    }

    private function execAction($controller, $action)
    {
        $action = $action . "Action";

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            throw new Exception("A action não existe");
        }
    }

    public function processRequest()
    {
        if (Login::isLogado()) {
            if (ACL::hasPermission(Login::getUsuario(), $action->getId())) {
                $execucao = $this->getInstanceController($controller->getNome());
                $this->execAction($execucao, $action->getNome());
            } else {
                $this->redir(array("modulo" => "", "controller" => "dashboard", 'action' => 'painelVenda'), array("permissao" => '<b>O Usuário não possui permissão para acessar esta ação.</b>'));
            }
        }
    }

    public
    function display($view = null)
    {
        if (!$view) {

            $view = $this->action;
        }
        $this->_lastView = $view;

        $view .= '.php';

        $view_path = "modulo/" . $this->modulo . "/view/" . $this->controller . "/";

        if (file_exists($view_path . $view) && !is_dir($view_path . $view)) {
            require_once $view_path . $view;
        } else {
            #throw new Exception("A view não pode ser carregada.");
            JScript::alert("A visualisação não pode ser completada!!!");
        }
    }

    public
    function displayMPDF($file = null, $css = null, $inMemory = true, $orientation = 'P', $format = "A4", $margens = array(5, 5, 5, 8))
    {

        require_once dirname(__FILE__) . '/../vendor/mpdf/mpdf/mpdf.php';

        if (!Login::getUsuario())
            $this->redir(array("modulo" => "", "controller" => "login", 'action' => 'index'), array("msg" => '<b>Acesso expirado <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span></b><br> Entre novamente no sistema.'));

        if (empty($this->_lastView))
            $this->display($this->action);

        $view = $this->_lastView . ".php";
        $view_path = "modulo/" . $this->modulo . "/view/" . $this->controller . "/";
        if (file_exists($view_path . $view) && !is_dir($view_path . $view)) {
            $content = ob_get_contents();
            ob_get_clean();
        } else {
            throw new Exception("A view não pode ser carregada.");
        }

        if ($orientation == 'L') {
            $mpdf = new mPdf('', 'A4-L', '', '', 15, 15, 20, 20, 5, 5, 'L');
        } else {
            $mpdf = new mPdf('', 'A4', '', '', 15, 15, 20, 20, 5, 5, 'L');
        }
        $mpdf->SetDisplayMode('fullwidth');

        $cabecalho['impresso'] = 'Impresso por ' . Login::getUsuario()->getLogin() . ' em ' . date('d/m/Y') . ' às ' . date('H:i');

        $footer = "Painel de Vendas / AcadeOne Softwares";

        $mpdf->SetFooter($cabecalho['impresso'] . '    -    ' . $footer . '  Página {PAGENO}');

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);

        if (is_array($css)) {
            foreach ($css as $file) {
                $estilo = file_get_contents($file);
                $mpdf->WriteHTML($estilo, 1);
            }
        }

        $mpdf->WriteHTML($content);

        $mpdf->Output();

        exit();
    }

    public
    function set($k, $v)
    {
        $this->dados[$k] = $v;
    }

    /**
     * Esse metodo e invocado pela view para recuperacao dos dados do controler
     *
     * @param $k
     * @param boolean $html_espape = false
     */
    public
    function get($k, $html_espape = false)
    {
        if (array_key_exists($k, $this->dados)) {
            if ($html_espape) {
                return htmlentities($this->dados[$k]);
            } else {
                return $this->dados[$k];
            }
        } else
            return null;
    }

    /**
     * Função responśavel por fazer o redirecionamento de ações
     *
     * @param array $request
     * @param array $mensagem
     */
    public
    function redir(array $request, array $mensagem = array())
    {
        $link = array("m" => $request["modulo"], "c" => $request["controller"], "a" => $request["action"]);
        foreach ($mensagem as $k => $v) {
            $link[$k] = $v;
        }
        header("location: " . $this->_helper->getLink($link));
    }

}
