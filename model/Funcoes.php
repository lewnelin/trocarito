<?php

//Acesso as funcoes do postgres para calculo das rotas para exibicao
class Funcoes
{
    //Cruza os trechos com os pontos de referencia
    public static function resultado($trecho, $logradouro, $referencia)
    {
        $db = DB::getInstance();
        $select = "SELECT * FROM resultado (seleciona_trecho_logradouro(':trecho',':logradouro')" .
            ", seleciona_trecho_ponto_referencia(':referencia')";
        $stmt = $db->prepare($select);
        $stmt->execute(array("trecho" => $trecho, "logradouro" => $logradouro, "referencia" => $referencia));
        return $stmt->fetch();
    }

    //Busca as paradas mais proximas pelo logradouro
    public static function onibusTrecho($logradouro){
        $db = DB::getInstance();
        $select = "SELECT * FROM busca_simplificada('".$logradouro."')";
        $stmt = $db->prepare($select);
        $stmt->execute();
        return $stmt->fetch();
    }
}