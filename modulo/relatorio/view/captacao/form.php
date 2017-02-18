<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

?>
    <form role="form" action="" method="post" name="registerForm" id="registerForm" target="_blank">
        <fieldset>
            <div id="warning" style="text-align: center" class="alert alert-danger col-sm-12" hidden></div>
        </fieldset>
        <fieldset>
            <legend>Relatório de Captações</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="captacaoEmpreendimento" class="control-label">Empreendimento:<span
                                class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select id="captacaoEmpreendimento" name="captacaoEmpreendimento" class="form-control"
                                title="Escolha o empreendimento"
                                emptyText="Escolha o empreendimento" useEmpty="true" required>
                            <?php Helper::geraOptionsSelect($this->get("listaEmpreendimentos"), 'id', 'nm_empreendimento'); ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="captador" class="control-label">Captador:<span class="text-danger"
                                                                                   title="Este campo é obrigatório.">*</span></label>
                        <select id="captador" name="captador" class="form-control" title="Escolha uma opção"
                                emptyText="Escolha uma opção" useEmpty="true" required disabled>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6" id="divDatas">
                    <div class="col-sm-12" style="padding-left: 0">
                        <label class="control-label" id="labelData" style="padding-left: 15px">Data da
                            Indicação: </label><br>

                        <div class="form-group col-sm-6">
                            <label for="dataDe" class="control-label">De: </label>
                            <input type="text" class="form-control dtpicker maskData" id="dataDe" name="dataDe"
                                   placeholder="Digite a data inicial">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="dataAte" class="control-label">Até: </label>
                            <input type="text" class="form-control dtpicker maskData" id="dataAte" name="dataAte"
                                   placeholder="Digite a data final">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="col-sm-12" style="padding-left: 0">
                        <label class="control-label" for="radioCaptacoes">Captações: </label><br>
                        <label for="radioCaptacoesT" class="control-label">Ativas:</label>
                        <input type="radio" id="radioCaptacoesT" value="1" name="radio" checked>
                        <label for="radioCaptacoesF" class="control-label">Finalizadas:</label>
                        <input type="radio" id="radioCaptacoesF" value="0" name="radio">
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