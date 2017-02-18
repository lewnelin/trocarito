<?php
$listaCaptacoes = $this->get("listaIndicacoes");
$cabecalho = $this->get('cabecalho');

$nrTotal = 0;
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

    .tdCaptador {
        background-color: #acacac;
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
                        <td>
                            <h3><strong>RELATÓRIO DE CAPTAÇÕES</strong></h3>

                            <h4><strong>Empreendimento: </strong><?php echo $cabecalho['empreendimento']; ?></h4>
                            <h4>
                                <strong>Período: </strong><?php echo $cabecalho['dataDe'] . " à " . $cabecalho['dataAte']; ?>
                            </h4>
                            <h4><strong>Captador: </strong><?php echo $cabecalho['captador']; ?></h4>
                        </td>
                    </tr>
                </table>

                <div class="widget col-md-8">
                    <div class="widget-content padding">
                        <div class="table-responsive">
                            <table style="font-size: 9pt" class="parcelas_corretor table-bordered" cellspacing="0"
                                   cellpadding="0">
                                <thead>
                                <tr>
                                    <th width="200px" style="background-color: #B8B8B8">Captador</th>
                                    <th style="background-color: #B8B8B8" align="center">Contrato</th>
                                    <th style="width:20px; background-color: #B8B8B8" align="center">Data Contrato</th>
                                    <th style="width:20px; background-color: #B8B8B8" align="center">Quadra</th>
                                    <th style="width:20px; background-color: #B8B8B8" align="center">Lote</th>
                                    <th style="background-color: #B8B8B8">Cliente</th>
                                    <th style="background-color: #B8B8B8">Corretor</th>
                                    <th style="background-color: #B8B8B8">Valor Venda</th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php $i = 0;
                                $vlTotal = 0;
                                $vlSubtotal = 0;
                                foreach ($listaCaptacoes as $captacoesUsuario) :
                                    $classe = 'tdCaptador';
                                    $vlSubtotal = 0;
                                    ?>
                                    <tr>
                                        <td width="200px" class="<?= $classe ?>"
                                            align='left'><?= $captacoesUsuario[0]['nmPessoaCaptador'] ?></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td style="width:20px;" class="<?= $classe ?>"></td>
                                        <td style="width:20px;" class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                    </tr>
                                    <?php foreach ($captacoesUsuario as $registro) : ?>
                                    <?php
                                    if ($i == 0) {
                                        $i = 1;
                                        $classe = 'tdAvulso2';
                                    } else {
                                        $i = 0;
                                        $classe = 'tdAvulso1';
                                    }
                                    $vlTotal += $registro['vlTotal'];
                                    $vlSubtotal += $registro['vlTotal'];
                                    $nrTotal++;
                                    ?>
                                    <tr>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>" align='center'><?= $registro['idContrato'] ?></td>
                                        <td class="<?= $classe ?>"
                                            align='center'><?= ($registro['dt_contrato']) ? Helper::dataParaBrasil($registro['dt_contrato']) : ' ' ?></td>
                                        <td class="<?= $classe ?>" align='center'><?= $registro['quadra'] ?></td>
                                        <td class="<?= $classe ?>" align='center'><?= $registro['lote'] ?></td>
                                        <td class="<?= $classe ?>" align='left'><?= $registro['nmPessoaCliente'] ?></td>
                                        <td class="<?= $classe ?>"
                                            align='left'><?= $registro['nmPessoaCorretor'] ?></td>
                                        <td class="<?= $classe ?>"
                                            align='right'><?= Helper::getMoney($registro['vlTotal']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                    <tr>
                                        <td width="200px" align='left'><b>Total Captados:</b></td>
                                        <td align="center"><?= count($captacoesUsuario) ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td align='left'><b>Total</b></td>
                                        <td align="right"><?= Helper::getMoney($vlSubtotal) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr><td><br></td></tr>
                                <tr>
                                    <td style="font-size: 11pt"><b>Total de Captações</b></td>
                                    <td style="font-size: 11pt" align="center"><?= $nrTotal ?></td>
                                    <td colspan="4"></td>
                                    <td style="font-size: 11pt"><b>Valor Total</b></td>
                                    <td style="font-size: 11pt" align="right"><?= Helper::getMoney($vlTotal) ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</page>