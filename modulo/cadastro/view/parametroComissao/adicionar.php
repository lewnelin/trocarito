<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaEmpreendimentos = $this->get('listaEmpreendimentos');

?>
<fieldset>
    <form id="adicionarParametroComissao" enctype="multipart/form-data" class="form" role="form" method="POST"
          action="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=<?php echo $_GET['a']; ?>">
        <fieldset>
                    
            <legend>Parâmetro de Comissão <?= '<span id="tipoParametro" style="color:red"></span>' ?></legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                <label for="Empreendimento" class="control-label cl-xs-3">Empreendimento:<span
                                        class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <select name="Empreendimento" id="Empreendimento" class="form-control"
                                        style="width: 100%"
                                        title="Escolha o Empreendimento" emptyText="Escolha o Empreendimento"
                                        useEmpty="true">
                                    <?php
                                    Helper::geraOptionsSelect($listaEmpreendimentos, 'id', 'nm_empreendimento');
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                <label for="nmParametro" class="control-label cl-xs-3">Nome Parâmetro:
                                    <span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <div class="input-group col-sm-12">
                                    <input type="text" class="form-control" id="nmParametro"
                                           name="nmParametro" placeholder="Digite o Nome do Parâmetro de Comissão"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                <label for="tpComissao" class="control-label cl-xs-3">Tipo de comissão:<span
                                        class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <select name="tpComissao" id="tpComissao" class="form-control" style="width: 100%"
                                        title="Escolha o tipo de comissão" emptyText="Escolha o tipo de comissão"
                                        useEmpty="true">
                                    <option></option>
                                    <option value="F">Fixo</option>
                                    <option value="P">Percentual</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                <label for="tpInsidencia" class="control-label cl-xs-3">Tipo de Insidência:<span
                                        class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <select name="tpInsidencia" id="tpInsidencia" class="form-control" style="width: 100%"
                                        title="Escolha o tipo de comissão" emptyText="Escolha o tipo de comissão"
                                        useEmpty="true">
                                    <option></option>
                                    <option value="S">Sinal</option>
                                    <option value="P">Parcela</option>
                                    <option value="TI">Total do Imóvel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <legend>Valores de Comissão</legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="errorbox has-error" style="padding-left: 18px; padding-bottom: 5px">
                                <span id="ErrorMessage" class="help-block has-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                <label for="qtParcelas" class="control-label cl-xs-3">Parcelas da
                                    Comissão:<span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <div class="input-group col-sm-12">
                                    <input type="text" class="form-control" id="qtParcelas"
                                           name="qtParcelas" placeholder="Digite a Quantidade de Parcelas"
                                           value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group col-sm-2">
                                <label for="vlCorretor" class="control-label">Corretor: </label>

                                <div class="input-group">
                                    <span class="input-group-addon vary">R$</span>
                                    <input type="text" class="form-control maskDinheiro valores" id="vlCorretor"
                                           name="vlCorretor" placeholder="Digite o valor">
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="vlCoordenador" class="control-label">Coordenador: </label>

                                <div class="input-group">
                                    <span class="input-group-addon vary">R$</span>
                                    <input type="text" class="form-control maskDinheiro valores" id="vlCoordenador"
                                           name="vlCoordenador" placeholder="Digite o valor">
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="vlIndicador" class="control-label">Captador: </label>

                                <div class="input-group">
                                    <span class="input-group-addon vary">R$</span>
                                    <input type="text" class="form-control maskDinheiro valores" id="vlIndicador"
                                           name="vlIndicador" placeholder="Digite o valor">
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="vlImobiliaria" class="control-label">Imobiliária: </label>

                                <div class="input-group">
                                    <span class="input-group-addon vary">R$</span>
                                    <input type="text" class="form-control maskDinheiro valores" id="vlImobiliaria"
                                           name="vlImobiliaria" placeholder="Digite o valor">
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="vlOutros" class="control-label">Outros: </label>

                                <div class="input-group">
                                    <span class="input-group-addon vary">R$</span>
                                    <input type="text" class="form-control maskDinheiro valores" id="vlOutros"
                                           name="vlOutros" placeholder="Digite o valor">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend style="color:red"><span id="textoTotalComissao">Valor Total Comissões: R$ </span><span id="vlTotalComissao">0,00</span><span id="textoTotalComissao2"></span></legend>
        </fieldset>

        <div class="form-group">
            <legend><strong>Operações</strong></legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <input type="submit" id="importar" class="btn btn-primary col-sm-12" value="Salvar"/>
                    </div>
                    <div class="col-sm-2">
                        <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar"
                           class="btn btn-danger col-sm-12">
                            Cancelar </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
