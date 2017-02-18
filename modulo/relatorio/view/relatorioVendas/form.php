<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

?>
    <form role="form" action="" method="post" name="registerForm" id="registerForm" target="_blank">
        <fieldset>
            <legend>Relatório Vendas</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="comissao_emp" class="control-label">Empreendimento: <span
                                class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select id="comissao_emp" name="emp" class="form-control" title="Escolha o empreendimento"
                                emptyText="Escolha o empreendimento" useEmpty="true" required>
                            <?php Helper::geraOptionsSelect($this->get("listaEmpreendimentos"), 'id', 'nm_empreendimento'); ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="corretor_emp" class="control-label">Corretor: <span
                                class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select id="corretor_emp" name="corretor" class="form-control" title="Escolha o corretor"
                                emptyText="Escolha o corretor" useEmpty="true" disabled required></select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12" id="divDatas">
                    <div class="form-group col-sm-3">
                        <label for="dataDe" class="control-label">De: <span
                                class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control dtpicker maskData" id="dataDe" name="dataDe"
                               placeholder="Digite a data inicial" required>
                        <small class="help-block" style="display:none" id="msgDatas">Não houve vendas neste periodo de
                            datas.
                        </small>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="dataAte" class="control-label">Até: <span
                                class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control dtpicker maskData" id="dataAte" name="dataAte"
                               placeholder="Digite a data final" required>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Operação</legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <button type="submit" class="btn btn-default" id="gerarRelatorio"> Gerar relatório</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
<?php require_once 'layout/includes/footer.php'; ?>