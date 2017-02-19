<?php

class Helper
{

    private $mensagem;
    static $DIAS = array("Domingo", "Segunda", "Ter�a", "Quarta", "Quinta", "Sexta", "S�bado");
    static $MESES = array("01" => "Janeiro", "02" => "Fevereiro", "03" => "Mar�o", "04" => "Abril", "05" => "Maio", "06" => "Junho", "07" => "Julho", "08" => "Agosto", "09" => "Setembro", "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro");

    public function __construct()
    {
        $this->mensagem = array();
    }

    public static function formataDataBoleto($data)
    {
        if ($data) {
            return substr($data, 0, 2) . '/' . substr($data, 2, 2) . '/' . substr($data, 4, 4);
        } else {
            return '';
        }
    }

    public static function dataParaBrasil($d)
    {
        $data = new Zend_Date($d);
        $data->setLocale('pt_BR');
        return $data->get('dd/MM/Y');
    }

    /**
     * Trata as datas enviadas pela API do PHPExcel, transformando em formato americano
     * @param $d
     * @return string
     */
    public static function dataAbreviadaFromExcel($d)
    {
        if ($d != '') {
            if (count(explode('/', $d)) > 1) {
                $ret = self::dataParaAmericano($d);
            } else {
                $data = explode('-', $d);
                if (strlen($data[2]) == 2) $data[2] = '20' . $data[2];

                $ret = $data[2] . '-' . $data[0] . '-' . $data[1];
            }
        } else {
            $ret = '';
        }
        return $ret;
    }

    public static function dataParaAmericano($d)
    {
        $data = new Zend_Date($d);
        $data->setLocale('en_US');
        return $data->get('yyyy-MM-dd');
    }

    public static function getDia($k)
    {
        return self::$DIAS[$k];
    }

    public static function getMes($k)
    {
        return self::$MESES[$k];
    }

    public function getLink(array $param = array())
    {
        $url = "?";
        $vars = array();
        foreach ($param as $k => $v) $vars[] = "{$k}={$v}";
        return $url . implode("&", $vars);
    }

    public function getMensagem($k)
    {
        if (array_key_exists($k, $this->mensagem)) return $this->mensagem[$k];
    }

    public function addMensagem($k, $v)
    {
        $this->mensagem[$k] = $v;
    }

    public function removerMensagem($k)
    {
        unset($this->mensagem[$k]);
    }

    public function getMensagens()
    {
        return $this->mensagem;
    }

    public static function getDate($data = '')
    {
        if ($data == '0000-00-00') $data = '';

        if ($data) {
            $data = date('d/m/Y', strtotime($data));;
        }

        return $data;
    }

    public static function dateIsValid($date)
    {
        $date = date_parse($date);
        return checkdate($date['month'], $date['day'], $date['year']);
    }

    /**
     * Modifica a data do formato brasileiro para o formato do banco de dados
     * @param $data
     * @return bool|string
     */
    public static function getInputDate($data)
    {
        if ($data != '') {
            $data_br = explode("/", $data);
            return $data_br[2] . "-" . $data_br[1] . "-" . $data_br[0];
        } else {
            return false;
        }
    }

    /**
     * Modifica o formato da moeda para o formato do banco
     * @param $money
     * @return float|null
     */
    public static function getInputMoney($money)
    {
        if ($money != '') {
            $money = str_replace(".", "", $money);
            $money = str_replace(",", ".", $money);

            return (float)$money;
        } else return null;
    }

    /**
     * Testa se o valor é em formato brasileiro e remove o caracter separador de milhar
     * @param $money
     * @return float
     */
    public static function getExcelMoney($money)
    {
        if (is_string($money)) {
            if (self::isMoneyBrasil($money)) {
                $money = self::getInputMoney($money);
            } else {
                if (strpos($money, '.') > 0) {
                    $money = str_replace(",", "", $money);
                } else {
                    $money = preg_replace('[,(0)*]', '', $money);
                }
            }

            return (float)$money;
        } else {
            return $money;
        }
    }

