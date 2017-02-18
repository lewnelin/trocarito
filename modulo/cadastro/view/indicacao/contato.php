<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

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
                            class="control-label"><b>Captador:</b></label> <?= ($infoIndicacao['nmPessoaCliente']) ? $infoIndicacao['nmPessoaCliente'] : $infoIndicacao['nmPessoaUsuario'] ?>
                        <br>
                        <label
                            class="control-label"><b>CPF/CNPJ:</b></label> <?= ($infoIndicacao['nr_cpf']) ? $infoIndicacao['nr_cpf'] : $infoIndicacao['nr_cnpj'] ?>
                        <br>
                        <label class="control-label"><b>CEP:</b></label> <?= $infoIndicacao['nr_cep'] ?><br>
                        <label class="control-label"><b>Endereço:</b></label> <?= $infoIndicacao['endereco'] ?><br>
                        <label class="control-label"><b>Bairro:</b></label> <?= $infoIndicacao['nm_bairro'] ?><br>
                        <label class="control-label"><b>Telefones:</b></label> <?php
                        $nrTelefone = ($infoIndicacao['nr_telefone']) ? ' ' . $infoIndicacao['nr_telefone'] : '';
                        $nrCelular = ($infoIndicacao['nr_celular']) ? ' ' . $infoIndicacao['nr_celular'] : '';
                        $nrFax = ($infoIndicacao['nr_fax']) ? ' ' . $infoIndicacao['nr_fax'] : '';
                        $nrRecado = ($infoIndicacao['nr_recado']) ? ' ' . $infoIndicacao['nr_recado'] : '';
                        echo $nrTelefone . $nrCelular . $nrFax . $nrRecado
                        ?>
                    </div>
                    <div class="form-group col-sm-6">
                        <label
                            class="control-label"><b>Empreendimento:</b></label> <?= $infoIndicacao['nm_empreendimento'] ?>
                        <br>
                        <label
                            class="control-label"><b>Indicado(a):</b></label> <?= $infoIndicacao['nm_indicado'] ?>
                        <br>
                        <label
                            class="control-label"><b>Telefone:</b></label> <?= $infoIndicacao['nr_telefone'] ?>
                    </div>
                </div>
            </div>

            <legend>Contatos Realizados</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-12">
                        <div style="overflow:auto;height:255px;border-collapse: collapse;">

                            <?php
                            if ($listaContatosIndicacao):
                                foreach ($listaContatosIndicacao AS $contato): ?>

                                    <div
                                        style="background:#f4f4f4;border:1px solid #AAAAAA; padding:5px; margin-top: 5px;">

                                        <!-- Nome -->
                                        <div style="float:left; font-weight: bold;">
                                            Responsável:
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


            <legend>Informações da Indicação
                <span style="font-size: 10px;">(Campos com <span class="text-danger"
                                                                 title="Este campo é obrigatório.">*</span> são obrigatórios)</span>
                <span
                    style="float:right;color:red;"><b><?= $this->get('usuario')['nm_pessoa'] . ' - ' . date('d/m/Y - H:i:s') ?></b></span>
            </legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="dtContato" class="control-label">Data: <span class="text-danger"
                                                                                 title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control dtpicker" id="dtContato" name="dtContato" value="<?= ($contato['dt_contato'] != "") ? Helper::getDate($contato['dt_contato']) : ''; ?>"
                               placeholder="Digite a Data" required>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="hrContato" class="control-label">Hora: <span class="text-danger"
                                                                                 title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control maskHora" id="hrContato" name="hrContato" value="<?= ($contato['dt_contato'] != "") ? $contato['hr_contato'] : ''; ?>"
                               placeholder="Digite a Hora" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="idTipoContato" class="control-label">Tipo de Contato: <span class="text-danger"
                                                                                                title="Este campo é obrigatório.">*</span></label>
                        <select class="form-control" name="idTipoContato" id="idTipoContato" required="required">
                            <?php Helper::geraOptionsSelect($this->get("listaTipoContato"), 'idCampo', 'descricao', $contato['id_tipo_contato']); ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="dsContato" class="control-label">Onservação: </label>
                        <textarea id="dsContato" name="dsContato" class="form-control" cols="6" rows="3"><?= ($contato['dt_contato'] != "") ? $contato['ds_contato'] : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="dtFechamento" class="control-label">Data de Fechamento:</label>
                        <input type="text" class="form-control dtpicker" id="dtFechamento" name="dtFechamento" value=""
                               placeholder="Digite a Data">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="dtFechamento" class="control-label">Criar Novo Contato:</label><br>
                        <input type="button" class="btn btn-success col-sm-12" value="Novo Contato"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                </div>
            </div>

            <legend><b>Operações</b></legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-2">
                        <input type="submit" class="btn btn-primary col-sm-12" value="Salvar"/>
                    </div>
                    <div class="form-group col-sm-2">
                        <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar"
                           class="btn btn-danger col-sm-12">Cancelar </a>
                    </div>
                </div>
            </div>

        </fieldset>

    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
