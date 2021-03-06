﻿<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="rotas, onibus, localizacao">

    <title>Rotas e Rumos</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div id="login-page">
    <div class="container">

        <form class="form-login" action="?m=dashboard&c=login&a=index" method="post">
            <h2 class="form-login-heading">Login</h2>
            <div class="login-wrap">
                <?= ($this->get('success'))?$this->get('success'):'' ?>
                <?= ($this->get('login'))?$this->get('login'):'' ?>
                <input type="text" class="form-control" placeholder="Usuário" name="login" autofocus>
                <br>
                <?= ($this->get('senha'))?$this->get('senha'):'' ?>
                <input type="password" class="form-control" placeholder="Senha" name="senha">
                <br>
                <button class="btn btn-success btn-block" href="index.html" type="submit"><i class="fa fa-lock"></i>  Acessar </button>

                <div class="login-social-link centered">
                    <p>ou você pode entrar via rede social</p>
                    <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
                </div>
                <div class="registration">
                    Não possui a conta ainda?<br/>
                    <a class="" href="?m=dashboard&c=login&a=cadastrar">
                        Criar uma conta
                    </a>
                </div>
            </div>

        </form>

    </div>
</div>

<!-- js placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
<script>
    $.backstretch("assets/img/rotasLogin.jpg", {speed: 500});
</script>


</body>
</html>
