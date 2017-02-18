<?php
$relatorio = $this->get('relatorio');
$cabecalho = $this->get('cabecalho');

$clienteAcade = $this->get("clienteAcade");
?>
<page backtop="-1mm" backbottom="-1mm">
    <style type="text/css">
        table.tabela td {
            border-collapse: collapse;
            padding: 3px 5px 3px 5px;
        }

        table tr td.linhaEscura {
            background-color: #ccc;
        }

        table th {
            background-color: #ccc;
            border: 1px solid #000000;
            padding: 3px 5px 3px 5px;
        }

        table {
            font-family: arial;
            border-collapse: collapse;
        }

        .header td {
            background-color: #B8B8B8;
        }

    </style>
    <div style="margin-bottom:20px;"></div>
    <div style=" width:800px;">
        <table style="font-family: arial;">
            <tr>
                <td valign="top" style="width: 170px;" rowspan="2">
                    <!-- Logomarca Cliente Acade -->
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
                    ?>
                </td>
                <td align="center" width="400" style="font-size: 11pt;">
                    <strong style="text-align: center;margin-top: 20px;">RELATÓRIO DE COMISSÃO</strong><br/>
                <span
                    style="font-size: 9pt;"><strong>Empreendimento: </strong><?= $relatorio['empreendimento']['nm_empreendimento'] ?></span><br/>
                    <?php if ($relatorio['periodoPagamento']) : ?>
                        <span style="font-size: 9pt;"><strong>Data de
                                Pagamento: </strong><?= $relatorio['periodoPagamento']; ?></span><br>
                    <?php endif ?>
                </td>
            </tr>
        </table>
    </div>
    <div style="margin-top:10px;">&nbsp;</div>
    <div style="margin-left:5px; width:800px; float: left">
        <?php if (count($relatorio['corretores'])) : ?>
            <table id="contrato" name="contrato" style="font-size: 12px" width="800px" align="center">
                <?php foreach ($relatorio['corretores'] AS $k => $corretor) : ?>
                    <tr>
                        <td style="padding-bottom: 10px" colspan="8">Corretor:
                            <strong><?= $corretor['nm_pessoa'] ?></strong></td>
                    </tr>
                    <tr class="header">
                        <td width="150px">
                            <strong>Cliente</strong></td>
                        <td align="center">
                            <strong>Qdr</strong></td>
                        <td align="center">
                            <strong>Lote</strong></td>
                        <td align="center">
                            <strong>Parcela</strong></td>
                        <td align="center">
                            <strong>Vencto</strong></td>
                        <td align="center">
                            <strong>Pagto</strong></td>
                        <td align="center">
                            <strong>Crédito</strong></td>
                        <td align="right">
                            <strong>Valor</strong></td>
                        <td align="right" width="80px" style="padding-right: 4px">
                            <strong>Vl. Corretor</strong></td>
                    </tr>
                    <?php
                    $i = 1;
                    $subtotal = 0;
                    ?>
                    <?php if (!isset($corretor['contratos']) || !count($corretor['contratos'])) : ?>
                        <tr>
                            <td colspan="9" align="center">O corretor não possui contratos com parcelas pagas neste
                                período.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if (isset($corretor['contratos']) && is_array($corretor['contratos'])) : ?>
                        <?php foreach ($corretor['contratos'] AS $idContrato => $contrato) : ?>
                            <?php foreach ($contrato['parcelas'] as $parcela): ?>
                                <?php
                                //seta a cor da linha
                                if ($i % 2 == 0) {
                                    $cor = "#CCCCCC";
                                } else {
                                    $cor = "#FFFFFF";
                                }

                                $subtotal += $parcela['vl_comissao'];
                                $i++;
                                ?>
                                <tr style="font-size: 12px; background-color: <?= $cor; ?>;">
                                    <td width="150px"><?= $contrato['nm_pessoa'] ?></td>
                                    <td align="center"><?= $contrato['quadra'] ?></td>
                                    <td align="center"><?= $contrato['lote'] ?></td>
                                    <td align="right">
                                        <?php
                                        switch ($parcela['tp_parcela']) {
                                            case 'S':
                                                echo $parcela['id_parcela'] . '/' . $contrato['nr_parcela_sinal'] . '-' . $parcela['tp_parcela'];
                                                break;
                                            case 'I':
                                                echo $parcela['id_parcela'] . '/' . $contrato['nr_intercalada'] . '-' . $parcela['tp_parcela'];
                                                break;
                                            case 'G':
                                                echo $parcela['id_parcela'] . '/' . $parcela['nr_negociacao'] . '-' . $parcela['tp_parcela'];
                                                break;
                                            case 'C':
                                                echo $parcela['id_parcela'] . '/' . $contrato['nr_parcela_entrega'] . '-' . $parcela['tp_parcela'];
                                                break;
                                            case 'Q':
                                                echo $parcela['id_parcela'] . '/' . $contrato['total']['quitacao'] . '-' . $parcela['tp_parcela'];
                                                break;
                                            default:
                                                echo $parcela['id_parcela'] . '/' . $contrato['nr_parcela'] . '-' . $parcela['tp_parcela'];
                                                break;
                                        }
                                        ?></td>
                                    <td align="center">
                                        <?= $parcela['dt_parcela'] ?>
                                    </td>
                                    <td align="center">
                                        <?= Helper::dataParaBrasil($parcela['dt_pagamento']) ?>
                                    </td>
                                    <td align="center">
                                        <?= Helper::dataParaBrasil($parcela['dt_credito']) ?>
                                    </td>
                                    <td align="right">
                                        <?= Helper::getMoney($parcela['vl_parcela']) ?>
                                    </td>
                                    <td align="right">
                                        <?= Helper::getMoney($parcela['vl_comissao']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <tr>
                        <td colspan="7"></td>
                        <td align="right"><strong>Subtotal:</strong></td>
                        <td align="right"><strong><?= Helper::getMoney($subtotal) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td width="690px" colspan="8">&nbsp;</td>
                </tr>
            </table>
            <table id="contrato" name="contrato" style="font-size: 10px;" width="800px" align="right">
                <tr>
                    <td><strong>Número de Parcelas:</strong> <?= $relatorio['total']['parcela'] ?></td>
                </tr>
                <tr>
                    <td><strong>Total de
                            Parcelas:</strong> <?= 'R$ ' . Helper::getMoney($relatorio['total']['valor']) ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Total
                            Comissão:</strong> <?= 'R$ ' . Helper::getMoney($relatorio['total']['comissao']) ?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</page>