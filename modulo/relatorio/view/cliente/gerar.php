<?php
$listaPessoas = $this->get("listaPessoas");
$cabecalho = $this->get('cabecalho');

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
                        <td>
                            <h3><strong>RELATÓRIO DE CLIENTES</strong></h3>

                            <h4><strong>Empreendimento: </strong><?php echo $cabecalho['empreendimento']; ?></h4>
                            <h4>
                                <strong>Período: </strong><?php echo $cabecalho['dataDe'] . " à " . $cabecalho['dataAte']; ?>
                            </h4>
                            <h4><strong>Cliente: </strong><?php echo $cabecalho['tpCliente']; ?></h4>
                        </td>
                    </tr>
                </table>

                <div class="widget col-md-8">
                    <div class="widget-content padding">
                        <div class="table-responsive">
                            <table class="parcelas_corretor table-bordered" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                    <th width="300px" style="background-color: #B8B8B8">Nome</th>
                                    <th style="background-color: #B8B8B8" align="center">CPF/CNPJ</th>
                                    <th width="150px" style="background-color: #B8B8B8">Cidade</th>
                                    <th width="150px" style="background-color: #B8B8B8">Telefone</th>
                                    <th width="150px" style="background-color: #B8B8B8">Email</th>
                                    <th style="background-color: #B8B8B8">Dt Cadastro</th>
                                    <th style="background-color: #B8B8B8">Contrato</th>
                                    <th style="background-color: #B8B8B8">Qd</th>
                                    <th style="background-color: #B8B8B8">Lt</th>
                                    <th style="background-color: #B8B8B8">Dt Contrato</th>
                                    <th style="background-color: #B8B8B8">Dt Distrato</th>
                                </tr>
                                </thead>

                                <tbody>

                                <?php $i = 0; foreach ($listaPessoas as $pessoa) : ?>
                                    <?php if ($cabecalho['tpCliente'] != 'Avulso') {
                                        $classe = 'tdComum';
                                    } else {
                                        if($i == 0){
                                            $i = 1;
                                            $classe = 'tdAvulso2';
                                        } else {
                                            $i = 0;
                                            $classe = 'tdAvulso1';
                                        }
                                    } ?>
                                    <tr>
                                        <td width="300px" class="<?= $classe ?>"
                                            align='left'><?= $pessoa[0]['nm_pessoa'] ?></td>
                                        <td class="<?= $classe ?>"
                                            align='center'><?= ($pessoa[0]['nr_cpf']) ? $pessoa[0]['nr_cpf'] : (($pessoa[0]['nr_cnpj']) ? $pessoa[0]['nr_cnpj'] : '') ?></td>
                                        <td class="<?= $classe ?>" width="150px"
                                            align='left'><?= $pessoa[0]['nm_cidade']; echo ($pessoa[0]['uf']) ? '/' . $pessoa[0]['uf'] : ''?></td>
                                        <td width="150px" class="<?= $classe ?>"
                                            align='center'><?= $pessoa[0]['nr_telefone'] . ' ' . $pessoa[0]['nr_celular'] . ' ' . $pessoa[0]['nr_recado'] ?></td>
                                        <td width="150px" class="<?= $classe ?>"
                                            align='center'><?= $pessoa[0]['email'] ?></td>
                                        <td class="<?= $classe ?>"
                                            align='center'><?= ($pessoa[0]['dt_cadastro']) ? Helper::dataParaBrasil($pessoa[0]['dt_cadastro']) : ' ' ?></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                        <td class="<?= $classe ?>"></td>
                                    </tr>
                                    <?php if ($cabecalho['tpCliente'] != 'Avulso') : ?>
                                        <?php foreach ($pessoa as $registro) : ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td align='center'><?= $registro['idContrato'] ?></td>
                                                <td align='center'><?= $registro['quadra'] ?></td>
                                                <td align='center'><?= $registro['lote'] ?></td>
                                                <td align='center'><?= ($registro['dt_contrato']) ? Helper::dataParaBrasil($registro['dt_contrato']) : ' ' ?></td>
                                                <td align='center'><?= ($registro['dt_distrato']) ? Helper::dataParaBrasil($registro['dt_distrato']) : ' ' ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                <?php endforeach; ?>

                                </tbody>

                            </table>
                            <div class="table-responsive">
                                <table class="totais_corretor">
                                    <tbody>
                                    <tr>
                                        <th>Total de clientes</th>
                                        <td><?= count($listaPessoas) ?></td>
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