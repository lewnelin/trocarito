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

                            <h3>933</h3>
                        </div>
                        <p>Você já realizou <b>933</b> contribuições!</p>
                    </div>
                    <div class="col-sm-3 box0">
                        <div class="box1">
                            <span class="li_banknote"></span>

                            <h3>R$ 135,00</h3>
                        </div>
                        <p>Valor total contribuído: <b>R$ 135,00</b></p>
                    </div>
                    <div class="col-sm-3 box0">
                        <div class="box1">
                            <span class="li_shop"></span>

                            <h3>3</h3>
                        </div>
                        <p><b>3</b> Instituição são apoiadas por você!</p>
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
                                <div class="desc">
                                    <div class="details"><span style="color: red">Instituição A</span></div>
                                </div>
                                <div class="desc">
                                    <div class="thumb"></div>
                                    <div class="details"><span style="color: green">Instituição B</span></div>
                                </div>
                                <div class="desc">
                                    <div class="thumb"></div>
                                    <div class="details"><span style="color: blue">Instituição C</span></div>
                                </div>
                            </div>
                            <script>
                                var doughnutData = [
                                    {
                                        value: 30,
                                        color: "red"
                                    },
                                    {
                                        value: 30,
                                        color: "green"
                                    },
                                    {
                                        value: 40,
                                        color: "blue"
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
                                <h5>TOP USER</h5>
                            </div>
                            <p><img src="assets/img/ui-zac.jpg" class="img-circle" width="80"></p>

                            <p><b>Zac Snider</b></p>

                            <div class="row">
                                <div class="col-md-6">
                                    <p class="small mt">MEMBER SINCE</p>

                                    <p>2012</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="small mt">TOTAL SPEND</p>

                                    <p>$ 47,60</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /col-md-4 -->


                </div>
                <!-- /row -->


                <div class="row">
                    <!-- TWITTER PANEL -->
                    <div class="col-md-4 mb">
                        <div class="darkblue-panel pn">
                            <div class="darkblue-header">
                                <h5>DROPBOX STATICS</h5>
                            </div>
                            <canvas id="serverstatus02" height="120" width="120"></canvas>
                            <script>
                                var doughnutData = [
                                    {
                                        value: 60,
                                        color: "#68dff0"
                                    },
                                    {
                                        value: 40,
                                        color: "#444c57"
                                    }
                                ];
                                var myDoughnut = new Chart(document.getElementById("serverstatus02").getContext("2d")).Doughnut(doughnutData);
                            </script>
                            <p>April 17, 2014</p>
                            <footer>
                                <div class="pull-left">
                                    <h5><i class="fa fa-hdd-o"></i> 17 GB</h5>
                                </div>
                                <div class="pull-right">
                                    <h5>60% Used</h5>
                                </div>
                            </footer>
                        </div>
                        <! -- /darkblue panel -->
                    </div>
                    <!-- /col-md-4 -->


                    <div class="col-md-4 mb">
                        <!-- INSTAGRAM PANEL -->
                        <div class="instagram-panel pn">
                            <i class="fa fa-instagram fa-4x"></i>

                            <p>@THISISYOU<br/>
                                5 min. ago
                            </p>

                            <p><i class="fa fa-comment"></i> 18 | <i class="fa fa-heart"></i> 49</p>
                        </div>
                    </div>
                    <!-- /col-md-4 -->

                    <div class="col-md-4 col-sm-4 mb">
                        <!-- REVENUE PANEL -->
                        <div class="darkblue-panel pn">
                            <div class="darkblue-header">
                                <h5>REVENUE</h5>
                            </div>
                            <div class="chart mt">
                                <div class="sparkline" data-type="line" data-resize="true" data-height="75"
                                     data-width="90%" data-line-width="1" data-line-color="#fff" data-spot-color="#fff"
                                     data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="4"
                                     data-data="[200,135,667,333,526,996,564,123,890,464,655]"></div>
                            </div>
                            <p class="mt"><b>$ 17,980</b><br/>Month Income</p>
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