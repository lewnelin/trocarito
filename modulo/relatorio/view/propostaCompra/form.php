<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

?>
    <form role="form" action="" method="post" name="registerForm" id="registerForm" target="_blank">
        <fieldset>
            <legend>Proposta Compra</legend>


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
                        <label for="lote" class="control-label">Lote:<span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select id="lote" name="lote" class="form-control" title="Escolha o lote"
                                emptyText="Escolha o lote" useEmpty="true" disabled required></select>
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