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
        $this->instituicao = new Instituicao();
        $this->doacao = new Doacao();
        $this->user = $_SESSION['trocarito'];
    }

    public function indexAction()
    {
        error_reporting(1);
        $doacoes = $this->doacao->getAdapter()
            ->select()->from(array('d' => 'doacao'))
            ->join(array('i' => 'instituicao'), 'd.id_instituicao = i.id_instituicao', array('*'))
            ->where('d.id_usuario = ?', $this->user->id_usuario)
            ->query()->fetchAll();

        $vlDoado = array('total' => 0);
        $instituicoes = array();
        foreach ($doacoes as $doacao) {
            $vlDoado['total'] += (float)$doacao['valor'];
            $vlDoado['instituicao'][$doacao['id_instituicao']] += (float)$doacao['valor'];
            $instituicoes[$doacao['id_instituicao']] = $doacao;
        }

        $biggestUser = Usuario::getMaiorDoador();

        $this->set('instituicoes', $instituicoes);
        $this->set('doacoes', $doacoes);
        $this->set('vlDoado', $vlDoado);
        $this->set('doador', $biggestUser);
        $this->set('user', $this->user);

        $this->display('index');
    }
}
