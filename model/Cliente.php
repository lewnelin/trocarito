<?php


class Cliente extends Zend_Db_Table_Abstract {

  protected $_name = TB_CLIENTE;

  /**
   * Executa busca na tabela por cliente
   *
   * @param $where
   * @return null|Zend_Db_Table_Row_Abstract
   */
  public function getCliente(){
    return $this->fetchRow()->toArray();
  }

}
