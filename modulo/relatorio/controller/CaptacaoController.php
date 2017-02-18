<?php

class CaptacaoController extends Controller
{

    public function __construct($request)
    {
        parent::__construct($request);

        $this->contrato = new Contrato();
        $this->pessoa = new Pessoa();
        $this->cliente = new Cliente();
        $this->empreendimento = new Empreendimento();

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {

        $this->redir(array("modulo" => "relatorio", "controller" => "captacao", 'action' => 'gerar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function gerarAction()
    {
        $this->set('clienteAcade', $this->cliente->getCliente());

        $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

        if ($_POST && $this->validar()) {


            $dataInicial = Helper::getInputDate($_POST['dataDe']);
            $dataFinal = Helper::getInputDate($_POST['dataAte']);

            //Busca todas as indicações do empreendimento feitas pelo usuário selecionado dentro do período
            $indicacoes = Indicacao::getIndicacoesByEmpreendimentoUsuarioPeriodo($_POST['captacaoEmpreendimento'],$_POST['captador'],$dataInicial,$dataFinal,$_POST['radio']);

            $nmEmpreendimento = $indicacoes[0]['nm_empreendimento'];

            if($_POST['captador'] != '*'){
                $cabecalho['captador'] = Pessoa::findNomeById($_POST['captador']);
            } else {
                $cabecalho['captador'] = 'Todos';
            }

            //Arranja o conjunto separando por id do usuario para exibição no relatório
            $aux = array();
            foreach($indicacoes as $indicacao){
                //Calcula o valor total das parcelas do contrato
                $vlParcela = $indicacao['nr_parcela'] * $indicacao['vl_parcela'];
                $vlSinal = $indicacao['nr_parcela_sinal'] * $indicacao['vl_sinal'];
                $vlIntercalada = $indicacao['nr_intercalada'] * $indicacao['vl_intercalada'];
                $vlChave = $indicacao['nr_parcela_entrega'] * $indicacao['vl_parcela_entrega'];
                $indicacao['vlTotal'] = $vlParcela + $vlSinal + $vlIntercalada + $vlChave + $indicacao['acrescimo'] - $indicacao['desconto'];

                //Seta na variavel auxiliar os valores
                $aux[$indicacao['id_usuario']][] = $indicacao;
            }

            $cabecalho['empreendimento'] = $nmEmpreendimento;
            $cabecalho['dataDe'] = $_POST['dataDe'] ? $_POST['dataDe'] : '---';
            $cabecalho['dataAte'] = $_POST['dataAte'] ? $_POST['dataAte'] : '---';

            $this->set('cabecalho', $cabecalho);
            $this->set('listaIndicacoes', $aux);

            if (count($indicacoes)) {

                $this->display('gerar');

                $css = array(
                    "public/css/style.css",
                    "public/css/style_painel.css");

                $this->displayMPDF('relatorio_de_captações_' . date('d/m/Y') . '.pdf', $css, true, 'L');
            } else {
                $this->_helper->addMensagem('warning', 'Não foi encontrado nenhum registro.');
                $this->display('form');
            }
        } else {
            $this->display('form');
        }
    }

    private function validar()
    {
        $campos = array('captacaoEmpreendimento', 'captador');
        $valid = true;

        foreach ($campos as $cp) {
            if ($_POST[$cp] == "") {
                $valid = false;

                switch ($cp) {
                    case "captacaoEmpreendimento":
                        $msg = "Escolha um empreendimento.";
                        break;
                    case "captador":
                        $msg = "Escolha um captador.";
                        break;
                }

                $this->_helper->addMensagem($cp, $msg);
            }
        }

        return $valid;
    }

    /** Ação responsável por pesquisar os resultados e evitar carregar o pdf
     * @return int
     */
    public function findResultadosAction() {

        $dataInicial = Helper::getInputDate($_POST['dataDe']);
        $dataFinal = Helper::getInputDate($_POST['dataAte']);

        $indicacoes = Indicacao::getIndicacoesByEmpreendimentoUsuarioPeriodo($_POST['idEmpreendimento'],$_POST['captador'],$dataInicial,$dataFinal,$_POST['captacao']);

        echo count($indicacoes);
    }

    //Busca e retorna por ajax os captadores do empreendimento selecionado
    public function captadoresByEmpreendimentoAction(){
        $empreendimento = $_POST['emp'];

        $indicacoes = Indicacao::getCaptadorByEmpreendimento($empreendimento);
        $options = array(
            array('id' => '', 'text' => ''),
            array('id' => '*', 'text' => 'Todos')
        );
        foreach($indicacoes as $indicacao){
            array_push($options, array(
                'id' => $indicacao['id_usuario'],
                'text' => $indicacao['nm_pessoa']
            ));
        }

        echo json_encode($options);
    }
}