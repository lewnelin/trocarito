<?php
$empreendimento = $this->get('empreendimento');
$parametro = $this->get('parametro');
?>
<fieldset>
    <form id='registerForm' class="form" role="form" method="POST"
          action="?m=<?= $_GET['m'] ?>&c=<?= $_GET['c']; ?>&a=<?= $_GET['a']; ?>">
        <fieldset>
            <legend>Parâmetro de Comissão</legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-12">
                        <label
                            class="control-label"><b>Empreendimento:</b></label> <?= $empreendimento['nm_empreendimento'] ?>
                    </div>
                    <div class="form-group col-sm-12">
                        <label
                            class="control-label"><b>Nome:</b></label> <?= $parametro['nm_parametro_comissao'] ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Tipo de Comissão:</b></label> <?= $parametro['comissao'] ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Insidência:</b></label> <?= $parametro['insidencia'] ?>
                    </div>
                </div>
            </div>

            <legend>Valores de Comissão</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Quantidade de Parcelas de
                                Comissão:</b></label> <?= $parametro['qt_parcela_comissao'] ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <!-- Corretor -->
                        <div>
                            <label style="font-weight: bold;">
                                Corretor:
                            </label>
                            <?= ($parametro['comissao'] == 'Fixo') ? 'R$' . Helper::getMoney($parametro['vl_corretor']) : $parametro['vl_corretor'] . '%' ?>
                        </div>
                        <!-- Coordenador -->
                        <div>
                            <label style="font-weight: bold;">
                                Coordenador:
                            </label>
                            <?= ($parametro['comissao'] == 'Fixo') ? 'R$' . Helper::getMoney($parametro['vl_coordenador']) : $parametro['vl_coordenador'] . '%' ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <!-- Indicador -->
                        <div>
                            <label style="font-weight: bold;">
                                Indicador:
                            </label>
                            <?= ($parametro['comissao'] == 'Fixo') ? 'R$' . Helper::getMoney($parametro['vl_indicador']) : $parametro['vl_indicador'] . '%' ?>
                        </div>
                        <!-- Imobiliária -->
                        <div>
                            <label style="font-weight: bold;">
                                Imobiliária:
                            </label>
                            <?= ($parametro['comissao'] == 'Fixo') ? 'R$' . Helper::getMoney($parametro['vl_imobiliaria']) : $parametro['vl_imobiliaria'] . '%' ?>
                        </div>
                        <!-- Outros -->
                        <div>
                            <label style="font-weight: bold;">
                                Outros:
                            </label>
                            <?= ($parametro['comissao'] == 'Fixo') ? 'R$' . Helper::getMoney($parametro['vl_outros']) : $parametro['vl_outros'] . '%' ?>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>

    </form>
</fieldset>
