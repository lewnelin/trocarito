<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$empreendimento = $this->get('empreendimento');
$parametro = $this->get('parametro');
?>
    <fieldset>
        <form id="adicionarParametroComissao" enctype="multipart/form-data" class="form" role="form" method="POST"
              action="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=<?php echo $_GET['a']; ?>">
            <fieldset>
                        
                <legend>Parâmetro de Comissão</legend>
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
                                            useEmpty="true" disabled>
                                        <option value="<?= $empreendimento['id'] ?>"><?= $empreendimento['nm_empreendimento'] ?></option>
                                    </select>
                                    <input type="hidden" name="idParametro" value="<?= $parametro['id_parametro_comissao'] ?>">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="form-group col-sm-12">
                                    <label for="nmParametro" class="control-label cl-xs-3">Nome Parâmetro:
                                        <span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input type="text" class="form-control" id="nmParametro"
                                               name="nmParametro" placeholder="Digite o Nome do Parâmetro de Comissão"
                                               value="<?= $parametro['nm_parametro_comissao'] ?>">
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
                                        <option <?= ($parametro['tp_comissao'] == 'F') ? 'selected' : '' ?> value="F">Fixo</option>
                                        <option <?= ($parametro['tp_comissao'] == 'P') ? 'selected' : '' ?> value="P">Percentual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="form-group col-sm-12">
                                    <label for="tpInsidencia" class="control-label cl-xs-3">Tipo de Insidência:<span
                                            class="text-danger" title="Este campo é obrigatório">*</span></label>
                                    <select name="tpInsidencia" id="tpInsidencia" class="form-control"
                                            style="width: 100%"
                                            title="Escolha o tipo de comissão" emptyText="Escolha o tipo de comissão"
                                            useEmpty="true">
                                        <option></option>
                                        <option <?= ($parametro['tp_local_insidencia'] == 'S') ? 'selected' : '' ?> value="S">Sinal</option>
                                        <option <?= ($parametro['tp_local_insidencia'] == 'P') ? 'selected' : '' ?> value="P">Parcela</option>
                                        <option <?= ($parametro['tp_local_insidencia'] == 'TI') ? 'selected' : '' ?> value="TI">Total do Imóvel</option>
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
                                    <label for="tpInsidencia" class="control-label cl-xs-3">Parcelas da
                                        Comissão:<span class="text-danger"
                                                       title="Este campo é obrigatório">*</span></label>
                                    <div class="input-group col-sm-12">
                                        <input type="text" class="form-control" id="qtParcelas"
                                               name="qtParcelas" placeholder="Digite a Quantidade de Parcelas"
                                               value="<?= $parametro['qt_parcela_comissao'] ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group col-sm-2">
                                    <label for="vlCorretor" class="control-label">Corretor: </label>

                                    <div class="input-group">
                                        <span class="input-group-addon vary">R$</span>
                                        <input type="text" class="form-control maskDinheiro valores" id="vlCorretor"
                                               name="vlCorretor" value="<?= $parametro['vl_corretor'] ?>" placeholder="Digite o valor">
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="vlCoordenador" class="control-label">Coordenador: </label>

                                    <div class="input-group">
                                        <span class="input-group-addon vary">R$</span>
                                        <input type="text" class="form-control maskDinheiro valores" id="vlCoordenador"
                                               name="vlCoordenador" value="<?= $parametro['vl_coordenador'] ?>" placeholder="Digite o valor">
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="vlIndicador" class="control-label">Captador: </label>

                                    <div class="input-group">
                                        <span class="input-group-addon vary">R$</span>
                                        <input type="text" class="form-control maskDinheiro valores" id="vlIndicador"
                                               name="vlIndicador" value="<?= $parametro['vl_indicador'] ?>" placeholder="Digite o valor">
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="vlImobiliaria" class="control-label">Imobiliária: </label>

                                    <div class="input-group">
                                        <span class="input-group-addon vary">R$</span>
                                        <input type="text" class="form-control maskDinheiro valores" id="vlImobiliaria"
                                               name="vlImobiliaria" value="<?= $parametro['vl_imobiliaria'] ?>" placeholder="Digite o valor">
                                    </div>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="vlOutros" class="control-label">Outros: </label>

                                    <div class="input-group">
                                        <span class="input-group-addon vary">R$</span>
                                        <input type="text" class="form-control maskDinheiro valores" id="vlOutros"
                                               name="vlOutros" value="<?= $parametro['vl_outros'] ?>" placeholder="Digite o valor">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend style="color:red"><span id="textoTotalComissao"><?= ($parametro['tp_comissao'] == 'F') ? 'Valor Total Comissões: R$ ' : '' ?></span><span id="vlTotalComissao">0,00</span><span id="textoTotalComissao2"></span></legend>
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