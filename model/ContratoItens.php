<?php

//Classe de itens relacionadas com o contrato (Tipos de parcelas)
class ContratoItens extends Zend_Db_Table_Abstract
{
    protected $_name = TB_CONTRATO_ITENS;
    static $contratoItensInstancia;

    //retorna uma instancia do objeto para uso de SINGLETON
    public static function getInstance()
    {
        if (!self::$contratoItensInstancia) {
            self::$contratoItensInstancia = new ContratoItens();
        }
        return self::$contratoItensInstancia;
    }

    //Retorna os dados pelo id
    public static function findById($id)
    {
        $contrato_itens = self::getInstance();
        $contrato_itens = $contrato_itens->getDefaultAdapter()->select()->from(array('c' => TB_CONTRATO_ITENS), array('*'))
            ->where('c.id_contrato_itens = ?', $id)->query()->fetch();

        return $contrato_itens;
    }

    //Retorna os contratos_pessoa pelo contrato
    public static function findByContrato($contrato)
    {
        $contrato_itens = self::getInstance();
        $contrato_itens = $contrato_itens->getDefaultAdapter()->select()->from(array('c' => TB_CONTRATO_ITENS), array('*'))
            ->where('c.id_contrato = ?', $contrato)->order('tp_parcela')->query()->fetchAll();

        return $contrato_itens;
    }

    /*
     * Apaga todos os registros na tabela CONTRATO_PESSOA
     * @param: dados (id_contrato)
    */
    public function deleteByContrato($contrato)
    {
        return self::delete("id_contrato = $contrato");
    }

}
