<?php

class RelatorioVendasController extends Controller
{

    public function __construct($request)
    {
        parent::__construct($request);

        $this->parcelaComissaoCorretor = new ParcelaComissaoCorretor();
        $this->parcelaComissaoImobiliaria = new ParcelaComissaoImobiliaria();
        $this->contrato = new Contrato();
        $this->cliente = new Cliente();
        $this->empreendimento = new Empreendimento();

    }

    /*
    * Redireciona para a ação de listar
    */
    public function indexAction()
    {

        $this->redir(array("modulo" => "relatorio", "controller" => "relatorioVendas", 'action' => 'gerar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function gerarAction()
    {
        $this->set('clienteAcade', $this->cliente->getCliente());

        $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

        if ($_POST && $this->validar()) {

            $listaContratos = $this->contrato->findContratoByEmpreendimento($_POST['emp'], $_POST['corretor'], $_POST['dataDe'], $_POST['dataAte']);

            if ($listaContratos)
                foreach ($listaContratos as &$contrato) {
                    $contrato['dt_contrato'] = ($contrato['dt_contrato'])?Helper::getDate($contrato['dt_contrato']):'';
                    //TODO: Modificar metodologia para utilizar parâmetros de comissão
                    $contrato['comissaoImobiliaria'] = $this->parcelaComissaoImobiliaria->findParcelasImobiliariaByContrato($contrato['idContrato']);
                    $contrato['comissaoCorretor'] = $this->parcelaComissaoCorretor->findParcelasCorretorByContrato($contrato['idContrato']);

                    $vlSinal = ((float)$contrato['vl_sinal']);
                    $vlIntercalada = (float)$contrato['vl_intercalada'] * (int)$contrato['nr_intercalada'];
                    $vlParcela = (float)$contrato['vl_parcela'] * (int)$contrato['nr_parcela'];
                    $vlParcelaEntrega = (float)$contrato['vl_parcela_entrega'] * (int)$contrato['nr_parcela_entrega'];
                    $contrato['vlTotalSinal'] = $vlSinal;
                    $contrato['vlTotal'] = $vlSinal + $vlIntercalada + $vlParcela + $vlParcelaEntrega;
                }

            $empreendimento = isset($_POST['empreendimento'])?$_POST['empreendimento']:'';

            $cabecalho['empreendimento'] = ($empreendimento == '*') ? 'Todos' : $listaContratos[0]['nm_empreendimento'];
            $cabecalho['corretor'] = ($_POST['corretor'] == '*') ? 'Todos' : $listaContratos[0]['nm_corretor'];
            $cabecalho['porcVenda'] = isset($listaContratos[0]['porcVenda'])?$listaContratos[0]['porcVenda'] . "%":'';
            $cabecalho['dataDe'] = $_POST['dataDe'] ? $_POST['dataDe'] : '---';
            $cabecalho['dataAte'] = $_POST['dataAte'] ? $_POST['dataAte'] : '---';

            $this->set('cabecalho', $cabecalho);

            $this->set('listaContratos', $listaContratos);
            if ($listaContratos) {

                $this->display('gerar');

                $css = array(
                    "public/libs/bootstrap/css/bootstrap.min.css",
                    "public/libs/font-awesome/css/font-awesome.min.css",
                    "public/libs/pace/pace.css",
                    "public/libs/jquery-datatables/css/dataTables.bootstrap.css",
                    "public/css/style.css",
                    "public/css/style-responsive.css",
                    "public/css/style_painel.css");

                $this->displayMPDF('relatorio_de_vendas_' . date('d/m/Y') . '.pdf', $css);
            }

        } else {
            $this->display('form');
        }
    }

    private function validar()
    {
        $campos = array('emp', 'corretor');
        $valid = true;

        foreach ($campos as $cp) {
            if ($_POST[$cp] == "") {
                $valid = false;

                switch ($cp) {
                    case "emp":
                        $msg = "Escolha um empreendimento.";
                        break;
                    case "corretor":
                        $msg = "Escolha um corretor.";
                        break;
                }

                $this->_helper->addMensagem($cp, $msg);
            }
        }

        return $valid;
    }

    /**
     * Ação responsável por pesquisar os corretores de um emprendimento e retornar em JSON os dados
     */
    public function corretor_by_empAction() {

        if ($_POST['emp']) {
            $corretores = $this->empreendimento->findCorretoresByEmp($_POST['emp']);
        }

        $options = array(
            array('id' => '', 'text' => '')
        );

        foreach($corretores as $corretor){
            array_push($options, array(
                'id' => $corretor['id'],
                'text' => $corretor['nm_pessoa']
            ));
        }

        echo json_encode($options);

    }

    /**
     * Ação responsável por pesquisar os corretores de um emprendimento e retornar em JSON os dados
     */
    public function findContratoByEmpreendimentoAction() {

        $valid = true;

        $listaContratos = $this->contrato->findContratoByEmpreendimento($_POST['cdEmpreendimento'], $_POST['cdCorretor'], $_POST['dataDe'], $_POST['dataAte']);

        if (!$listaContratos)
            $valid = false;

        echo json_encode($valid);
    }
}