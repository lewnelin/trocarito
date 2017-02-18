<?php

class ParametroComissao extends Zend_Db_Table_Abstract {

    protected $_name = TB_PARAMETRO_COMISSAO;


    /**
     * Busca parâmetros dos empreendimentos aos quais o usuário tem acesso
     * @param bool $verificarPermissao
     * @return array
     */
    public static function getParametrosUsuario($verificarPermissao = true){

        //Buscando a lista de empreendimentos para o select
        $listaParametros = self::getDefaultAdapter()->select()
            ->from(array('pc' => TB_PARAMETRO_COMISSAO), array('*'));

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario() && $verificarPermissao) {

            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaParametros = $listaParametros->join(array('e' => TB_EMPREENDIMENTO),"pc.id_empreendimento = e.id AND e.id IN({$idsUsuarioEmpreendimento})",'*');
            } else {
                $listaParametros = $listaParametros->join(array('e' => TB_EMPREENDIMENTO),"pc.id_empreendimento = e.id",'*');
            }
        }

        return $listaParametros->order('pc.id_parametro_comissao')->query()->fetchAll();
    }

    /**
     * Busca os parametros de comissão pelo empreendimento
     * @param $idEmpreendimento
     * @return array
     */
    public function getParametroEmpreendimento($idEmpreendimento){
        //Buscando a lista de empreendimentos para o select
        $listaParametros = self::getDefaultAdapter()->select()
            ->from(array('pc' => TB_PARAMETRO_COMISSAO), array('*'))
            ->where('pc.id_empreendimento = ' . $idEmpreendimento)
            ->order('pc.id_parametro_comissao')->query()->fetchAll();

        return $listaParametros;
    }

}