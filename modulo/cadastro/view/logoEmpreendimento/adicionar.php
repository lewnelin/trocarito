<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaEmpreendimentos = $this->get('listaEmpreendimentos');

?>
<fieldset>
    <form id="adicionarLogoEmpreendimento" enctype="multipart/form-data" class="form" role="form" method="POST"
          action="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=<?php echo $_GET['a']; ?>">
        <fieldset>
                    
            <legend>Logo Empreendimento</legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-12">
                                <label for="Empreendimento" class="control-label cl-xs-3">Empreendimento:<span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <select name="Empreendimento" id="Empreendimento" class="form-control"
                                        title="Escolha o Empreendimento" emptyText="Escolha o Empreendimento"
                                        useEmpty="true">
                                    <?php
                                    Helper::geraOptionsSelect($listaEmpreendimentos, 'id', 'nm_empreendimento');
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-group col-sm-5">
                                <img src="public/images/noLogo.jpg" id="imgPreView"
                                     class="img-frame control-label" alt="LogoDefault"
                                     title="Logo" style="max-width: 100%; max-height: 100%"></div>
                            <div class="col-sm-5">
                                <label for="arquivoCadastro" class="control-label cl-xs-3">Logomarca:<span class="text-danger" title="Este campo é obrigatório">*</span></label><br>
                            <span class="file-input-wrapper">
                                <input type="file" id="arquivoCadastro" disabled name="arquivoCadastro"
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

        <div class="form-group">
            <legend><strong>Operações</strong></legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-2">
                        <input type="submit" id="importar" class="btn btn-primary col-sm-12" disabled value="Salvar"/>
                    </div>
                    <div class="col-sm-2">
                        <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar"
                           class="btn btn-danger col-sm-12">
                            Cancelar </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
