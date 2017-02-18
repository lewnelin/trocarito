<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

?>
    <form role="form" action="" method="post" name="registerForm" id="registerForm" target="_blank">
        <fieldset>
            <div id="warning" style="text-align: center" class="alert alert-danger col-sm-12" hidden></div>
        </fieldset>
        <fieldset>
            <legend>Lotes Disponíveis</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="proposta_emp" class="control-label">Empreendimento:<span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select id="proposta_emp" name="emp" class="form-control" title="Escolha o empreendimento"
                                emptyText="Escolha o empreendimento" useEmpty="true" required>
                            <?php Helper::geraOptionsSelect($this->get("listaEmpreendimentos"), 'id', 'nm_empreendimento'); ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="quadra" class="control-label">Quadra:<span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select id="quadra" name="quadra" class="form-control" title="Escolha a quadra"
                                emptyText="Escolha a quadra" useEmpty="true" disabled required></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="tabela" class="control-label">Tabela de Preços:</label>
                        <select id="tabela" name="tabela" class="form-control" title="Escolha a Tabela de Preços"
                                emptyText="Escolha a Tabela de Preços" useEmpty="true" disabled required></select>
                    </div>
                    <div class="form-group col-sm-6">
                        <div class="col-sm-12" style="padding-left: 0">
                            <label class="control-label" for="radioExibe">Exibe Reservados: </label><br>
                            <label for="radioExibeS" class="control-label">Sim</label>
                            <input type="radio" id="radioExibeS" value="1" name="reservados" checked>
                            <label for="radioExibeN" class="control-label">Não</label>
                            <input type="radio" id="radioExibeN" value="0" name="reservados">
                        </div>
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