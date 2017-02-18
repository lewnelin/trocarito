<?php

class PessoaFisica extends Zend_Db_Table_Abstract {


    protected $_name = TB_PESSOA_FISICA;

    /**
     * Pesquisa uma pessoa fisica  atrav�s do where
     * @param $where
     * @return mixed
     */
    private function findPessoaFisica($where)
    {
        return $this->getAdapter()->select()
            ->from(array('p' => TB_PESSOA), array('*', 'idPessoa'=>'id'))
            ->join(array('pf' => TB_PESSOA_FISICA),'pf.id_pessoa = p.id', array('*'))
            ->joinLeft(array('c' => TB_CIDADE),'c.id = p.cd_cidade', array('nm_cidade'=>'nome'))
            ->where('p.fl_status = "1"')
            ->where($where)
            ->query()->fetch();
    }

    /**
     * Lista todas pessoas Físicas
     * @return where = where para ser adicionado na consulta
     * @return array = retorna a lista de todas pessoa fisicas
     */
    public function findPessoasFisicas($where = null)
    {
        $listaPessoasFisicas = $this->getDefaultAdapter()->select()
            ->from(array('p' => TB_PESSOA), array('*', 'idPessoa'=>'id'))
            ->join(array('pf' => TB_PESSOA_FISICA), 'p.id = pf.id_pessoa', array('*'));

        if ($where)
            $listaPessoasFisicas->where($where);

        return $listaPessoasFisicas->query()->fetchAll();
    }

    /**
     * Pesquisa as pessoas físicas por nome
     *
     * @param $termo = letras que vão usadas para pesquisar os nomes das pessoas fisicas
     * @return mixed = lista de pessoas
     */
    public function findPessoasFisicasByNmPessoa($termo)
    {
        return $this->findPessoasFisicas('p.nm_pessoa LIKE "%'.$termo.'%" OR pf.nr_cpf LIKE "%'.$termo.'%"');
    }

    /**
     * Pesquisa a pessoa fisica de acordo com o cpf
     *
     * @param $cpf = cpf da pessoa fisica
     * @return mixed = retorna um array com as informacoes
     */
    public function findPessoaFisicaByCpf($cpf)
    {
        return self::findPessoaFisica('pf.nr_cpf = "' . $cpf . '" AND p.fl_status = "1"');
    }

    /**
     * Pesquisa a pessoa fisica de acordo com o cpf
     *
     * @param $id = id da pessoa fisica
     * @return mixed = retorna um array com as informacoes
     */
    public function findPessoaFisicaById($id)
    {
        return self::findPessoaFisica('pf.id_pessoa = '.$id);
    }

    /**
     * Faz o insert/update da pessoa f�sica na tabela
     *
     * @param $dados = array onde a chave e o nome do campo e o valor da chave o valor do campo
     * @param null $idPessoaFisica = id da pessoa fisica, caso seja um update
     * @return mixed = retorna o id da pessoa
     */
    public function savePessoaFisica($dados, $idPessoaFisica = null) {

        $pessoaFisica = $this->createRow();

        if ($idPessoaFisica) {
            $pessoaFisica = $this->fetchRow('id_pessoa = ' . $idPessoaFisica);
        }

        foreach ($dados as $campo => $valor) {
            $pessoaFisica->$campo = $valor;
        }

        return $pessoaFisica->save();

    }

}
