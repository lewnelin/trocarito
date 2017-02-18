<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaContatosIndicacao = $this->get('listaContatosIndicacao');

$infoIndicacao = $listaContatosIndicacao[0];

//pega as informações do ultimo contato cadastrado nesta indicacao
$ultimoContatoIndicacao = $infoIndicacao;

$primeiroContato = end($listaContatosIndicacao);

if ($ultimoContatoIndicacao['id_tipo_contato'] == '131') {
    $ultimoContatoIndicacao['dt_contato'] = '';
    $ultimoContatoIndicacao['hr_contato'] = '';
    $ultimoContatoIndicacao['ds_contato'] = '';
}

?>

<fieldset>
    <form id='registerForm' class="form" role="form" method="POST"
          action="?m=<?= $_GET['m'] ?>&c=<?= $_GET['c']; ?>&a=<?= $_GET['a']; ?>&id=<?= $_GET['id']; ?>">
                
        <input type="hidden" name="novoContato" id="novoContato" value="<?= ($ultimoContatoIndicacao['id_tipo_contato'] == '131') ? 1 : 0 ?>"/>
        <input type="hidden" name="idUltimaIndicacaoContato" id="idUltimaIndicacaoContato" value="<?= $infoIndicacao['id_indicacao_contato'] ?>"/>

        <legend>
            Informações da Indicação
            <span style="float:right;color:red;"><b><?= $this->get('usuario')['nm_pessoa'] . ' - ' . date('d/m/Y - H:i:s') ?></b></span>
        </legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-12">
                    <label class="control-label"><b>Empreendimento:</b></label> <?= $infoIndicacao['nm_empreendimento'] ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label
                        class="control-label"><b><?php echo ($primeiroContato['id_tipo_contato'] == '131') ? 'Indicado(a) por:' : 'Cliente:' ?></b></label> <?= ($infoIndicacao['nmPessoaCliente']) ? $infoIndicacao['nmPessoaCliente'] : $infoIndicacao['nmPessoaUsuario'] ?>
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
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label
                        class="control-label"><b>Indicado(a):</b></label> <?= $infoIndicacao['nm_indicado'] ?>
                </div>
                <div class="form-group col-sm-6">
                    <label
                        class="control-label"><b>Telefone:</b></label> <?= $infoIndicacao['nrTelefoneIndicado'] ?><br>
                    <label class="control-label"><b>Email:</b></label> <?= $infoIndicacao['ds_email'] ?>
                    <br>
                </div>
            </div>
        </div>

        <legend>
            Contatos Realizados
        </legend>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-12">
                    <div style="overflow:auto;max-height:255px;border-collapse: collapse;" class="divContatoRealizado">

                        <?php
                        if ($listaContatosIndicacao):
                            foreach ($listaContatosIndicacao AS $contato): ?>

                                <div style="background:#f4f4f4;border:1px solid #AAAAAA; padding:5px; margin-top: 5px;">

                                    <!-- Nome -->
                                    <div style="float:left; font-weight: bold;">
                                        Captador:
                                    </div>
                                    <div style="padding-left: 110px;" class="usuarioContato">
                                        <?= ($contato['nmPessoaUsuario'] != "") ? $contato['nmPessoaUsuario'] : '-----'; ?>
                                    </div>

                                    <!-- Tipo -->
                                    <div style="float:left; font-weight: bold;">
                                        Tipo:
                                    </div>
                                    <div style="padding-left: 110px;" class="tipoContato">
                                        <?= ($contato['nmTipoContato'] != "") ? $contato['nmTipoContato'] : '-----'; ?>
                                    </div>

                                    <!-- Data e hora -->
                                    <div style="float:left;padding-top:7px;">
                                        <b>Data/Hora: </b>
                                    </div>
                                    <div style="padding-left: 110px;padding-top:7px;">
                                        <?= ($contato['dt_contato'] != "") ? Helper::getDate($contato['dt_contato']) : '-----'; ?> - <?= ($contato['hr_contato'] != "") ? $contato['hr_contato'] : '-----'; ?>
                                    </div>

                                    <!-- Observação da Atividade -->
                                    <div style="float:left; font-weight: bold;">
                                        Observação:
                                    </div>
                                    <div style="padding-left: 110px;" class="observacaoContato">
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

        <legend>
            <span id="legendUltimoContato">Contato</span>
            <span style="font-size: 10px;">(Campos com <span class="text-danger" title="Este campo é obrigatório.">*</span> são obrigatórios)</span>
        </legend>

        <div class="row blocoContato">
            <div class="col-sm-12">
                <div class="form-group col-sm-3">
                    <label for="dtContato" class="control-label">Data: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <input type="text" class="form-control limpaCampos maskData dtpicker" id="dtContato" name="dtContato" value="<?= Helper::getDate($ultimoContatoIndicacao['dt_contato']) ?>" placeholder="Digite a Data" required>
                </div>
                <div class="form-group col-sm-3">
                    <label for="hrContato" class="control-label">Hora: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <input type="text" class="form-control maskHora limpaCampos" id="hrContato" name="hrContato" value="<?= $ultimoContatoIndicacao['hr_contato'] ?>" placeholder="Digite a Hora" required>
                </div>
                <div class="col-sm-6 form-group">
                    <label for="idTipoContato" class="control-label">Tipo de Contato: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <select class="form-control limpaCampos" name="idTipoContato" id="idTipoContato" required="required">
                        <?php
                        Helper::geraOptionsSelect($this->get("listaTipoContato"), 'idCampo', 'descricao', $ultimoContatoIndicacao['id_tipo_contato']);
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row blocoContato">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="dsContato" class="control-label">Observação: </label>
                    <textarea id="dsContato" name="dsContato" class="form-control limpaCampos" cols="6" rows="3"><?= $ultimoContatoIndicacao['ds_contato']; ?></textarea>
                </div>
                <div class="form-group col-sm-6 blocoBtnNovoContato" style="display:<?= ($ultimoContatoIndicacao['id_tipo_contato'] == '131') ? 'none' : 'bloked' ?>">
                    <label for="" class="control-label">Adicionar Novo Contato: </label>
                    <input type="button" class="btn btn-success col-sm-12" value="Adicionar Novo Contato" id="btnNovoContato"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="fl_status" class="control-label cl-xs-3">Fechar Contato</label><br>
                    <div class="col-sm-1" style="height: 50px;line-height: 40px; width: 30px">
                        <label style="display: inline-block;vertical-align: middle;line-height: normal;">Não</label>
                    </div>
                    <div class="col-sm-1" style="padding-right: 50px; padding-left: -50px">
                    <input type="checkbox" class="ios-switch flStatus ios-switch-success" value="ativo" name="fl_status" id="fl_status"/>
                    </div>
                    <div class="col-sm-1" style="height: 50px;line-height: 40px;">
                        <label style="display: inline-block;vertical-align: middle;line-height: normal;">Sim</label>
                    </div>
                </div>
            </div>
        </div>

        <legend><b>Operações</b></legend>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-2">
                    <input type="button" class="btn btn-primary col-sm-12" value="Salvar" id="btnSalvarContato"/>
                </div>
                <div class="form-group col-sm-2">
                    <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar" class="btn btn-danger col-sm-12">Voltar</a>
                </div>
            </div>
        </div>

    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
