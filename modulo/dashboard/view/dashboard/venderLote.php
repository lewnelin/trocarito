<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$lote = $this->get("infoLote");
$tabelaPreco = $this->get("tabelaPreco");

//Calculo de numero de parcelas
if (isset($lote['vl_parcela']) && $lote['vl_parcela'] > 0) {
    if (isset($lote['vl_total'])) {
        $vlTotal = (float)$lote['vl_total'];
        if (isset($lote['vl_sinal'])) {
            $vlTotal = $vlTotal - (float)$lote['vl_sinal'];
        }
        if (isset($lote['vl_intercalada']) && isset($lote['qt_intercalada'])) {
            $vlTotal = $vlTotal - ((float)$lote['vl_intercalada'] * (float)$lote['qt_intercalada']);
        }
        $qtParcelas = intval($vlTotal / (float)$lote['vl_parcela']);
    }
}

?>
    <style id="style">
        /*Esta no formulario adicionado pelo javascript*/
        .enderecoConjuge {
            display: none;
        }

        /*Esta no formulario adicionado pelo javascript*/
        .infoConjuge {
            display: none;
        }
    </style>

    <div class="modal fade mAdicionaPessoa" tabindex="-1" role="dialog" aria-labelledby="mAdicionaPessoaModalLabel"
         aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            id="closeModal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" id="buttonPessoaDiv">
                            <button class="btn btn-blue-3 btn-lg col-sm-5 buttonPessoa" type="button" value="F">
                                Cadastrar Pessoa Física
                            </button>
                            <span class="col-sm-2"></span>
                            <button class="btn btn-blue-3 btn-lg col-sm-5 buttonPessoa" type="button" value="J">
                                Cadastrar Pessoa Jurídica
                            </button>
                        </div>
                    </div>
                    <div id="cadastroPessoa"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-heading">
        <h1><i class='icon-basket-circled'></i> Vender <strong>Lote</strong></h1>
    </div>

    <div class="widget">

    <div class="widget-content padding">

    <div class="col-sm-12">

    <!--INFORMAÇÕES SOBRE RESERVA-->
    <?php if (isset($_GET['reserva'])) { ?>

        <div class="alert alert-info alert-dismissable" align="center">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            O cliente ainda <b>não foi cadastrado na nossa base de dados</b>, por favor, <b>cadastre-o antes
                de prosseguir com a venda</b>.
        </div>

        <input type="hidden" name="nmPessoaReserva" value="<?= $lote['nome_pessoa'] ?>"/>
        <input type="hidden" name="telefonePessoaReserva" value="<?= $lote['telefone_pessoa'] ?>"/>
        <input type="hidden" name="cpfPessoaReserva" value="<?= $lote['cpf_pessoa'] ?>"/>
        <input type="hidden" name="obsReserva" value="<?= $lote['observacao'] ?>"/>

    <?php } ?>
    <input type="hidden" name="qtdSinalTabelaPreco"
           value="<?= isset($lote['qt_sinal']) ? $lote['qt_sinal'] : '' ?>"/>

    <?php if (count($this->_helper->getMensagens()) > 0) { ?>
        <div class="alert alert-danger alert-dismissable">
            <div align="justify" align="center">
                <?php
                echo 'Erro no(s) campo(s):<br />';
                foreach ($this->_helper->getMensagens() as $campo => $msg) {
                    echo '<b>' . $campo . '</b>: ' . $msg . '<br />';
                }
                ?>
            </div>
        </div>
    <?php } else if (isset($_GET['exception'])) { ?>
        <div class="alert alert-danger alert-dismissable">
            <div align="justify" align="center">
                <?php echo $_GET['exception']; ?>
            </div>
        </div>
    <?php } ?>

    <form role="form" action="" method="post" name="registerForm" id="registerForm">

    <fieldset>
        <legend>Informações da Venda <span style="font-size: 10px;">(Campos com <span
                    class="text-danger" title="Este campo é obrigatório."
                    style="font-size: 13 px;">*</span> são obrigatórios)</span><span style="float:right;color:red;">Quadra: <b><?= $lote['quadra'] ?></b> Lote: <b><?= $lote['lote'] ?></b></span>
        </legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-6">
                    <label for="idCliente" class="control-label">Empreendimento: </label>
                    <input type="text" class="form-control" value="<?= $lote['nm_empreendimento'] ?> "
                           disabled>
                    <br>
                </div>
                <div class="col-sm-6">
                    <label for="idIndicacao" class="control-label">Indicado Por: </label>
                    <select class="form-control" name="idIndicacao" id="idIndicacao">
                        <?php
                        $indicacoes = $this->get("indicacoes");
                        Helper::geraOptionsSelect($indicacoes, 'id_indicacao', array('[id_indicacao] - [indicadoPor] ([nm_indicado])', array('id_indicacao', 'indicadoPor', 'nm_indicado')));
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-5">
                    <label for="idCliente" class="control-label">Comprador Responsável Pagamento: <span
                            class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <select class="form-control select2BigData"
                            data-placeholder="Digite o nome do cliente" name="idCliente" id="idCliente"
                            required="required"></select>
                </div>
                <div class="form-group col-sm-1">
                    <span class="input-group-btn btnAddPF" data-toggle="modal" data-target=".mAdicionaPessoa"
                          style="padding-top: 24px;">
                        <button tabindex="-1" style="height: 27px" class="btn btn-primary btn-sm" type="button"
                                data-toggle="tooltip" data-original-title="Adicionar Pessoa Física/Jurídica">
                            <i class="icon-user-add"></i>
                        </button>
                    </span>
                </div>
                <div class="form-group col-sm-6">
                    <label for="outrosCompradores" class="control-label">Outros Compradores:</label>
                    <select class="form-control select2Multiplo" name="outrosCompradores[]"
                            id="outrosCompradores" multiple="multiple"></select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group select2 col-sm-6">
                    <label for="cdBanco" class="control-label">Banco: <span class="text-danger"
                                                                            title="Este campo é obrigatório.">*</span></label>
                    <select class="form-control" name="cdBanco" id="cdBanco" required="required">
                        <?php
                        $contas = $this->get("listaContas");
                        $selected = (count($contas) == 1) ? $contas[0]['id'] : null;
                        Helper::geraOptionsSelect($contas, 'id', array('Banco: [descricao] - Ag.: [agencia]-[agencia_dv] - Cc:[conta_corrente]-[conta_corrente_dv]', array('descricao', 'agencia', 'agencia_dv', 'conta_corrente', 'conta_corrente_dv')), $selected);
                        ?>
                    </select>
                </div>
                <div class="form-group select2 col-sm-6">
                    <label for="idCorretor" class="control-label">Corretor: <span class="text-danger"
                                                                                  title="Este campo é obrigatório.">*</span></label>
                    <select class="form-control" name="idCorretor" id="idCorretor" required="required">
                        <?php Helper::geraOptionsSelect($this->get("listaCorretorEmpreendimento"), 'id_corretor', 'nm_pessoa', $lote['corretor']); ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="nrProposta" class="control-label">Número de Proposta:</label>
                    <input type="text" class="form-control" id="nrProposta" name="nrProposta"
                           placeholder="Digite o número da proposta.">
                </div>
                <div class="form-group col-sm-6">
                    <label for="vlDesconto" class="control-label">Valor do Desconto: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro" id="vlDesconto"
                               name="vlDesconto" placeholder="Digite o valor do desconto.">
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset id="parcelasNormais">
        <legend>Parcelas Normais</legend>
        <input type="hidden" id="nrNormalAdicional" value="0">

        <div class="row" id="rowNormalPadrao">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="vlParcelaNormal" class="control-label">Valor da Parc. Normal: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro calculaValores" id="vlParcelaNormal"
                               name="vlParcelaNormal" placeholder="Digite o valor da parcela."
                               value="<?= ($lote['vl_parcela'] > 0) ? Helper::getMoney($lote['vl_parcela']) : '' ?>">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="qtdParcelaNormal" class="control-label">Quant. de Parc.
                        Normais: </label>
                    <input type="text" class="form-control calculaValores" id="qtdParcelaNormal"
                           name="qtdParcelaNormal" placeholder="Digite a quantidade de parcelas."
                           value="<?= isset($qtParcelas) ? $qtParcelas : '' ?>">
                </div>
                <div class="form-group col-sm-2">
                    <label for="dtParcelaNormal" class="control-label">Data 1ª Parc. Normal: </label>
                    <input type="text" class="form-control dtpicker" id="dtParcelaNormal"
                           name="dtParcelaNormal" placeholder="Digite data da parcela.">
                    <small id="dtParcelaNormalMsg" style="display:none;" class="help-block">A data da 1ª
                        Parc. Normal tem que ser maior ou igual a data de contrato.
                    </small>
                </div>
                <div class="form-group col-sm-2">
                    <br>
                    <input type="checkbox" class="form-control" id="fl_reajustavel_mensais"
                           name="fl_reajustavel_mensais" checked>
                    <label for="fl_reajustavel_mensais" class="control-label">Reajustável</label>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"></label>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="adicionarNormal" data-toggle="tooltip"
                                title="ADICIONAR">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true">
                        </button>
                        <button class="btn btn-danger" type="button" id="removerNormal" data-toggle="tooltip"
                                title="REMOVER">
                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true">
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="row hide" id="rowNormal">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="vlNormalAdicional" class="control-label">Valor da Normal Adicional: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro" id="vlNormalAdicional_0"
                               name="vlNormalAdicional[]" placeholder="Digite o valor da parcela.">
                    </div>
                    <small data-bv-validator-for="vlNormalAdicional_0" class="help-block" style="display: none;">
                    </small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="qtNormalAdicional" class="control-label">Qtd de Normais Adicionais: </label>
                    <input type="text" class="form-control" id="qtNormalAdicional_0"
                           name="qtNormalAdicional[]" placeholder="Digite a quantidade de parcelas.">
                    <small data-bv-validator-for="qtNormalAdicional_0" class="help-block" style="display: none;">b
                    </small>
                </div>
                <div class="form-group col-sm-2">
                    <br>
                    <input type="checkbox" class="form-control" id="reajustavelNormalAdicional_0"
                           name="reajustavelNormalAdicional[]">
                    <label for="reajustavelNormalAdicional_0" class="control-label">Reajustável</label>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Parcelas Sinais</legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="vlSinal" class="control-label">Valor total do Sinal: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro calculaValores" id="vlSinal" name="vlSinal"
                               placeholder="Digite o valor da parc. sinal."
                               value="<?= ($lote['vl_sinal'] > 0) ? Helper::getMoney($lote['vl_sinal']) : '' ?>">
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label for="qtdSinal" class="control-label">Quantidade das Parc. Sinais: </label>
                    <select class="form-control calculaValores" name="qtdSinal" id="qtdSinal"
                            value="<?= ($lote['qt_sinal'] > 0) ? $lote['qt_sinal'] : '' ?>">
                        <option value=""></option>
                        <?php for ($i = 0; $i <= 100; $i++):
                            $selected = ($lote['qt_sinal'] == $i) ? 'selected' : '';
                            ?>
                            <option value="<?= $i; ?>" <?= $selected ?>><?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="flIncluirContratoSinal" class="control-label">Incluir no contrato a
                        Parc. Sinal?</label><br>
                    <label for="flIncluirContratoSinalSim" class="control-label"
                           style="padding-top: 8px;">
                        Sim: <input type="radio" id="flIncluirContratoSinalSim"
                                    name="flIncluirContratoSinal"
                                    value="1" <?= ($lote['vl_sinal'] > 0) ? 'checked' : '' ?>/>
                    </label>
                    <label for="flIncluirContratoSinalNao" class="control-label">
                        Não: <input type="radio" id="flIncluirContratoSinalNao"
                                    name="flIncluirContratoSinal"
                                    value="0" <?= ($lote['vl_sinal'] == 0) ? 'checked' : '' ?>/>
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-12" id="TabelaParcSinalDiv">
                    <div class="table-responsive">
                        <small id="TabelaParcSinalMsg" style="display:none;" class="help-block">A soma
                            dos valores de todas parcelas difere do valor total das parcelas sinais.
                        </small>
                        <small id="TabelaParcSinalMsg2" style="display:none;" class="help-block">As
                            Datas de todas parcelas devem ser preenchidas.
                        </small>
                        <table data-sortable
                               class="table table-striped table-bordered table-hover table-condensed"
                               id="tabelaParcSinais" style="display: none;">
                            <thead>
                            <tr>
                                <th>Parcela</th>
                                <th data-sortable="false">Valor</th>
                                <th data-sortable="false">Vencimento</th>
                            </tr>
                            </thead>

                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Parcelas Intercaladas</legend>
        <input type="hidden" id="nrIntercaladaAdicional" value="0">

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="vlIntercalada" class="control-label">Valor da Parc.
                        Intercalada: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro calculaValores" id="vlIntercalada"
                               name="vlIntercalada" placeholder="Digite o valor da parc. intercalada."
                               value="<?= ($lote['vl_intercalada'] > 0) ? Helper::getMoney($lote['vl_intercalada']) : '' ?>">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="qtdIntercalada" class="control-label">Quant. de Intercaladas: </label>
                    <input type="text" class="form-control calculaValores" id="qtdIntercalada" name="qtdIntercalada"
                           placeholder="Digite a quantidade de parc. intercalada."
                           value="<?= ($lote['qt_intercalada'] > 0) ? $lote['qt_intercalada'] : '' ?>">
                </div>
                <div class="form-group col-sm-2">
                    <label for="nrFrequencia" class="control-label">Freq. das Intercaladas: </label>
                    <input type="text" class="form-control" id="nrFrequencia" name="nrFrequencia"
                           placeholder="Digite a frequẽncia.">
                </div>
                <div class="form-group col-sm-2">
                    <br>
                    <input type="checkbox" class="form-control" id="fl_reajustavel_intercaladas"
                           name="fl_reajustavel_intercaladas" checked>
                    <label for="fl_reajustavel_intercaladas" class="control-label">Reajustável</label>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"></label>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="adicionarIntercalada" data-toggle="tooltip"
                                title="ADICIONAR">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true">
                        </button>
                        <button class="btn btn-danger" type="button" id="removerIntercalada" data-toggle="tooltip"
                                title="REMOVER">
                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true">
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="dtIntercalada" class="control-label">Data da 1ª Parc.
                        Intercalada: </label>
                    <input type="text" class="form-control dtpicker" id="dtIntercalada"
                           name="dtIntercalada" placeholder="Digite a data da 1ª parc. intercalada.">
                    <small id="dtIntercaladaMsg" style="display:none;" class="help-block"></small>
                </div>
                <div class="form-group col-sm-4">
                    <label for="flCoincidirIntercaladas" class="control-label">Coincidir intercaladas
                        com parc. mensais?</label><br>
                    <label for="flCoincidirIntercaladasSim" class="control-label"
                           style="padding-top: 8px;">
                        Sim: <input type="radio" id="flCoincidirIntercaladasSim"
                                    name="flCoincidirIntercaladas" value="1" checked/>
                    </label>
                    <label for="flCoincidirIntercaladasNao" class="control-label">
                        Não: <input type="radio" id="flCoincidirIntercaladasNao"
                                    name="flCoincidirIntercaladas" value="0"/>
                    </label>
                </div>
            </div>
        </div>
        <div class="row hide" id="rowIntercalada">
            <div class="col-sm-12">
                <div class="form-group col-sm-3">
                    <label for="vlIntercaladaAdicional" class="control-label">Valor da Intercalada Adicional: </label>
                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro" id="vlIntercaladaAdicional_0"
                               name="vlIntercaladaAdicional[]" placeholder="Digite o valor da parcela.">
                    </div>
                    <small data-bv-validator-for="vlIntercaladaAdicional_0" class="help-block"
                           style="display: none;"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="qtIntercaladaAdicional" class="control-label">Quantidade da Intercalada: </label>
                    <input type="text" class="form-control" id="qtIntercaladaAdicional_0"
                           name="qtIntercaladaAdicional[]" placeholder="Digite a quantidade de parcelas.">
                    <small data-bv-validator-for="qtIntercaladaAdicional_0" class="help-block"
                           style="display: none;"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="nrIntercaladaAdicional" class="control-label">Frequência da Intercalada: </label>
                    <input type="text" class="form-control" id="nrIntercaladaAdicional_0"
                           name="nrIntercaladaAdicional[]"
                           placeholder="Digite a frequẽncia.">
                    <small data-bv-validator-for="nrIntercaladaAdicional_0" class="help-block"
                           style="display: none;"></small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="dtIntercaladaAdicional" class="control-label">Data Intercalada: </label>
                    <input type="text" class="form-control dtpicker" id="dtIntercaladaAdicional_0"
                           name="dtIntercaladaAdicional[]" placeholder="Digite a data da 1ª parc. intercalada.">
                    <small data-bv-validator-for="dtIntercaladaAdicional_0" class="help-block"
                           style="display: none;"></small>
                </div>
                <div class="form-group col-sm-2">
                    <br>
                    <input type="checkbox" class="form-control" id="reajustavelIntercaladaAdicional_0"
                           name="reajustavelIntercaladaAdicional[]">
                    <label for="reajustavelIntercaladaAdicional_0" class="control-label">Reajustável</label>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Parcelas Chaves</legend>
        <input type="hidden" id="nrChaveAdicional" value="0">

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="vlChave" class="control-label">Valor da Parc. Chave:: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro calculaValores" id="vlChave" name="vlChave"
                               placeholder="Digite o valor da chave.">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label for="qtdParcChave" class="control-label">Quant. Parc. Chaves: </label>
                    <input type="text" class="form-control calculaValores" id="qtdParcChave" name="qtdParcChave"
                           placeholder="Digite a quantidade de parc. da chave.">
                    <small id="qtdParcChaveMsg" style="display:none;" class="help-block">A quantidade de
                        parcela de chaves tem que ser menor ou igual a quantidade de parcela do valor
                        normal.
                    </small>
                </div>
                <div class="form-group col-sm-2">
                    <label for="dtParcChave" class="control-label">Data da 1ª Parc. Chave: </label>
                    <input type="text" class="form-control dtpicker" id="dtParcChave" name="dtParcChave"
                           placeholder="Digite a data da 1ª parc. chave.">
                </div>
                <div class="form-group col-sm-2">
                    <br>
                    <input type="checkbox" class="form-control" id="fl_reajustavel_chaves"
                           name="fl_reajustavel_chaves" checked>
                    <label for="fl_reajustavel_chaves" class="control-label">Reajustável</label>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"></label>
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="adicionarChave" data-toggle="tooltip"
                                title="ADICIONAR">
                            <span class="glyphicon glyphicon-plus-sign" aria-hidden="true">
                        </button>
                        <button class="btn btn-danger" type="button" id="removerChave" data-toggle="tooltip"
                                title="REMOVER">
                            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true">
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="row hide" id="rowChave">
            <div class="col-sm-12">
                <div class="form-group col-sm-4">
                    <label for="vlChaveAdicional" class="control-label">Valor da Parcela Chave Adicional: </label>

                    <div class="input-group">
                        <span class="input-group-addon">R$</span>
                        <input type="text" class="form-control maskDinheiro" id="vlChaveAdicional_0"
                               name="vlChaveAdicional[]" placeholder="Digite o valor da parcela.">
                    </div>
                    <small data-bv-validator-for="vlChaveAdicional_0" class="help-block" style="display: none;">a
                    </small>
                </div>
                <div class="form-group col-sm-3">
                    <label for="qtChaveAdicional" class="control-label">Qtd de Parcelas Chave Adicionais: </label>
                    <input type="text" class="form-control" id="qtChaveAdicional_0"
                           name="qtChaveAdicional[]" placeholder="Digite a quantidade de parcelas.">
                    <small data-bv-validator-for="qtChaveAdicional_0" class="help-block" style="display: none;">b
                    </small>
                </div>
                <div class="form-group col-sm-2">
                    <br>
                    <input type="checkbox" class="form-control" id="reajustavelChaveAdicional_0"
                           name="reajustavelChaveAdicional[]">
                    <label for="reajustavelChaveAdicional_0" class="control-label">Reajustável</label>
                </div>
            </div>
        </div>
    </fieldset>
    <br>
    <fieldset>
        <legend style="color:red">Valor Total do Lote: R$ <span id="vlTotalLote">0,00</span></legend>
    </fieldset>
    <br>
    <fieldset>
        <legend><b>Operações</b></legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary col-sm-12" id="btnSalvar">Salvar</button>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-danger col-sm-12" id="btnCancelar"
                            onclick="location.href = '<?php echo $this->_helper->getLink(array("m" => "dashboard", "c" => "dashboard", 'a' => 'painelVenda', "idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'], "statusAtual" => $_GET['statusAtual'], "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'])); ?>';">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </fieldset>
    </form>


    </div>

    </div>
    </div>

<?php require_once 'layout/includes/footer.php'; ?>