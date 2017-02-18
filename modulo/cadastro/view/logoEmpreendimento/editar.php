<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$empreendimento = $this->get('empreendimento');
$image = $this->get('image');
if (!file_exists($image)) {
    $image = 'public/images/imagemInvalida.jpg';
}
?>
    <fieldset>
        <form id="editarTabelaPrecoLotes" enctype="multipart/form-data" class="form" role="form" method="POST"
              action="<?php echo '?m=' . $_GET['m'] . '&c=' . $_GET['c'] . '&a=' . $_GET['a'] . "&id=" . $_GET['id']; ?>">
            <fieldset>
                        
                <legend>Logo Empreendimento</legend>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <!-- Selecionar empreendimento. Se houver mudanças na tabela, perguntar se o usuário tem certeza
                    que quer trocar de empreendimento e descartar todas as mudanças-->
                                <label for="Empreendimento" class="control-label cl-xs-3">Empreendimento:<span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <select name="Empreendimento" id="Empreendimento" class="form-control"
                                        title="Escolha o Empreendimento" emptyText="Escolha o Empreendimento"
                                        useEmpty="true"
                                        disabled>
                                    <?php echo '<option value="' . $empreendimento['id'] . '">' . $empreendimento['nm_empreendimento'] . '</option>'; ?>
                                </select>
                                <input type="hidden" id="empreendimentoHidden" name="empreendimentoHidden">
                            </div>
                            <div class="form-group col-sm-6">
                                <div class="form-group col-sm-5">
                                    <img src="<?php echo $image ?>" id="imgPreView"
                                         class="img-frame control-label" alt="LogoDefault"
                                         title="Logo" style="max-width: 100%; max-height: 100%"></div>
                                <div class="col-sm-5">
                                    <label for="arquivoCadastro" class="control-label cl-xs-3">Logomarca:<span class="text-danger" title="Este campo é obrigatório">*</span></label><br>
                            <span class="file-input-wrapper">
                                <input type="file" id="arquivoCadastro" name="arquivoCadastro"
                                       class="btn btn-default"
                                       title="Selecione o arquivo">
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row"><br></div>
            </fieldset>

            <legend><strong>Operações</strong></legend>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-2">
                            <input type="submit" class="btn btn-primary col-sm-12" value="Salvar"/>
                        </div>
                        <div class="col-sm-2">
                            <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar"
                               class="btn btn-danger col-sm-12">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </fieldset>
<?php require_once 'layout/includes/footer.php'; ?>