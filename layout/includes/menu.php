<div class="topbar">
    <div class="topbar-left">
        <div class="logo">
            <h1><a href="?m=dashboard&c=dashboard&a=index"><img src="public/img/Acade/acadeone_lg_branca.png"
                                                                alt="Inicio"></a>
            </h1>
        </div>
        <button class="button-menu-mobile open-left">
            <i class="fa fa-bars"></i>
        </button>
    </div>

    <div class="navbar navbar-default" role="navigation">
        <div class="container">
            <div class="navbar-collapse2">
                <ul class="nav navbar-nav navbar-right top-navbar">
                    <li>
                        <label>
                            <div style="margin-top: -32px">
                                <div class="profile-text" style="margin-left: 20px"><br> Painel de <b>Vendas</b></div>
                            </div>
                        </label>
                    </li>
                    <li class="dropdown iconify hide-phone"><a href="#" onclick="javascript:toggle_fullscreen()"><i
                                class="icon-resize-full-2"></i></a></li>
                    <li class="dropdown topbar-profile">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="rounded-image topbar-profile-image">
                                    <img src="public/img/Acade/AcadeLotear_pq.png">
                                </span>
                            <?php
                            if (Login::isLogado()) {
                                $nome = explode(' ', Db_Pessoa::find(Login::getUsuario()->getId())->getNome());
                                $nome[1] = (strlen($nome[1]) > 2) ? $nome[1] : $nome[2];
                                echo $nome[0] . ' <strong>' . $nome[1] . '</strong>';
                            }
                            ?>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= $this->_helper->getLink(array ("m" => "dashboard",
                                    "c" => "login",
                                    "a" => "logout")); ?>"
                                   class="md-trigger" data-modal="logout-modal"><i class="icon-logout-1"></i>
                                    Sair do Sistema
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="left side-menu">

    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div class="clearfix"></div>
        <hr class="divider"/>
        <div class="clearfix"></div>
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a <?= ($this->action == 'painelVenda' ? 'class="active"' : ''); ?>
                        href="?m=dashboard&c=dashboard">

                        <i class="icon-home-3"></i><span>Início</span>
                    </a>
                </li>

                <?php
                $menu = new Menu(Login::getUsuario()->getPerfilId());
                foreach ($menu->getModulos() as $p):

                    if (!$p->getFlPainelVenda() || $p->getNome() == "Dashboard") continue;
                    ?>

                    <li <?= (count($menu->getControllers($p)) ? 'class="has_sub"' : '"'); ?> >

                        <a href="#" class="master">
                            <i class="<?= ($p->ds_icon ? $p->ds_icon : ''); ?>"></i>
                            <span><?= utf8_encode($p->getNome()); ?></span>
                        </a>

                        <ul>
                            <?php foreach ($menu->getControllers($p) as $c): ?>
                                <?php if (file_exists("modulo/" . $p->getPath() . "/controller/" . ucfirst($c->getNome()) . "Controller" . ".php")) : ?>
                                    <li>
                                        <a class="limpaUltimoId <?= ($_GET['c'] == $c->getNome()) ? 'active' : ''; ?>"
                                           href="?m=<?= $p->getPath() ?>&c=<?= $c->getNome(); ?>"><?= utf8_encode($c->getRotulo()); ?>
                                        </a>
                                    </li>
                                <?php else : ?>
                                    <li>
                                        <a class="limpaUltimoId <?= ($_GET['c'] == $c->getNome()) ? 'active' : ''; ?>"
                                           href="#" style="cursor: not-allowed; color: #808080"><?= utf8_encode($c->getRotulo()); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="left-footer">
        <div class="progress progress-xs">
            <div class="progress-bar bg-green-1" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                 aria-valuemax="100" style="width: 80%">
                <span class="progress-precentage">80%</span>
            </div>

            <a data-toggle="tooltip" title="See task progress" class="btn btn-default md-trigger"
               data-modal="task-progress"><i class="fa fa-inbox"></i></a>
        </div>
    </div>
</div>


<!-- MODAL VISUALIZAR -->
<div id="modalVisualizar" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title" id="myModalLabel" align="center">
                    <i class="fa fa-eye"></i> Visualizar
                </h2>
            </div>

            <div class="modal-body tableFix">
                <div align="center">Carregando<a class="loading"></a></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelModal">Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="visualizarTabelaPrecos" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Visualizar Tabela de Preços</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="content-page">

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


    <div class="content">

        <?php

        if ($_GET['c'] != 'dashboard') {

            switch ($this->action) {
                case "listar":
                    $icon = 'glyphicon glyphicon-list-alt';
                    break;
                case "adicionar":
                    $icon = 'icon-plus-2';
                    break;
                case "editar":
                    $icon = 'icon-edit';
                    break;
                case "gerar":
                    $icon = 'icon-doc';
                    break;
                case "imprimir":
                    $icon = 'icon-print-1';
                    break;
                case "contato":
                    $icon = 'icon-user-add';
                    break;
            }

            ?>

            <div class="page-heading">
                <h1>
                    <i class='<?= $icon ?>'></i>
                    <?= isset($_GET['a']) ? ucwords($_GET['a']) : '' ?>
                    <?php if ($this->action == 'gerar') echo '<b>Relatório</b>'; ?>
                    <strong><?= isset($_GET['c']) ? ucwords(ControllerTb::getControllerByNome($_GET['c'])) : '' ?></strong>
                </h1>
            </div>

        <?php } ?>

        <?php if ($_GET['c'] != 'dashboard') { ?>

        <div class="widget">

            <div class="widget-content padding">

                <div class="alert alert-warning alert-dismissable" style="display: none" id="alertAviso">Nenhum
                    registro foi selecionado.
                </div>

                <?php
                }
                ?>
