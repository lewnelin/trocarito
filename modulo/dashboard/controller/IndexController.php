<?php

class IndexController extends Controller
{
    private $instituicao;
    private $user;

    /**
     * IndexController constructor.
     */
    public function __construct($request)
    {
        parent::__construct($request);
        $this->instituicao = new Instituicao();
        $this->user = $_SESSION['trocarito'];
    }

    public function indexAction()
    {
        $instituicoes = $this->instituicao->getAdapter()
            ->select()->where('id_usuario = ?',$this->user->id_usuario)
            ->query()->fetchAll();

        var_dump($instituicoes); exit;
        $this->display('index');
    }
}
