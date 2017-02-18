<?php

class PessoaJuridica extends Zend_Db_Table_Abstract {


    protected $_name = TB_PESSOA_JURIDICA;

    /**
     * Pesquisa uma pessoa juridica através do where recebido
     * @param $where
     * @return mixed
     */
    private function findPessoaJuridica($where)
    {
        return $this->getAdapter()->select()
            ->from(array('p' => TB_PESSOA), array('*'))
            ->join(array('pj' => TB_PESSOA_JURIDICA),'pj.id_pessoa = p.id', array('*'))
            ->joinLeft(array('c' => TB_CIDADE),'c.id = p.cd_cidade', array('nm_cidade'=>'nome'))
            ->where($where)
            ->query()->fetch();
    }

    /**
     * Faz o insert/update da pessoa juridica na tabela
     *
     * @param $dados = array onde a chave e o nome do campo e o valor da chave o valor do campo
     * @param null $idPessoaJuridica = id da pessoa juridica, caso seja um update
     * @return mixed = retorna o id da pessoa
     */
    public function savePessoaJuridica($dados, $idPessoaJuridica = null) {

        $pessoaJuridica = $this->createRow();

        if ($idPessoaJuridica) {
            $pessoaJuridica = $this->fetchRow('id_pessoa = ' . $idPessoaJuridica);
        }

        foreach ($dados as $campo => $valor) {
            $pessoaJuridica->$campo = $valor;
        }

        return $pessoaJuridica->save();

    }

    /**
     * Pesquisa a pessoa fisica de acordo com o cpf
     *
     * @param $cpf = cpf da pessoa fisica
     * @return mixed = retorna um array com as informacoes
     */
    public function findPessoaJuridicaByCnpj($cnpj)
    {
        return self::findPessoaJuridica('pj.nr_cnpj = "' . $cnpj . '" AND p.fl_status = "1"');
    }

}
