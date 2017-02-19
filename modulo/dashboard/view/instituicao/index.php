<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';
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
        <form id="formDashboard" method="post" action="?m=dashboard&c=index&a=dashboardUsuario">
            <div class="col-sm-12 main-chart">
                <?php
                if ($this->get('listaInstituicao'))
                    foreach ($this->get('listaInstituicao') as $instituicao):?>

                        <div class="col-sm-3 box0">
                            <div class="box1">
                                <span class="li_shop"></span>
                                <h3><?= $instituicao['nome'] ?></h3>
                            </div>
                            <p><?= $instituicao['descricao'] ?></p>
                            <p>
                                <a class="btn btn-primary btn-xs"><i class="fa fa-check"></i></a>
                                <a class="btn btn-success btn-xs" href="?m=dashboard&c=instituicao&a=editar&id=<?= $instituicao['id_instituicao'] ?>">
                                    <i class="fa fa-pencil"></i></a>
                                <a class="btn btn-danger btn-xs" href="?m=dashboard&c=instituicao&a=deletar&id=<?= $instituicao['id_instituicao'] ?>"><i class="fa fa-trash-o "></i></a>
                            </p>
                        </div>

                    <?php endforeach; ?>
            </div>
        </form>
    </div>

<?php require_once 'layout/includes/footer.php'; ?>