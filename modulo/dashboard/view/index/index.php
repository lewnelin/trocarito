<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$usuario = $this->get('user');
$instituicoes = $this->get('instituicoes');
$doacoes = $this->get('doacoes');
$doador = $this->get('doador');
$vlDoado = $this->get('vlDoado');
$cores = array(
    'red', 'blue', 'green', 'yellow', 'black', 'light-blue'
);
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

<?php if (isset($_GET['permissao'])): ?>
    <div class="alert alert-danger alert-dismissable">
        <div align="center">
            <?= $_GET['permissao'] ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->_helper->getMensagens()) : ?>
    <?php foreach ($this->_helper->getMensagens() as $mensagem) : ?>
        <div class="alert alert-danger alert-dismissable">
            <div align="center">
                <?= $mensagem ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

    <div class="row">
        <form id="formDashboard" method="post" action="?m=dashboard&c=index&a=buscaRota">
            <div class="col-sm-12 main-chart">
                <div class="row">
                    <div class="col-sm-9" style="border: 3px groove; padding: 15px; margin: 10px" align="center">
                        <div>
                            <label>Origem: </label>
                            <input type="text" size="80" name="dsOrigem">
                        </div>
                        <!--<div>
                            <label>Destino:</label>
                            <input type="text" size="80" name="dsDestino">
                        </div>
                        -->
                        <div align="right">
                            <input class="btn" type="submit" value="Buscar Rota">
                        </div>
                    </div>
                </div>
                <!-- /row -->

            </div>
        </form>

        <input type="hidden" id="ultimoId" name="ultimoId" value=""/>
        <input type="hidden" id="idCorretorAtual" name="idCorretorAtual"
               value="<?= Login::getUsuario()->getId() . '_' . Login::getUsuario()->getSuper() ?>"/>

    </div>

<?php require_once 'layout/includes/footer.php'; ?>