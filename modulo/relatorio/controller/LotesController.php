<?php

class LotesController extends Controller
{
    public function __construct($request)
    {
        parent::__construct($request);

        $this->cliente = new Cliente();
        $this->empreendimento = new Empreendimento();
        $this->lote = new Lote();
        $this->tb_tabela_preco = new TabelaPreco();
        $this->tb_tabela_preco_lotes = new TabelaPrecoLotes();
    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {
        $this->redir(array("modulo" => "relatorio", "controller" => "lotes", 'action' => 'gerar'));
    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function gerarAction()
    {
        $this->set('clienteAcade', $this->cliente->getCliente());
        $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

        if ($this->_isPost && $this->validar()) {
            try {
                $reservados = ($_POST['reservados'] == '0') ? false : true;
                $listaLotes = $this->lote->getLotesDisponiveisEmpreendimento($_POST['emp'], $_POST['quadra'], null, isset($_POST['tabela']) ? ($_POST['tabela']) : null, $reservados);
                $nmEmpreendimento = Empreendimento::getNomeById($_POST['emp']);
                $tabelaPreco = $this->empreendimento->listaTabelaPrecoEmpreendimento($_POST['emp']);
                $nomeTabela = '';
                foreach ($tabelaPreco as $tb) {
                    $nomeTabela = $tb['nm_tabela'];
                }
                $cabecalho['empreendimento'] = $nmEmpreendimento;
                $cabecalho['quadra'] = ($_POST['quadra'] == '*') ? 'Todos' : ($_POST['quadra']);
                $cabecalho['tabela'] = isset($_POST['tabela']) ? $nomeTabela : '-';
                $this->set('cabecalho', $cabecalho);

                if ($listaLotes) {
                    $this->set('listaLotes', $listaLotes);
                    $this->display('gerar');
                    $this->displayMPDF('relatorio_de_lote.pdf');
                } else {
                    $this->_helper->addMensagem('msgFail', 'Não foram encontrados registros com o filtro selecionado.');
                    $this->display('form');
                }
            } catch (Exception $e) {
                $this->_helper->addMensagem('msgFail', 'Não foi possível realizar a operação. Erro: ' . $e->getMessage());
                $this->display('form');
            }
        } else {
            $this->display('form');
        }
    }

    private function validar()
    {
        $campos = array('emp', 'quadra');
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
                }
                $this->_helper->addMensagem($cp, $msg);
            }
        }
        return $valid;
    }

    /**
     * Ação responsável por pesquisar as quadras de um emprendimento e retornar em JSON os dados
     */
    public function findQuadraByEmpreendimentoAction()
    {
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

    public function findTabelaPrecobyEmpreendimentoAction()
    {
        if ($_POST['cdEmpreendimento']) {
            $valid = true;
            $jsonTabelasPreco = array();
            $tabelasPrecoEmpreendimento = $this->empreendimento->listaTabelaPrecoEmpreendimento($_POST['cdEmpreendimento']);

            if (!$tabelasPrecoEmpreendimento) {
                $valid = false;
            } else {
                foreach ($tabelasPrecoEmpreendimento as $tabelaPreco) {
                    $jsonTabelasPreco[] = array('id' => $tabelaPreco['id_tabela_preco'], 'text' => $tabelaPreco['nm_tabela']);
                }
            }
            echo json_encode(array('valid' => $valid, 'tabela' => $jsonTabelasPreco));
        }
    }

    public function findResultadosAction()
    {
        $valid = true;
        echo json_encode($valid);
    }
}