    /**
     * Testa se o formato de dinheiro é brasileiro no formato ##.###,##
     * @param $valor
     * @return bool
     */
    public static function isMoneyBrasil($valor)
    {
        $valor = (string)$valor;
        $regra = "/^[0-9]{1,3}([.]([0-9]{3}))*[,]([.]{0})[0-9]{0,2}$/";
        if (preg_match($regra, $valor)) {
            return true;
        } else {
            return false;
        }
    }

    public static function getMoney($money = '0,00', $precisao = 2)
    {
        if ($money > 0) $money = number_format((float)$money, $precisao, ",", ".");

        if ($money == '0.00') $money = '0,00';
        return $money;
    }

    /*
     * Retorna os valores em float
     */
    public static function parseFloat($number)
    {

        /*
         * Se o numero tiver ponto e virgula
         * ex: 1.000,00 - 100.000.000,00
         * retorna: 1000.00 - 100000000.00
         */
        if (strripos($number, '.') && strripos($number, ',')) {
            $number = str_replace(',', '.', str_replace('.', '', $number));
            return $number;
        }

        /*
         * Se o numero tiver virgula e n�o tiver ponto
         * ex: 500,00 - 10,00 - 5,9
         * retorna: 500.00 - 10.00 - 5.9
         */
        if (strripos($number, ',') && strripos($number, '.') == false) {
            $number = str_replace(',', '.', $number);
            return $number;
        }

        return $number;

    }

