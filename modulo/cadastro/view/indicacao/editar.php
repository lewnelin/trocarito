<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaEmpreendimentos = $this->get('listaEmpreendimentos');
$idEmpreendimento = $this->get('idEmpreendimento');
$indicacao = $this->get('indicacao');
$cliente = $this->get('cliente');

?>

<fieldset>
    <form id='registerForm' class="form" role="form" method="POST" action="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=<?php echo $_GET['a']; ?>">
                
        <legend>
            Informações da Indicação
            <span style="font-size: 10px;">(Campos com <span class="text-danger" title="Este campo é obrigatório.">*</span> são obrigatórios)</span>
        </legend>

        <input type="hidden" id="idIndicacao" name="idIndicacao" value="<?= $indicacao['id_indicacao'] ?>">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="idEmpreendimento" class="control-label">Empreendimento: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <select class="form-control" name="idEmpreendimento" id="idEmpreendimento" disabled>
                        <?php Helper::geraOptionsSelect($this->get("listaEmpreendimentos"), 'id', 'nm_empreendimento', $idEmpreendimento); ?>
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label for="nmCaptador" class="control-label">Captador:</label>
                    <select class="form-control" name="nmCaptador" id="nmCaptador" disabled>
                        <option><?= $this->get('usuario')['nm_pessoa'] . ' - ' . date('d/m/Y - H:i:s') ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="idCliente" class="control-label">Indicado Por:</label>
                    <select class="form-control" name="idCliente" id="idCliente">
                        <?php if(isset($indicacao['id_cliente'])) Helper::geraOptionsSelect($this->get("listaPessoas"), 'id', 'nm_pessoa', $indicacao['id_cliente']); ?>
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label class="control-label">Telefones:</label><br>
                    <span id="infoTelefone"><?php echo (isset($indicacao['id_cliente'])) ? $cliente['nr_telefone'] . ' ' . $cliente['nr_celular'] . ' ' . $cliente['nr_recado'] . ' ' : '' ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="nmIndicado" class="control-label">Pessoa Indicada: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <input type="text" class="form-control" id="nmIndicado" name="nmIndicado" value="<?= $indicacao['nm_indicado'] ?>" placeholder="Digite o nome do Cliente" required>
                </div>
                <div class="form-group col-sm-2">
                    <label for="nrTelefone" class="control-label">Telefone: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <input type="text" class="form-control maskTelefone" id="nrTelefone" name="nrTelefone" value="<?= $indicacao['nr_telefone'] ?>" placeholder="Digite o telefone do Cliente" required>
                </div>
                <div class="form-group col-sm-4">
                    <label for="dsEmail" class="control-label">E-Mail:</label>
                    <input type="text" class="form-control" id="dsEmail" name="dsEmail" value="<?= $indicacao['ds_email'] ?>" placeholder="Digite o e-mail do Cliente">
                </div>
            </div>
        </div>

        <div class="row">
            <input type="hidden" id="idContatoIndicacao" name="idContatoIndicacao" value="<?= $indicacao['idContato'] ?>">
            <div class="col-sm-12">
                <div class="form-group col-sm-3">
                    <label for="dtContato" class="control-label">Data: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <input type="text" class="form-control limpaCampos maskData dtpicker" id="dtContato" name="dtContato" value="<?= Helper::getDate($indicacao['dtIndicacao']) ?>" placeholder="Digite a Data" required>
                </div>
                <div class="form-group col-sm-3">
                    <label for="hrContato" class="control-label">Hora: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                    <input type="text" class="form-control maskHora limpaCampos" id="hrContato" name="hrContato" value="<?= $indicacao['hrIndicacao'] ?>" placeholder="Digite a Hora" required>
                </div>
                <div class="form-group col-sm-6">
                    <label for="dsContato" class="control-label">Observação: </label>
                    <textarea id="dsContato" name="dsContato" class="form-control" cols="6" rows="3"><?= $indicacao['obs'] ?></textarea>
                </div>
            </div>
        </div>

        <legend><b>Operações</b></legend>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-primary col-sm-12" value="Salvar"/>
                </div>
                <div class="form-group col-sm-2">
                    <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar" class="btn btn-danger col-sm-12">Cancelar </a>
                </div>
            </div>
        </div>

    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
