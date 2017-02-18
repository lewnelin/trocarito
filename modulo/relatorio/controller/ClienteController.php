<?php

class ClienteController extends Controller
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

        $this->redir(array("modulo" => "relatorio", "controller" => "cliente", 'action' => 'gerar'));

    }

    /*
    * Exibe tela de tabela de preços e, se ha requisicao ajax, faz consulta no banco e exibe listagem de tabelas
    */
    public function gerarAction()
    {
        $this->set('clienteAcade', $this->cliente->getCliente());

        $this->set('listaEmpreendimentos', $this->empreendimento->getEmpreendimentosUsuario());

        if ($_POST && $this->validar()) {

            //Constroi o where que vai ser usado na query de pessoas
            $where = '';
            $tipo = '';
            $dataInicial = Helper::getInputDate($_POST['dataDe']);
            $dataFinal = Helper::getInputDate($_POST['dataAte']);
            $nmEmpreendimento = Empreendimento::getNomeById($_POST['clienteEmpreendimento']);

            switch($_POST['tpCliente']){
                //Com contratos
                case 'cc':
                    $tipo = 'Com contrato';
                    $where .= 'e.id = "' . $_POST['clienteEmpreendimento'] . '" AND ';
                    $where .= 'c.fl_distrato = "0" AND ';
                    $where .= 'dt_contrato >= "' . $dataInicial . '" AND ';
                    $where .= 'dt_contrato <= "' . $dataFinal . '"';
                    break;
                //Com distratos
                case 'cd':
                    $tipo = 'Com distrato';
                    $where .= 'e.id = "' . $_POST['clienteEmpreendimento'] . '" AND ';
                    $where .= 'c.fl_distrato = "1" AND ';
                    $where .= 'dt_distrato >= "' . $dataInicial . '" AND ';
                    $where .= 'dt_distrato <= "' . $dataFinal . '"';
                    break;
                //Avulso
                case 'sc':
                    $tipo = 'Avulso';
                    $where .= 'c.id IS NULL AND ';
                    $where .= 'dt_cadastro >= "' . $dataInicial . '" AND ';
                    $where .= 'dt_cadastro <= "' . $dataFinal . '"';
                    break;
            }

            $listaPessoas = $this->pessoa->findPessoasFisicasDadosByEmpreendimento($where);


            if(count($listaPessoas)){
                $auxLista = array();
                foreach($listaPessoas AS $k => $item){
                    $auxLista[$item['nm_pessoa']][] = $item;
                }
                $listaPessoas = $auxLista;
            }

            $cabecalho['tpCliente'] = $tipo;
            $cabecalho['empreendimento'] = $nmEmpreendimento;
            $cabecalho['dataDe'] = $_POST['dataDe'] ? $_POST['dataDe'] : '---';
            $cabecalho['dataAte'] = $_POST['dataAte'] ? $_POST['dataAte'] : '---';

            $this->set('cabecalho', $cabecalho);
            $this->set('listaPessoas', $listaPessoas);

            if (count($listaPessoas)) {

                $this->display('gerar');

                $css = array(
                    "public/css/style.css",
                    "public/css/style_painel.css");

                $this->displayMPDF('relatorio_de_cliente_' . date('d/m/Y') . '.pdf', $css, true, 'L');
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
        $campos = array('clienteEmpreendimento', 'tpCliente');
        $valid = true;

        foreach ($campos as $cp) {
            if ($_POST[$cp] == "") {
                $valid = false;

                switch ($cp) {
                    case "clienteEmpreendimento":
                        $msg = "Escolha um empreendimento.";
                        break;
                    case "tpCliente":
                        $msg = "Escolha um cliente.";
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

        //Constroi o where que vai ser usado na query de pessoas
        $where = '';
        $dataInicial = Helper::getInputDate($_POST['dataDe']);
        $dataFinal = Helper::getInputDate($_POST['dataAte']);

        switch($_POST['tpCliente']){
            //Com contratos
            case 'cc':
                $where .= 'e.id = "' . $_POST['idEmpreendimento'] . '" AND ';
                $where .= 'c.fl_distrato = "0" AND ';
                $where .= 'dt_contrato >= "' . $dataInicial . '" AND ';
                $where .= 'dt_contrato <= "' . $dataFinal . '"';
                break;
            //Com distratos
            case 'cd':
                $where .= 'e.id = "' . $_POST['idEmpreendimento'] . '" AND ';
                $where .= 'c.fl_distrato = "1" AND ';
                $where .= 'dt_distrato >= "' . $dataInicial . '" AND ';
                $where .= 'dt_distrato <= "' . $dataFinal . '"';
                break;
            //Avulso
            case 'sc':
                $where .= 'c.id IS NULL AND ';
                $where .= 'dt_cadastro >= "' . $dataInicial . '" AND ';
                $where .= 'dt_cadastro <= "' . $dataFinal . '"';
                break;
        }

        $listaPessoas = $this->pessoa->findPessoasFisicasDadosByEmpreendimento($where);

        echo count($listaPessoas);
    }
}