    public static function validaCPF($cpf)
    { // Verifiva se o n�mero digitado cont�m todos os digitos
        $cpf = str_replace(array(".", "-"), array("", ""), $cpf);

        // Verifica se nenhuma das sequ�ncias abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else { // Calcula os n�meros para verificar se o CPF � verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * @return CNPJ com M�scara
     */
    public function cnpjMask($cnpj)
    {
        return $cnpj[0] . $cnpj[1] . "." . $cnpj[2] . $cnpj[3] . $cnpj[4] . "." . $cnpj[5] . $cnpj[6] . $cnpj[7] . "/" . $cnpj[8] . $cnpj[9] . $cnpj[10] . $cnpj[11] . "-" . $cnpj[12] . $cnpj[13];
    }

    public static function getMaskCNPJ($val)
    {
        if (strlen($val) == 14) {
            $mask = "##.###.###/####-##";

            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i])) $maskared .= $mask[$i];
                }
            }
            return $maskared;
        } else
            return $val;

    }

    /**
     * Retorna valor com maskara de CPF
     * @param $val
     * @return string
     */
    public static function getMaskCPF($val)
    {
        if (!self::validaCPF($val)) {
            $mask = "###.###.###-##";

            $maskared = '';
            $k = 0;
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i])) $maskared .= $mask[$i];
                }
            }
            return $maskared;
        } else
            return $val;
    }

    public static function getMaskCEP($val)
    {
        $mask = "##.###-###";

        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;

    }

    /**
     * @return boolean
     */
    public function validaCNPJ($cnpj)
    {
        if (strlen($cnpj) <> 18) return false;

        $soma1 = ($cnpj[0] * 5) + ($cnpj[1] * 4) + ($cnpj[3] * 3) + ($cnpj[4] * 2) + ($cnpj[5] * 9) + ($cnpj[7] * 8) + ($cnpj[8] * 7) + ($cnpj[9] * 6) + ($cnpj[11] * 5) + ($cnpj[12] * 4) + ($cnpj[13] * 3) + ($cnpj[14] * 2);
        $resto = $soma1 % 11;
        $digito1 = $resto < 2 ? 0 : 11 - $resto;
        $soma2 = ($cnpj[0] * 6) + ($cnpj[1] * 5) + ($cnpj[3] * 4) + ($cnpj[4] * 3) + ($cnpj[5] * 2) + ($cnpj[7] * 9) + ($cnpj[8] * 8) + ($cnpj[9] * 7) + ($cnpj[11] * 6) + ($cnpj[12] * 5) + ($cnpj[13] * 4) + ($cnpj[14] * 3) + ($cnpj[16] * 2);
        $resto = $soma2 % 11;
        $digito2 = $resto < 2 ? 0 : 11 - $resto;

        return (($cnpj[16] == $digito1) && ($cnpj[17] == $digito2));
    }

    /**
     * @return seconds
     */
    public static function date_diff($from, $to, $type = 'day')
    {
        $diff = strtotime($to) - strtotime($from);
        switch ($type) {
            case "day":
                return (int)($diff / 60 / 60 / 24);
            case "month":
                return (int)($diff / 60 / 60 / 24 / 31);
        }
    }

    /**
     * @return string sem acento e com h�fem entre espa�os
     */
    public static function removeAcentos($arq, $comUnderline = true)
    {
        $a = array('/[�����]/' => 'A', '/[�����]/' => 'a', '/[����]/' => 'E', '/[����]/' => 'e', '/[����]/' => 'I', '/[����]/' => 'i', '/[�����]/' => 'O', '/[�����]/' => 'o', '/[����]/' => 'U', '/[����]/' => 'u', '/�/' => 'n', '/�/' => 'c', '/�/' => 'C', '/�/' => '', '/\'/' => '', '/ /' => '_', '/  /' => '_', '/   /' => '_');
        if (!$comUnderline) {
            unset($a['/ /'], $a['/  /'], $a['/   /']);
        }
        return preg_replace(array_keys($a), array_values($a), $arq);
    }

    public static function retirar_caracteres_especiais($string)
    {
        $palavra = strtr($string, "???????��������������������������������������������������������������", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
        $palavranova = str_replace("_", " ", $palavra);
        return $palavranova;
    }

    public static function valorPorExtenso($valor = 0, $maiusculas = false)
    {
        // verifica se tem virgula decimal
        if (strpos($valor, ",") > 0) {
            // retira o ponto de milhar, se tiver
            $valor = str_replace(".", "", $valor);

            // troca a virgula decimal por ponto decimal
            $valor = str_replace(",", ".", $valor);
        }
        $singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o");
        $plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es", "quatrilh�es");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis", "sete", "oito", "nove");
        $errado = array(" e cento", " e duzentos", " e trezentos", " e quatrocentos", " e quinhentos", " e seissentos", " e setecentos", " e oitocentos", " e novecentos", " e mil");
        $certo = array(", cento", ", duzentos", ", trezentos", ", quatrocentos", ", quinhentos", ", seissentos", ", setecentos", ", oitocentos", ", novecentos", ", mil");

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        $cont = count($inteiro);
        $rt = '';
        for ($i = 0; $i < $cont; $i++) for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) $inteiro[$i] = "0" . $inteiro[$i];

        $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < $cont; $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = $cont - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000"

            ) $z++; elseif ($z > 0) $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        $rt = str_replace($errado, $certo, $rt);
        $rt = str_replace(' ,', ',', $rt);

        if (!$maiusculas) {
            return ($rt ? $rt : "zero");
        } elseif ($maiusculas == "2") {
            return (strtoupper($rt) ? strtoupper($rt) : "Zero");
        } else {
            return (ucwords($rt) ? ucwords($rt) : "Zero");
        }
    }

    public static function str2Upper($value)
    {

        $original = "������������������������������";
        $replacer = "������������������������������";

        $value = strtr(strtoupper($value), $original, $replacer);

        return $value;
    }

    /**
     * @param mixed $options
     * @param $chaveSelecionada
     */
    public static function _select_option($options, $chaveSelecionada = NULL)
    {

        $html_options = '';
        if (is_array($options)) foreach ($options as $valor => $label) {
            $html_options .= '<option value="' . $valor . '"';
            if ($valor == $chaveSelecionada && $chaveSelecionada) $html_options .= ' selected';
            $html_options .= '>' . $label . '</option>';
        }
        return $html_options;
    }

    public static function randomPassword($limite = 5)
    {
        $carac = '0123456789qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM';
        $password = '';
        for ($i = 0; $i < $limite; $i++) {
            $password .= $carac[rand(0, strlen($carac) - 1)];
        }
        return $password;
    }

    public static function apenasNumeros($string)
    {

        return preg_replace("/[^0-9]/", "", $string);
    }

    public function removerMensagens()
    {
        $this->mensagem = array();
    }

    /**
     * Função responsável por criar a chamada do arquivo javascript de cada ação automaticamente
     *
     * @return string = chamada do script com o mesmo nome da ação
     */
    public static function exibeJS()
    {


        $controller = isset($_GET['c']) ? $_GET['c'] : '';
        $controller = ($controller != '') ? '/' . $controller : '';

        $modulo = isset($_GET['m']) ? $_GET['m'] : '';
        $modulo = ($modulo != '') ? '/' . $modulo : '/dashboard';

        $action = isset($_GET['a']) ? $_GET['a'] : '';
        $action = ($action != '') ? '/' . $action : '';

        $caminho = 'public/js/modulo' . $modulo . $controller . $action . '.js';

        if (file_exists($caminho)) {
            echo '<script type="text/javascript" src="' . $caminho . '"></script>';
        } else {
            return '';
        }

    }

    /*
     * Cria o breadcrumb na telas que não forem do módulo de configuração
     */
    public static function criaBreadcrumb()
    {
        $acao = isset($_GET['a']) ? $_GET['a'] : null;
        $breadcrumb = '<ol class="breadcrumb">';

        if ($acao != 'index') {
            (isset($_GET['m']) && $_GET['m'] != '') ? $breadcrumb .= '<li>' . ucwords($_GET['m']) . '</li>' : '';
            (isset($_GET['c']) && $_GET['c'] != '') ? $breadcrumb .= '<li>' . ucwords($_GET['c']) . '</li>' : '';
            (isset($_GET['a']) && $_GET['a'] != '') ? $breadcrumb .= '<li>' . ucwords($_GET['a']) . '</li>' : '';
        }

        $breadcrumb .= '</ol>';

        return $breadcrumb;

    }

    /*
     * Verifica se há uma requisicao ajax e retorna verdadeiro ou falso
     */
    public static function verificaAjax()
    {

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

            return true;
        } else {
            return false;
        }
    }

    /*
    * Informações:
    * checkbox - id e nome exibido no deletar
    * acoes - nome da acao, icone da acao, id
    * status valor e descricao
    * coluna e valor (pode ser o mesmo, pra facilitar: index => valor )
    */
    public static function jsonForDataTables($infos)
    {
        if (count($infos['dados']) > 0) {

            foreach ($infos['dados'] as $col => $info) {

                $id = "";
                if (is_array($infos['id'])) {
                    foreach ($infos['id'] as $coluna) {
                        if ($coluna != reset($infos['id'])) {
                            $id .= "_";
                        }

                        $id .= $info[$coluna];
                    }
                } else {
                    $id = $info[$infos['id']];
                }

                //Coluna Acões
                if (isset($infos['acoes'])) {

                    $acoes = '<div align="center">';

                    foreach ($info['acoes'] as $acao) {
                        switch ($acao['tipo']) {
                            case "modal":

                                break;
                            case "link":
                                $acoes .= '<a href="' . $acao['destino'] . '" data-toggle="tooltip" title="' . $acao['titulo'] . '" class="btn btn-default btn-xs" style="margin - right:5px"><i class="' . $acao['icone'] . '"></i> </a>';
                                break;
                        }
                    }

                    $acoes .= '</div>';

                    $toArray['acoes'] = $acoes;

                }

                //Coluna de checkboxes
                if (isset($infos['checkbox'])) {

                    $nmModal = isset($info['checkbox']) ? $info['checkbox']['nmModal'] : '';

                    $checkbox = '<div class="checkbox control-label" align="center">';
                    $checkbox .= '<input type="checkbox" class="" value="' . $id . '" id="' . $id . '" nmModal="' . $nmModal . '" name="linhas[]">';
                    $checkbox .= '</div>';

                    $toArray['checkbox'] = $checkbox;
                }

                if (isset($infos['status'])) {
                    switch ($info['fl_status']) {
                        case '1':
                            $status = '<span class="label label-success">Ativo</span>';
                            break;
                        case '0':
                            $status = '<span class="label label-danger">Inativo</span>';
                            break;
                    }
                }

                if (isset($infos['inputs'])) {

                    foreach ($infos['inputs'] as $name => $input) {
                        $input_name = "";

                        if (is_array($input['name'])) {
                            foreach ($input['name'] as $name) {
                                if ($name != reset($input['name'])) {
                                    $input_name .= "_";
                                }
                                if ($name == reset($input['name'])) {
                                    $input_name .= $name;
                                } else {
                                    $input_name .= $info[$name];
                                }

                            }
                        } else {
                            $input_name = $input['name'];
                        }

                        $value = isset($input['value']) ? $input['value'] : false;

                        $infoValue = isset($info[$value]) ? $info[$value] : false;

                        $toArray[reset($input['name'])] = Helper::getInputHTML($input['type'], $input_name, $infoValue);
                    }
                }

                if (isset($infos['colunas'])) {

                    foreach ($infos['colunas'] as $coluna) {
                        $toArray[$coluna] = $info[$coluna];
                    }

                } else {
                    return json_encode(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array()));

                }

                $toJson[] = $toArray;

            }

            return json_encode(array('draw' => 1, 'recordsTotal' => count($toJson), 'recordsFiltered' => count($toJson), 'data' => $toJson));

        } else {

            return json_encode(array('draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => array()));

        }
    }

    /*
     *
    */
    public static function getInputHTML($type, $name, $value = NULL, $placeholder = NULL, $mask = NULL, $validator = NULL)
    {
        switch ($type) {
            case "real":
                $input = '<div class="input-group">';
                $input .= '<span class="input-group-addon">R$</span>';
                $input .= '<input type="text" class="form-control maskDinheiro" data-mask="9999999999" id="' . $name . '" name="' . $name . '"
                   value="' . Helper::getMoney($value) . '" placeholder="' . $placeholder . '" style="max-width:200px; margin:0;">';
                $input .= '</div>';
                break;
            case "number":
                $input = '<input type="number" min="0" class="form-control" id="' . $name . '" name="' . $name . '"
                                   value="' . $value . '" placeholder="' . $placeholder . '" style="max-width:100px; margin:0;">';
                break;
            case "date":
                $input = '<input type="date" class="form-control" id="' . $name . '" name="' . $name . '"
                                   value="' . $value . '" placeholder="' . $placeholder . '" style="max-width:200px; margin:0;">';
                break;
            default:
                $input = '<input type="' . $type . '" class="form-control" id="' . $name . '" name="' . $name . '"
                                   value="' . $value . '" placeholder="' . $placeholder . '" style="max-width:100px; margin:0;">';
                break;

        }

        return $input;
    }

    /**
     * Função que gera as opções de um select automaticamente.
     *
     * @param $lista - lista vinda do banco com todos dados pra ser exibido no select, caso esteja
     * vazia a função retorna um option com aviso que não há registros.
     * @param $valueOption - nome do campo(no DB) é utilizado pra exibir o valor do campo
     * @param $descricaoOption - pode ser retornado em String ou Array
     *
     * String: É passado o nome do campo que está no DB para ser exibido no option.
     * Ex: geraOptionsSelect($listaPessoa, 'cd_pessoa', 'nm_pessoa');
     *
     * Array: Dois parametros passados na função, onde o primeiro é o texto com os campos entre
     * cochetes e outro com um array dos nomes de campos.
     * Ex: geraOptionsSelect($listaPessoa,'cd_pessoa',array('Pessoa: [nm_pessoa] - CPF: [nr_cpf]', array('nm_pessoa','nr_cpf'))
     * );
     */
    public static function geraOptionsSelect($lista, $valueOption = false, $descricaoOption = false, $valueSelected = null)
    {
        if ($lista) {
            //Se for apenas uma lista de valores e não varios arrays
            if (!$valueOption && !$descricaoOption) {
                foreach ($lista as $a => $list) {
                    $selected = ($valueSelected) ? $valueSelected : '';
                    echo '<option value="' . $list . '" ' . $selected . '>' . $list . '</option>';
                }
            } else {
                if (is_array($descricaoOption)) {
                    $textoDescricao = $descricaoOption[0];
                    $variaveisDescricao = $descricaoOption[1];
                    $divisor = array('[', ']');
                }
                echo '<option value=""></option>';
                foreach ($lista as $a => $list) {
                    $selected = '';
                    if (is_array($descricaoOption)) {
                        $textoModificado = $textoDescricao;
                        foreach ($variaveisDescricao as $posicaoVariavel => $nomeCampo) {
                            $textoModificado = str_replace($divisor[0] . $nomeCampo . $divisor[1], $list[$nomeCampo], $textoModificado);
                        }
                    } else
                        $textoModificado = $list[$descricaoOption];

                    if ($valueSelected && $valueSelected == $list[$valueOption])
                        $selected = 'selected';

                    echo '<option value="' . $list[$valueOption] . '" ' . $selected . '>' . $textoModificado . '</option>';
                }
            }
        } else {
            echo '<option value="">Não há registros cadastrados.</option>';
        }

    }

    /**
     * Método responsável por filtrar os valores de acordo com o tipo passado
     *
     * @param $value - valor passadopelo post/get
     * @param array $filtros - tipo de filtros
     * @return null|string
     */
    public function filters($value, $filtros = array())
    {
        $value = strip_tags($value);

        if ($value != '') {

            $var = $value;

            if ($filtros) {

                if (!is_array($filtros)) {
                    $filtros = array($filtros);
                }

                foreach ($filtros as $f) {
                    switch ($f) {
                        case 'date':
                            //Tranforma a data no formato americano yyyy-mm-dd
                            $var = $this->getInputDate($value);
                            break;
                        case 'onlyNumber':
                            //retira da string tudo que não for número
                            $var = $this->apenasNumeros($value);
                            break;
                        case 'money':
                            //transforma valor em float
                            $var = $this->getInputMoney($value);
                            break;
                        case 'moneyExcel':
                            //transforma valor em float
                            $var = $this->getExcelMoney($value);
                            break;
                    }
                }
            }

            return $var;

        } else {
            return null;
        }
    }

    /**
     * Realiza o upload do arquivo
     *
     * @param $from
     * @param $to
     * @return bool
     */
    public function fileUpload($from, $to)
    {

        return move_uploaded_file($from, $to);

    }

    /**
     * Realiza a exclusão do arquivo
     *
     * @param $dir
     * @return bool
     */
    public function fileDelete($dir)
    {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir) || is_link($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!self::fileDelete($dir . DIRECTORY_SEPARATOR . $item)) {
                chmod($dir . DIRECTORY_SEPARATOR . $item, 0777);
                if (!self::fileDelete($dir . DIRECTORY_SEPARATOR . $item)) return false;
            };
        }
        return rmdir($dir);
    }

    /**
     * Função responsável por exibir o nome do estado civil de acordo com o código vindo
     *
     * @param $codEstCivil
     * @return string
     */
    public static function getEstCivil($codEstCivil)
    {
        switch ($codEstCivil) {
            case 'SOL':
                $nomeEstCivil = "Solteiro(a)";
                break;
            case 'CAS':
                $nomeEstCivil = "Casado(a)";
                break;
            case 'DIV':
                $nomeEstCivil = "Divorciado(a)";
                break;
            case 'VIU':
                $nomeEstCivil = "Viúvo(a)";
                break;
            case 'SEP':
                $nomeEstCivil = "Separado(a)";
                break;
            case 'UNE':
                $nomeEstCivil = "União Estável";
                break;
        }
        return $nomeEstCivil;
    }
}
