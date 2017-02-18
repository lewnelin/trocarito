<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

?>
    <fieldset>
        <legend>Relatório Comissão</legend>

        <form role="form" action="" method="post" name="registerForm" id="registerForm" target="_blank">

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="comissao_emp" class="control-label">Empreendimento: </label>
                        <select id="comissao_emp" name="emp" class="form-control" title="Escolha o empreendimento"
                                emptyText="Escolha o empreendimento" useEmpty="true" required>
                            <?php Helper::geraOptionsSelect($this->get("listaEmpreendimentos"), 'id', 'nm_empreendimento'); ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="corretor_emp" class="control-label">Corretor: </label>
                        <select id="corretor_emp" name="corretor" class="form-control" title="Escolha o corretor"
                                emptyText="Escolha o corretor" useEmpty="true" disabled required></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                        <label>Tipos de Parcela</label>
                        <br>

                        <div class="form-group col-sm-2">
                            <input type="checkbox" class="form-control tp_parcelas" id="tp_parcelaN"
                                   name="tp_parcela[]" value="N" checked>
                            <label for="tp_parcelaN" class="control-label">Normal</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input type="checkbox" class="form-control tp_parcelas" id="tp_parcelaS"
                                   name="tp_parcela[]" value="S" checked>
                            <label for="tp_parcelaS" class="control-label">Sinal</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input type="checkbox" class="form-control tp_parcelas" id="tp_parcelaI"
                                   name="tp_parcela[]" value="I" checked>
                            <label for="tp_parcelaI" class="control-label">Intercalada</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input type="checkbox" class="form-control tp_parcelas" id="tp_parcelaC"
                                   name="tp_parcela[]" value="C" checked>
                            <label for="tp_parcelaC" class="control-label">Chave</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input type="checkbox" class="form-control tp_parcelas" id="tp_parcelaQ"
                                   name="tp_parcela[]" value="Q" checked>
                            <label for="tp_parcelaQ" class="control-label">Quitação</label>
                        </div>
                        <div class="form-group col-sm-2">
                            <input type="checkbox" class="form-control tp_parcelas" id="tp_parcelaG"
                                   name="tp_parcela[]" value="G" checked>
                            <label for="tp_parcelaG" class="control-label">Negociada</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12" id="divDatas">
                    <div class="form-group col-sm-3">
                        <label for="dataDe" class="control-label">De: </label>
                        <input type="text" class="form-control dtpicker maskData" id="dataDe" name="dataDe"
                               placeholder="Digite a data inicial" required>
                        <small class="help-block" style="display:none" id="msgDatas">Não houve vendas neste periodo de
                            datas.
                        </small>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="dataAte" class="control-label">Até: </label>
                        <input type="text" class="form-control dtpicker maskData" id="dataAte" name="dataAte"
                               placeholder="Digite a data final" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-default" id="gerarRelatorio"> Gerar relatório</button>
                    </div>
                </div>
            </div>
        </form>
    </fieldset>
<?php require_once 'layout/includes/footer.php'; ?>