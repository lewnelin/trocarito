<?php
$cabecalho = $this->get('cabecalho');
$listaLotes = $this->get('listaLotes');
$clienteAcade = $this->get("clienteAcade");
?>
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.js"></script>

<style>
    th, td {
        margin-right: 0;
        margin-left: 0;
        padding-right: 0;
        padding-left: 0;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    #cabecalho td {
        padding: 5px;
    }

    .table-responsive {
        margin-top: 5px;
    }

    div, body, td, h1, h2, h3, h4, strong {
        background: #fff !important;
        font-family: arial !important;
        color: #000 !important;
    }

    .tdAvulso1 {
        background-color: #cccccc;
    }

    .tdAvulso2 {
        background-color: #FFFFFF;
    }

    .tdComum {
        background-color: #cccccc;
    }
</style>
<page backtop="10mm" backbottom="10mm">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <table id="cabecalho">
                    <tr>
                        <td>
                            <?php

                            $dsEndLogomarca = $clienteAcade['ds_end_logomarca'];

                            if ($dsEndLogomarca) {

                                $src = explode('/', $dsEndLogomarca);
                                $src = 'upload/logomarcaCliente/' . array_pop($src);

                                if (file_exists($src)) {
                                    echo '<img src="' . $src . '" alt="' . $clienteAcade['nm_fantasia'] . '" height="80">';
                                } else {
                                    echo '<img style="float: left; " src="' . LOGOMARCA_DEFAULT . '" alt="LogoPadrao" height="80">';
                                }
                            }

                            ?></td>
                        <td width="60%">
                            <h3><strong>RELATÓRIO DE LOTES DISPONÍVEIS</strong></h3>

                            <h4>Empreendimento: <?php echo $cabecalho['empreendimento']; ?></h4>
                            <h4>Quadra: <?php echo $cabecalho['quadra']; ?></h4>
                            <h4>Tabela de Preço: <?php echo $cabecalho['tabela']; ?></h4>

                        </td>
                    </tr>
                </table>

                <div class="widget col-md-8">
                    <div class="widget-content padding">
                        <div class="table-responsive">
                            <table class="parcelas_corretor table-bordered" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                    <th width="50px" style="background-color: #B8B8B8">Quadra</th>
                                    <th width="50px" style="background-color: #B8B8B8">Lote</th>
                                    <?php if ($_POST['reservados'] == '1') : ?>
                                        <th width="50px" style="background-color: #B8B8B8">Status</th>
                                    <?php endif; ?>
                                    <th width="50px" style="background-color: #B8B8B8">Área</th>
                                    <th width="100px" style="background-color: #B8B8B8">Valor Total</th>
                                    <th width="100px" style="background-color: #B8B8B8">Sinal</th>
                                    <th width="100px" style="background-color: #B8B8B8">Parcelas</th>
                                    <th width="100px" style="background-color: #B8B8B8">Intercaladas</th>
                                    <th width="90px" style="background-color: #B8B8B8">Qtde Interc.</th>
                                    <th width="150px" style="background-color: #B8B8B8">Data Atualização</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $i = 0;
                                $vlTotal = 0;
                                $qtdCorretor = 0;
                                $qtdDisponivel = 0;
                                $qtdTecnica = 0;
                                foreach ($listaLotes as $lote) :
                                    $classe = 'tdComum';
                                    if ($i == 0) {
                                        $i = 1;
                                        $classe = 'tdAvulso2';
                                    } else {
                                        $i = 0;
                                        $classe = 'tdAvulso1';
                                    }
                                    $lote['reserva'] == '1' ? $qtdCorretor++ : ($lote['reservado'] == '1' ? $qtdTecnica++ : $qtdDisponivel++);
                                    $vlTotal += ($lote['vl_total'] > 0) ? $lote['vl_total'] : $lote['valor'];
                                    ?>
                                    <tr>
                                        <td class="<?= $classe ?>" align='center'><?= $lote['quadra'] ?></td>
                                        <td class="<?= $classe ?>" align='center'><?= $lote['lote'] ?></td>
                                        <?php if ($_POST['reservados'] == '1') : ?>
                                            <td class="<?= $classe ?>"
                                                align='center'><?= isset($lote['reserva']) && $lote['reserva'] == '1' ? 'RC' : ($lote['reservado'] == '1' ? 'RT' : 'D') ?></td>
                                        <?php endif; ?>
                                        <td class="<?= $classe ?>" align='center'><?= $lote['area']; ?></td>
                                        <td class="<?= $classe ?>"
                                            align='right'><?= ($lote['vl_total'] > 0) ? Helper::getMoney($lote['vl_total']) : Helper::getMoney($lote['valor']); ?></td>
                                        <td class="<?= $classe ?>"
                                            align='right'><?= Helper::getMoney($lote['vl_sinal']) ?></td>
                                        <td class="<?= $classe ?>"
                                            align='right'><?= Helper::getMoney($lote['vl_parcela']) ?></td>
                                        <td class="<?= $classe ?>"
                                            align='right'><?= Helper::getMoney($lote['vl_intercalada']) ?></td>
                                        <td class="<?= $classe ?>" align='center'><?= $lote['qt_intercalada'] ?></td>
                                        <td class="<?= $classe ?>"
                                            align='center'><?= Helper::getDate($lote['dt_atualizacao']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div>
                    <?php if ($_POST['reservados'] == '1') : ?>
                        <table align="left" style="width: 300px; padding-right: 25px; padding-top: 20px;">
                            <tr>
                                <td><strong>LEGENDA</strong></td>
                                <td><strong>QTDE</strong></td>
                            </tr>
                            <tr>
                                <td>D - Disponível</td>
                                <td align="center"><?= $qtdDisponivel ?></td>
                            </tr>
                            <tr>
                                <td>RC - Reserva corretor</td>
                                <td align="center"><?= $qtdCorretor ?></td>
                            </tr>
                            <tr>
                                <td>RT - Reserva técnica</td>
                                <td align="center"><?= $qtdTecnica ?></td>
                            </tr>
                        </table>
                    <?php endif; ?>
                    <table align="right" style="width: 400px; padding-right: 25px; padding-top: 20px;">
                        <tr>
                            <td>&nbsp;</td>
                            <td align="right">Valor Total:</td>
                            <td align="right">
                                <strong>
                                    <?= Helper::getMoney($vlTotal); ?>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td align="right">Lotes:</td>
                            <td align="right">
                                <strong>
                                    <?= count($listaLotes); ?>
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
</page>