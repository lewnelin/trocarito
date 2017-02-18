<?php

$maximo = '';
$ok = false;

function atualiza($db){
    global $maximo, $ok;
    $server = "localhost";
    $user = "ecobranc_lotear";
    $passwd = "acadesuporte2020";
    $database = $db;

    $conn = mysql_connect($server, $user, $passwd);
    mysql_select_db($db,$conn);
    
    //vê se o id está disponível
    $rs = mysql_query("select id from CONTRATO where id = {$_POST['to']}") or die (mysql_error());
    $contrato = mysql_fetch_array($rs);
    if($contrato['id']){
        //pega o código do lançamento do último registro disponivel +1
        $rs = mysql_query("select c1.id+1 from CONTRATO c1 where (select c2.id from CONTRATO c2 where c2.id = c1.id+1 ) is null and c1.id > 2120")  or die (mysql_error());
        $obj = mysql_fetch_array($rs);
        $maximo = $obj[0];
        
    }else{

        //cria um registro temporario
        mysql_query("insert into CONTRATO (id,id_lote,dt_contrato,nr_parcela,vl_parcela,dt_primeira_parcela,id_pessoa,id_corretor,fl_gerado,id_banco,fl_sinal_agregado)
                     values ('9999',66,'2011-05-12',0,0,'2011-05-12',2,121627,'0',1,'0')")  or die (mysql_error());

        //altera as tabelas para o novo contrato
        mysql_query("update PARCELA set id_contrato = 9999 where id_contrato = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update CONTATO set idContrato = 9999 where idContrato = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update HISTORICO_CARTA set idContrato = 9999 where idContrato = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update HISTORICO_REAJUSTE set idContrato = 9999 where idContrato = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update DISTRATO set id_contrato = 9999 where id_contrato = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update SINAL set idContrato = 9999 where idContrato = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update TRANSFERENCIA set idContrato = 9999 where idContrato = {$_POST['from']}")  or die (mysql_error());

        //altera as tabelas para o id informado
        mysql_query("update CONTRATO set id = {$_POST['to']} where id = {$_POST['from']}")  or die (mysql_error());
        mysql_query("update PARCELA set id_contrato = {$_POST['to']} where id_contrato = 9999")  or die (mysql_error());
        mysql_query("update CONTATO set idContrato = {$_POST['to']} where idContrato = 9999")  or die (mysql_error());
        mysql_query("update HISTORICO_CARTA set idContrato = {$_POST['to']} where idContrato = 9999")  or die (mysql_error());
        mysql_query("update HISTORICO_REAJUSTE set idContrato = {$_POST['to']} where idContrato = 9999")  or die (mysql_error());
        mysql_query("update DISTRATO set id_contrato = {$_POST['to']} where id_contrato = 9999")  or die (mysql_error());
        mysql_query("update SINAL set idContrato = {$_POST['to']} where idContrato = 9999")  or die (mysql_error());
        mysql_query("update TRANSFERENCIA set idContrato = {$_POST['to']} where idContrato = 9999")  or die (mysql_error());

        //apaga o registro temporario
        mysql_query("delete from CONTRATO where id = 9999")  or die (mysql_error());

        $ok = true;

    }
}

if($_POST){
atualiza('ecobranc_lotear',$_POST['from'],$_POST['to']);
}
global $maximo,$ok;

if($maximo){
    echo "Alteração não realizada, id do contrato já existe!<br />
          Sugestão do último registro ".$maximo."<br /><br />";
}
if($ok){
    echo "contrato alterado de {$_POST['from']} para {$_POST['to']} com sucesso!: <br />";
}


?>
<form action="" method="POST">
    
    de: <input type="text" size="5" name="from" /> para: <input type="text" size="5" name="to" /> <input type="submit" value="alterar">

</form>