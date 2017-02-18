<?php

$listaContatosIndicacao = $this->get('listaContatosIndicacao');

$infoIndicacao = $listaContatosIndicacao[0];

$ultimoContatoIndicacao = end($listaContatosIndicacao);
?>

<fieldset>
    <form id='registerForm' class="form" role="form" method="POST"
          action="?m=<?= $_GET['m'] ?>&c=<?= $_GET['c']; ?>&a=<?= $_GET['a']; ?>">
        <fieldset>
                    
            <legend>Informações da Indicação</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Empreendimento:</b></label> <?= $infoIndicacao['nm_empreendimento'] ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Captador:</b></label> <?= $infoIndicacao['nmPessoaUsuario'] ?>
                    </div>
                </div>
            </div>
            <?php if (isset($infoIndicacao['nmPessoaCliente'])) : ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group col-sm-6">
                            <label
                                class="control-label"><b>Indicado
                                    Por:</b></label> <?= $infoIndicacao['nmPessoaCliente'] ?>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="control-label"><b>Telefones:</b></label> <?php
                            $nrTelefone = ($infoIndicacao['nr_telefone']) ? ' ' . $infoIndicacao['nr_telefone'] : '';
                            $nrCelular = ($infoIndicacao['nr_celular']) ? ' ' . $infoIndicacao['nr_celular'] : '';
                            $nrFax = ($infoIndicacao['nr_fax']) ? ' ' . $infoIndicacao['nr_fax'] : '';
                            $nrRecado = ($infoIndicacao['nr_recado']) ? ' ' . $infoIndicacao['nr_recado'] : '';
                            echo $nrTelefone . $nrCelular . $nrFax . $nrRecado
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Indicado(a):</b></label> <?= $infoIndicacao['nm_indicado'] ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Telefone:</b></label> <?= $infoIndicacao['nrTelefoneIndicado'] ?>
                        <?php if (isset($infoIndicacao['ds_email']) && $infoIndicacao['ds_email'] != '') : ?>
                            <br>
                            <label class="control-label"><b>Email: </b></label><?= ' ' . $infoIndicacao['ds_email'] ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <legend>Contatos Realizados</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-12">
                        <div style="overflow:auto;height:220px;border-collapse: collapse;">

                            <?php
                            if ($listaContatosIndicacao):
                                foreach ($listaContatosIndicacao AS $contato): ?>

                                    <div
                                        style="background:#f4f4f4;border:1px solid #AAAAAA; padding:5px; margin-top: 5px;">

                                        <!-- Nome -->
                                        <div style="float:left; font-weight: bold;">
                                            Captador:
                                        </div>
                                        <div style="padding-left: 110px;">
                                            <?= ($contato['nmPessoaUsuario'] != "") ? $contato['nmPessoaUsuario'] : '-----'; ?>
                                        </div>

                                        <!-- Tipo -->
                                        <div style="float:left; font-weight: bold;">
                                            Tipo:
                                        </div>
                                        <div style="padding-left: 110px;">
                                            <?= ($contato['nmTipoContato'] != "") ? $contato['nmTipoContato'] : '-----'; ?>
                                        </div>

                                        <!-- Data -->
                                        <div style="float:left;padding-top:7px; font-weight: bold;">
                                            Data:
                                        </div>
                                        <div style="padding-left: 110px;padding-top:7px;">
                                            <?= ($contato['dt_contato'] != "") ? Helper::getDate($contato['dt_contato']) : '-----'; ?>
                                        </div>

                                        <!-- Hora -->
                                        <div style="float:left; padding-top:7px;font-weight: bold;">
                                            Hora:
                                        </div>
                                        <div style="padding-left: 110px;padding-top:7px;">
                                            <?= ($contato['hr_contato'] != "") ? $contato['hr_contato'] : '-----'; ?>
                                        </div>

                                        <!-- Observação da Atividade -->
                                        <div style="float:left; font-weight: bold;">
                                            Observação:
                                        </div>
                                        <div style="padding-left: 110px;">
                                            <?= ($contato['ds_contato'] != "") ? $contato['ds_contato'] : '-----'; ?>
                                        </div>
                                    </div>

                                <?php
                                endforeach;
                            endif;
                            ?>

                        </div>
                    </div>
                </div>
            </div>

        </fieldset>

    </form>
</fieldset>
