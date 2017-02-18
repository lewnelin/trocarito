<?php
if (Login::isLogado()) {
    $this->redir(array("m" => "dashboard", "controller" => "dashboard", 'action' => 'painelVenda'));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Painel de Vendas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="description" content="">
    <meta name="keywords" content="acadeone, acadelotear, acadefinan, acade, acadesoftwares, acadefacil, acadepro, softwares, loteamento, gestao de carteiras, pre moldado, artefatos de cimento, sistema">
    <meta name="author" content="AcadeOne Softwares Ltda">

    <!-- Base Css Files -->
    <link href="public/libs/jqueryui/ui-lightness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet"/>
    <link href="public/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="public/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="public/libs/fontello/css/fontello.css" rel="stylesheet"/>
    <link href="public/libs/animate-css/animate.min.css" rel="stylesheet"/>
    <link href="public/libs/nifty-modal/css/component.css" rel="stylesheet"/>
    <link href="public/libs/magnific-popup/magnific-popup.css" rel="stylesheet"/>
    <link href="public/libs/ios7-switch/ios7-switch.css" rel="stylesheet"/>
    <link href="public/libs/pace/pace.css" rel="stylesheet"/>
    <link href="public/libs/sortable/sortable-theme-bootstrap.css" rel="stylesheet"/>
    <link href="public/libs/bootstrap-datepicker/css/datepicker.css" rel="stylesheet"/>
    <link href="public/libs/jquery-icheck/skins/all.css" rel="stylesheet"/>
    <!-- Code Highlighter for Demo -->
    <link href="public/libs/prettify/github.css" rel="stylesheet"/>

    <!-- Extra CSS Libraries Start -->
    <link href="public/libs/rickshaw/rickshaw.min.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/morrischart/morris.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/jquery-jvectormap/css/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/jquery-clock/clock.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/bootstrap-calendar/css/bic_calendar.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/sortable/sortable-theme-bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/jquery-weather/simpleweather.css" rel="stylesheet" type="text/css"/>
    <link href="public/libs/bootstrap-xeditable/css/bootstrap-editable.css" rel="stylesheet" type="text/css"/>
    <link href="public/css/style.css" rel="stylesheet" type="text/css"/>
    <!-- Extra CSS Libraries End -->
    <link href="public/css/style-responsive.css" rel="stylesheet"/>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="public/img/favicon.ico">
    <link rel="apple-touch-icon" href="public/img/apple-touch-icon.png"/>
    <link rel="apple-touch-icon" sizes="57x57" href="public/img/apple-touch-icon-57x57.png"/>
    <link rel="apple-touch-icon" sizes="72x72" href="public/img/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon" sizes="76x76" href="public/img/apple-touch-icon-76x76.png"/>
    <link rel="apple-touch-icon" sizes="114x114" href="public/img/apple-touch-icon-114x114.png"/>
    <link rel="apple-touch-icon" sizes="120x120" href="public/img/apple-touch-icon-120x120.png"/>
    <link rel="apple-touch-icon" sizes="144x144" href="public/img/apple-touch-icon-144x144.png"/>
    <link rel="apple-touch-icon" sizes="152x152" href="public/img/apple-touch-icon-152x152.png"/>

</head>

<body class="widescreen pace-done fixed-left">

<div id="wrapper">

    <div class="container">

        <div class="full-content-center">
            <p class="text-center">
                <img src="public/img/Acade/acadeone1.png" alt="Logo" height="100">
            </p>
        </div>

        <div class="full-content-center">

            <div class="login-wrap animated flipInX">
                <div class="login-block">
                    <div align="center" style="color: #000000; margin-top: -10px; padding-bottom: 10px">
                        <h3>Painel de <strong>Vendas</strong></h3>
                    </div>
                    <?php if (isset($_GET['msg'])): ?>
                        <div class="alert alert-danger alert-dismissable">
                            <div align="justify">
                                <?= $_GET['msg'] ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <form action="<?php echo $this->_helper->getLink(array("m"=>"dashboard","c"=>"login","a"=>"autenticar"));?>" role='form' method="POST">

                        <div class="form-group login-input">
                            <i class="fa fa-user overlay"></i>
                            <input type="text" id="txt_login" name="txt_login" class="form-control text-input" placeholder="Login">
                        </div>

                        <div class="form-group login-input">
                            <i class="fa fa-key overlay"></i>
                            <input type="password" id="txt_senha" name="txt_senha" class="form-control text-input" placeholder="Senha">
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <input type="submit" name="acessar" value="Entrar" class="btn btn-green-3 btn-block">
                            </div>
                        </div>

                    </form>
                </div>
            </div>


        </div>
    </div>

</div>

</div>

<div id="contextMenu" class="dropdown clearfix" style="display: none;">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
        <li><a tabindex="-1" href="javascript:;" data-priority="high"><i class="fa fa-circle-o text-red-1"></i> High Priority</a></li>
        <li><a tabindex="-1" href="javascript:;" data-priority="medium"><i class="fa fa-circle-o text-orange-3"></i> Medium Priority</a></li>
        <li><a tabindex="-1" href="javascript:;" data-priority="low"><i class="fa fa-circle-o text-yellow-1"></i> Low Priority</a></li>
        <li><a tabindex="-1" href="javascript:;" data-priority="none"><i class="fa fa-circle-o text-lightblue-1"></i> None</a></li>
    </ul>
</div>

<div class="md-overlay"></div>
<script>
    var resizefunc = [];
</script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="public/libs/jquery/jquery-1.11.1.min.js"></script>
<script src="public/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="public/libs/jqueryui/jquery-ui-1.10.4.custom.min.js"></script>
<script src="public/libs/jquery-ui-touch/jquery.ui.touch-punch.min.js"></script>
<script src="public/libs/jquery-detectmobile/detect.js"></script>
<script src="public/libs/jquery-animate-numbers/jquery.animateNumbers.js"></script>
<script src="public/libs/ios7-switch/ios7.switch.js"></script>
<script src="public/libs/fastclick/fastclick.js"></script>
<script src="public/libs/jquery-blockui/jquery.blockUI.js"></script>
<script src="public/libs/bootstrap-bootbox/bootbox.min.js"></script>
<script src="public/libs/jquery-slimscroll/jquery.slimscroll.js"></script>
<script src="public/libs/jquery-sparkline/jquery-sparkline.js"></script>
<script src="public/libs/nifty-modal/js/classie.js"></script>
<script src="public/libs/nifty-modal/js/modalEffects.js"></script>
<script src="public/libs/sortable/sortable.min.js"></script>
<script src="public/libs/bootstrap-fileinput/bootstrap.file-input.js"></script>
<script src="public/libs/bootstrap-select/bootstrap-select.min.js"></script>
<script src="public/libs/bootstrap-select2/select2.min.js"></script>
<script src="public/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="public/libs/pace/pace.min.js"></script>
<script src="public/libs/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="public/libs/jquery-icheck/icheck.min.js"></script>

<!-- Demo Specific JS Libraries -->
<script src="public/libs/prettify/prettify.js"></script>

<script src="public/js/init.js"></script>

</body>

</html>