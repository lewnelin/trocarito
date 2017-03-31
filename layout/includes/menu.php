<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <!--logo start-->
    <a href="?m=dashboard&c=index&a=index" class="logo"><b><img alt="avatar" height="30"
                                                                src="images/logoRotasRumos.png"></b></a>
    <!--logo end-->

    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <li><a class="logout" href="?m=dashboard&c=login&a=logout">Logout</a></li>
        </ul>
    </div>
</header>
<?php
if (isset($_SESSION['rotaserumos'])) {
    $user = ($_SESSION['rotaserumos']);
    $user = Db_Usuario::find($user->id_usuario);
}
?>

<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <p class="centered"><img
                    src="assets/img/ui-sherman.jpg"
                    class="img-circle" width="60"></p>
            <h5 class="centered"><?= isset($user->nome) ? $user->nome : '' ?></h5>

            <li class="mt">
                <a class="<?= ($_GET['c'] == 'index') ? 'active' : '' ?>" href="index.php">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!--
            <li class="mt">
                <a class="<?= ($_GET['c'] == 'instituicao') ? 'active' : '' ?>"
                   href="?m=dashboard&c=instituicao&a=index">
                    <i class="li_shop"></i>
                    <span>Instituição</span>
                </a>
            </li>
            -->

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>

<section id="main-content">

    <?= $this->_helper->criaBreadcrumb() ?>

    <?php
    if (isset($_GET['msgSuccess']) && strlen($_GET['msgSuccess']) > 0 || isset($this->mensagem['msgSuccess'])) {
        echo '<div class="alert alert-success alert-dismissable" align="center">';
        echo(isset($_GET['msgSuccess']) ? $_GET['msgSuccess'] : $this->mensagem['msgSuccess']);
        echo '</div>';
    }
    if (isset($_GET['msgFail']) && strlen($_GET['msgFail']) > 0 || isset($this->mensagem['msgFail'])) {
        echo '<div class="alert alert-danger alert-dismissable" align="center">';
        echo(isset($_GET['msgFail']) ? $_GET['msgFail'] : $this->mensagem['msgFail']);
        echo '</div>';
    }
    if (isset($_GET['msgWarning']) && strlen($_GET['msgWarning']) > 0 || isset($this->mensagem['msgWarning'])) {
        echo '<div class="alert alert-warning alert-dismissable" align="center">';
        echo(isset($_GET['msgWarning']) ? $_GET['msgWarning'] : $this->mensagem['msgWarning']);
        echo '</div>';
    }
    ?>


    <section class="wrapper">

        <?= ($this->get('success')) ? $this->get('success') : '' ?>
<?= ($this->get('fail')) ? $this->get('success') : '' ?>