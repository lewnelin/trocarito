<?php

class Banco extends Zend_Db_Table_Abstract {

    protected $_name = TB_BANCO;

    /**
     * Busca todas as contas do empreendimento
     */
    public function listarContasEmpreendimento($idEmpreendimento)
    {
        return $this->getDefaultAdapter()->select()
            ->from(array('b' => TB_BANCO), array('id','agencia','agencia_dv','conta_corrente','conta_corrente_dv','cd_banco'))
            ->join(array('banco' => TB_AGRUPADA), 'banco.idTabela = 9 AND banco.idCampo = b.cd_banco', array('descricao'))
            ->where('b.id_empreendimento = ' . $idEmpreendimento)
            ->query()->fetchAll();
    }

}

