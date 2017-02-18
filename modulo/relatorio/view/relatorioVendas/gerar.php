<?php
$listaContratos = $this->get("listaContratos");
$cabecalho = $this->get('cabecalho');

$clienteAcade = $this->get("clienteAcade");
?>
<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.10.2.min.js"></script>

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.js"></script>

<script>
    $(document).ready(function () {
        var options = {
            searching: false,
            ordering: false,
            paging: false,
            info: false
        };

        $(".parcelas_corretor").dataTable(options);
        $(".totais_corretor").dataTable(options);
        $(".parcelas_imobiliaria").dataTable(options);
        $(".totais_imobiliaria").dataTable(options);
    })
</script>
<style>
    th, td {
        white-space: nowrap;
        padding-top: 5px;
        padding-bottom: 5px;
    }

    #cabecalho td {
        padding: 10px;
    }

    .table-responsive {
        margin-top: 5px;
    }

    div, body, td, h1, h2, h3, h4, strong {
        background: #fff !important;
        font-family: arial !important;
        color: #000 !important;
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
                            <h3><strong>RELATÓRIO DE VENDAS</strong></h3>

                            <h4><strong>Empreendimento: </strong><?php echo $cabecalho['empreendimento']; ?></h4>
                            <h4>
                                <strong>Período: </strong><?php echo $cabecalho['dataDe'] . " à " . $cabecalho['dataAte']; ?>
                            </h4>
                            <h4><strong>Corretor: </strong><?php echo $cabecalho['corretor']; ?></h4>
                        </td>
                    </tr>
                </table>

                <div class="widget col-md-8">
                    <div class="widget-content padding">
                        <div class="table-responsive">
                            <table class="parcelas_corretor table-bordered" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Contrato</th>
                                    <th align="center">Data</th>
                                    <th>Quadra</th>
                                    <th>Lote</th>
                                    <th>Cliente</th>
                                    <th>Valor Sinal (R$)</th>
                                    <th>Valor Venda (R$)</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                $vlTotalSinal = 0;
                                $vlTotalVenda = 0;
                                foreach ($listaContratos as $contrato) :
                                    $vlTotalSinal += $contrato['vlTotalSinal'];
                                    $vlTotalVenda += $contrato['vlTotal'];
                                    ?>
                                    <tr>
                                        <td align='center'><?= $contrato['idContrato'] ?></td>
                                        <td align='center'><?= $contrato['dt_contrato'] ?></td>
                                        <td align='center'><?= $contrato['quadra'] ?></td>
                                        <td align='center'><?= $contrato['lote'] ?></td>
                                        <td><?= $contrato['nm_cliente'] ?></td>
                                        <td align="right"><?= Helper::getMoney($contrato['vlTotalSinal']) ?></td>
                                        <td align="right"><?= Helper::getMoney($contrato['vlTotal']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>

                            </table>
                            <div class="table-responsive">
                                <table class="totais_corretor">
                                    <tbody>
                                    <tr>
                                        <th>Total de Vendas</th>
                                        <td><?= count($listaContratos) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Valor Total Sinal</th>
                                        <td>R$ <?= Helper::getMoney($vlTotalSinal) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Valor Total Vendas</th>
                                        <td>R$ <?= Helper::getMoney($vlTotalVenda) ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</page>