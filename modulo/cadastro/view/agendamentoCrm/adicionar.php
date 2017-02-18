<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaEmpreendimentos = $this->get('listaEmpreendimentos');
?>

<fieldset>
    <form id='registerForm' class="form" role="form" method="POST" action="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=<?php echo $_GET['a']; ?>">
        <fieldset>
                    
            <legend style="margin: 0">
                Informações da Indicação
                <span style="font-size: 10px;">(Campos com <span class="text-danger" title="Este campo é obrigatório.">*</span> são obrigatórios)</span>
            </legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="idEmpreendimento" class="control-label">Empreendimento: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select class="form-control" name="idEmpreendimento" id="idEmpreendimento" required="required">
                            <?php Helper::geraOptionsSelect($this->get("listaEmpreendimentos"), 'id', 'nm_empreendimento'); ?>
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
                        <label for="idCliente" class="control-label">Cliente: </label>
                        <select class="form-control" name="idCliente" id="idCliente">
                            <?php Helper::geraOptionsSelect($this->get("listaPessoas"), 'id', 'nm_pessoa'); ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="control-label"><b>Telefones:</b></label> <span id="infoTelefone"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="nmIndicado" class="control-label">Pessoa Indicada: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control" id="nmIndicado" name="nmIndicado" value="" placeholder="Digite o nome do Cliente" required>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="nrTelefone" class="control-label">Telefone: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control maskTelefone" id="nrTelefone" name="nrTelefone" value="" placeholder="Digite o telefone do Cliente" required>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="dsEmail" class="control-label">E-Mail:</label>
                        <input type="text" class="form-control" id="dsEmail" name="dsEmail" value="" placeholder="Digite o e-mail do Cliente">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-3">
                        <label for="dtContato" class="control-label">Data: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control limpaCampos maskData dtpicker" id="dtContato" name="dtContato" value="<?= date('d/m/Y') ?>" placeholder="Digite a Data" required>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="hrContato" class="control-label">Hora: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <input type="text" class="form-control maskHora limpaCampos" id="hrContato" name="hrContato" value="<?= date('H:i:s') ?>" placeholder="Digite a Hora" required>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="idTipoContato" class="control-label">Tipo de Contato: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                        <select class="form-control limpaCampos" name="idTipoContato" id="idTipoContato" required="required">
                            <?php Helper::geraOptionsSelect($this->get("listaTipoContato"), 'idCampo', 'descricao'); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6">
                        <label for="dsContato" class="control-label">Observação: </label>
                        <textarea id="dsContato" name="dsContato" class="form-control limpaCampos" cols="6" rows="3"></textarea>
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
        </fieldset>
    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
