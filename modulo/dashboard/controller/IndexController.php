<?php

class IndexController extends Controller
{
    private $instituicao;
    private $doacao;
    private $user;

    /**
     * IndexController constructor.
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->user = $_SESSION['rotaserumos'];
    }

    public function indexAction()
    {
        error_reporting(1);

        $this->set('user', $this->user);

        $this->display('index');
    }

    public function buscaRotaAction()
    {
        $resultado = Funcoes::onibusTrecho($_POST['dsOrigem']);

        var_dump($resultado); exit;
    }
}
