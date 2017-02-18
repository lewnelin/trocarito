<?php
$listaQuadras = $this->get("listaQuadras");
$id_tabela = $this->get("idTabela");
$nm_tabela = $this->get("nomeTabela");
$dsTabela = $this->get("descricaoTabela");
$empreendimento = $this->get("empreendimento");
$idEmpreendimento = $this->get("idEmpreendimento");
?>

<script>
    $("select").select2();
</script>
<div class="row">
    <div class="form-group col-sm-6">
        <input name="Empreendimento" id="Empreendimento" class="form-control select2"
               value="<?= $empreendimento ?>" disabled>
        <input type="hidden" id="idEmpreendimento" name="idEmpreendimento" value="<?= $idEmpreendimento ?>">
        <input type="hidden" id="idTabela" name="idTabela" value="<?= $id_tabela ?>">
    </div>
    <div class="form-group col-sm-6">
        <select name="Quadra" id="Quadra" class="form-control select2" style="width: 100%" title="Escolha a Quadra"
                data-placeholder="Escolha a Quadra">
            <option></option>
            <?php Helper::geraOptionsSelect($listaQuadras); ?>
        </select>
    </div>
</div>
<div id="table-title" class="row" style="display: none;"></div>
<div class="table-responsive col-sm-12">
    <table id="TabelaVisualizarTabelaPrecos"
           class="table table-striped table-bordered table-hover table-condensed order-column"
           cellspacing="0" style="width:100%; vertical-align: middle;">
        <thead class="tb-center">
        <tr>
            <th colspan="10"><?php echo $nm_tabela . ' - ' . $empreendimento; ?></th>
        </tr>
        <?php if($dsTabela != '') echo '<tr><td align="center" colspan="10">Descrição: ' . $dsTabela . '</td></tr>' ?>
        <tr>
            <th>Quadra</th>
            <th>Lote</th>
            <th>Valor Total</th>
            <th width="100px">Sinal</th>
            <th>Parcelas</th>
            <th>Intercaladas</th>
            <th>Qtde Intercaladas</th>
            <th>Status</th>
            <th width="150px">Atualização</th>
            <th>Ações</th>
        </tr>
        </thead>
    </table>
</div>