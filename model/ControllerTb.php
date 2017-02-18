<?php

class ControllerTb extends Zend_Db_Table_Abstract
{

    protected $_name = TB_CONTROLLER;

    static public function getControllerByNome($nome)
    {
        $controller = self::getDefaultAdapter()->select()
            ->from(array('c' => TB_CONTROLLER), array('*'))
            ->where('c.nome = "' . $nome . '"')
            ->query()->fetch();

        return $controller['rotulo'];
    }
}
