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
        <form id="formDashboard" method="post" action="?m=dashboard&c=index&a=dashboardUsuario">
            <div class="col-sm-12 main-chart">

                <div class="row">
                    <div class="col-sm-3 box0">
                        <div class="box1">
                            <span class="li_star"></span>

                            <h3><?= count($doacoes) ?></h3>
                        </div>
                        <p>Você já realizou <b><?= count($doacoes) ?></b> contribuições!</p>
                    </div>
                    <div class="col-sm-3 box0">
                        <div class="box1">
                            <span class="li_banknote"></span>

                            <h3>R$ <?= Helper::getMoney($vlDoado['total']); ?></h3>
                        </div>
                        <p>Valor total contribuído: <b>R$ <?= Helper::getMoney($vlDoado['total']); ?></b></p>
                    </div>
                    <div class="col-sm-3 box0">
                        <div class="box1">
                            <span class="li_shop"></span>

                            <h3><?= count($instituicoes) ?></h3>
                        </div>
                        <p><b><?= count($instituicoes) ?></b> Instituição são apoiadas por você!</p>
                    </div>
                    <div class="col-sm-3 box0">
                        <div class="box1">
                            <span class="li_heart" style="color: red;"></span>

                            <h3><?= isset($user->nv_caridade) ? $user->nv_caridade : '' ?>%</h3>
                        </div>
                        <p>Sua caridade é <?= isset($user->nv_caridade) ? $user->nv_caridade : '' ?>%</p>
                    </div>

                </div>
                <!-- /row mt -->


                <div class="row mt">
                    <!-- SERVER STATUS PANELS -->
                    <div class="col-md-8 mb">
                        <div class="white-panel donut-chart">
                            <div class="white-header">
                                <h5>PERCENTUAIS DE DISTRIBUIÇÃO</h5>
                            </div>
                            <canvas id="instituicoesPie" height="300" width="300"></canvas>
                            <div class="legenda ds" style="float: right; width: 30%">
                                <?php foreach ($instituicoes as $k => $instituicao) : ?>
                                    <div class="desc">
                                        <div class="details"><span
                                                style="color: <?= $cores[$k] ?>"><?= $instituicao['nome'] ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <script>
                                var doughnutData = [
                                    {
                                        value: 30,
                                        color: "blue"
                                    },
                                    {
                                        value: 30,
                                        color: "green"
                                    },
                                    {
                                        value: 30,
                                        color: "yellow"
                                    }
                                ];
                                var myDoughnut = new Chart(document.getElementById("instituicoesPie").getContext("2d")).Pie(doughnutData);
                            </script>
                        </div>
                    </div>
                    <!-- /col-md-4-->


                    <div class="col-md-4 mb">
                        <!-- WHITE PANEL - TOP USER -->
                        <div class="white-panel pn">
                            <div class="white-header">
                                <h5>MAIORES CORAÇÕES</h5>
                            </div>
                            <p>
                                <img src="assets/img/Coracao<?= $doador['nv_caridade'] ?>.png" class="img-circle"
                                     width="60">
                            </p>

                            <p><b><?= $doador['nome'] ?></b></p>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="small mt">NIVEL DE CARIDADE</p>

                                    <p><?= $doador['nv_caridade'] ?>%</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="small mt">TOTAL DOADO</p>

                                    <p>R$ <?= Helper::getMoney($doador['valorDoado']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /col-md-4 -->


                </div>
                <!-- /row -->

            </div>
        </form>

        <input type="hidden" id="ultimoId" name="ultimoId" value=""/>
        <input type="hidden" id="idCorretorAtual" name="idCorretorAtual"
               value="<?= Login::getUsuario()->getId() . '_' . Login::getUsuario()->getSuper() ?>"/>

    </div>

<?php require_once 'layout/includes/footer.php'; ?>