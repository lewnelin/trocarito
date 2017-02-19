<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';
$instituicao = $this->get('instituicao');
$usuario = $this->get('usuario');
?>
    <style>
        .widget {
            position: relative;
            background: #fff;
            color: #5b5b5b;
            margin-bottom: 20px;
            height: 170px;
        }
    </style>

    <h3><i class="fa fa-angle-right"></i> Instituição</h3>
    <div class="row">
        <?= ($this->get('fail')) ? $this->get('fail') : '' ?>

        <div class="row mt">
            <div class="col-lg-12">
                <div class="form-panel">
                    <h4 class="mb"><i class="fa fa-angle-right"></i> Editar</h4>
                    <form class="form-horizontal style-form" method="post">
                        <fieldset>
                            <legend><h4>Informações Representante</h4></legend>
                            <div class="form-group">
                                <?= ($this->get('nomeRepresentante'))?$this->get('nomeRepresentante'):'' ?>
                                <label class="col-sm-2 col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nomeRepresentante" value="<?= $usuario['nome'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <?= ($this->get('email'))?$this->get('email'):'' ?>
                                <label class="col-sm-2 col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" name="email" value="<?= $usuario['email'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <?= ($this->get('senha'))?$this->get('senha'):'' ?>
                                <label class="col-sm-2 col-sm-2 control-label">Senha</label>
                                <div class="col-sm-10">
                                    <input type="text" name="senha" value="" class="form-control">
                                </div>
                            </div>
                            <legend><h4>Informações Instituição</h4></legend>
                            <div class="form-group">
                                <?= ($this->get('nomeInstituicao'))?$this->get('nomeInstituicao'):'' ?>
                                <label class="col-sm-2 col-sm-2 control-label">Nome</label>
                                <div class="col-sm-10">
                                    <input type="text" name="nomeInstituicao" value="<?= $instituicao['nome'] ?>"
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <?= ($this->get('categoria'))?$this->get('categoria'):'' ?>
                                <label class="col-sm-2 col-sm-2 control-label">Categoria</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="categoria">
                                        <option value="">Selecione...</option>
                                        <?php foreach ($this->get('listaCategoria') as $categoria): ?>
                                            <option value="<?= $categoria['id_categoria'] ?>" <?= ($instituicao['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '' ?>><?= $categoria['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= ($this->get('descricao'))?$this->get('descricao'):'' ?>
                                <label class="col-sm-2 col-sm-2 control-label">Descrição</label>
                                <div class="col-sm-10">
                                    <textarea name="descricao" class="form-control"><?= $instituicao['descricao'] ?></textarea>
                                </div>
                            </div>
                            <legend><h4>Operações</h4></legend>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <input class="form-control btn btn-success" type="submit" value="Salvar">
                                </div>
                                <div class="col-sm-2">
                                    <a class="form-control btn btn-danger" href="?m=dashboard&c=instituicao&a=index">Voltar</a>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div><!-- col-lg-12-->
        </div>

    </div>

<?php require_once 'layout/includes/footer.php'; ?>