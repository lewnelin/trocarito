<?php
class ACL extends Zend_Db_Table_Abstract {

    protected $_name = "SC_ACL";

    public static function hasPermission($usuario, $action){
        if(!$usuario instanceof Db_Usuario) $usuario = Db_Usuario::find($usuario);
        if($usuario->getSuper() == '1') return true;
        $perfil = Db_Perfil::find($usuario->getPerfilId());
        return (Db_ACL::find($perfil,$action) instanceof Db_ACL);
    }
}