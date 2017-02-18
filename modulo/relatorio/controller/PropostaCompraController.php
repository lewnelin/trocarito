<?php

class PropostaCompraController extends Controller
{

    public function __construct($request)
    {
        parent::__construct($request);

        $this->contrato = new Contrato();
        $this->cliente = new Cliente();
        $this->empreendimento = new Empreendimento();
        $this->lote = new Lote();

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {

        $this->redir(array("modulo" => "relatorio", "controller" => "propostaCompra", 'action' => 'gerar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function gerarAction()
    {
        $this->set('clienteAcade', $this->cliente->getCliente());

        $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

        if ($_POST && $this->validar()) {

            //Ao validar o formulário redireciona para o gerar proposta do dashboard passando o id do lote
            $this->redir(array("modulo" => "dashboard", "controller" => "dashboard", 'action' => 'gerarProposta'), array('idLote' => $_POST['lote']));

        } else {
            $this->display('form');
        }
    }

    private function validar()
    {
        $campos = array('emp', 'quadra', 'lote');
        $valid = true;

        foreach ($campos as $cp) {
            if ($_POST[$cp] == "") {
                $valid = false;

                switch ($cp) {
                    case "emp":
                        $msg = "Escolha um empreendimento.";
                        break;
                    case "quadra":
                        $msg = "Escolha uma quadra.";
                        break;
                    case "lote":
                        $msg = "Escolha um lote.";
                        break;
                }

                $this->_helper->addMensagem($cp, $msg);
            }
        }

        return $valid;
    }

    /**
     * Ação responsável por pesquisar as quadras de um emprendimento e retornar em JSON os dados
     */
    public function findQuadraByEmpreendimentoAction() {

        $valid = true;

        $quadras = $this->empreendimento->listaQuadraEmpreendimento($_POST['cdEmpreendimento']);

        $jsonQuadraEmpreendimento[] = array('id' => '', 'text' => '');
        if (!$quadras) {
            $valid = false;
        } else {
            $jsonQuadraEmpreendimento[] = array('id' => '*', 'text' => 'Todos');
            foreach ($quadras as $quadraEmpreendimento) {
                $jsonQuadraEmpreendimento[] = array('id' => $quadraEmpreendimento['quadra'], 'text' => 'Quadra ' . $quadraEmpreendimento['quadra']);
            }
        }

        echo json_encode(array('valid' => $valid, 'quadras' => $jsonQuadraEmpreendimento));
    }


    /**
     * Ação responsável por pesquisar os lotes de uma quadra de um emprendimento e retornar em JSON os dados
     */
    public function findLoteByQuadraAction() {

        $valid = true;

        if($_POST['quadra'] == '*'){
            $_POST['quadra'] = null;
        }

        $lotes = $this->lote->getLotesNegociadosEmpreendimento($_POST['cdEmpreendimento'],$_POST['quadra']);

        $jsonLoteQuadra[] = array('id' => '', 'text' => '');

        if (!$lotes) {
            $valid = false;
        } else {
            foreach ($lotes as $lote) {
                $jsonLoteQuadra[] = array('id' => $lote['idLote'], 'text' => 'Lote ' . $lote['lote'] . ' Qd: ' . $lote['quadra'] . ' - ' . $lote['nm_pessoa']);
            }
        }

        echo json_encode(array('valid' => $valid, 'lotes' => $jsonLoteQuadra));
    }
}