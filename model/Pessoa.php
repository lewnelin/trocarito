<?php

class Pessoa extends Zend_Db_Table_Abstract
{

    protected $_name = TB_PESSOA;

    /**
     * Lista todas pessoas físicas e jurídicas ativas da tabela de acordo com os parametros passados
     *
     * @param null $tipo : F = trás pessoas fisicas; J = Trás pessoas juridicas; null = Trás os dois
     * @param null $where = adiciona o where na consulta
     * @return array
     */
    public function findPessoas($tipo = null, $where = null)
    {
        $listaPessoas = $this->getDefaultAdapter()->select()
            ->from(array('p' => TB_PESSOA), array('idPessoa' => 'id', 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'));

        if ($tipo == 'F' || !$tipo)
            $listaPessoas->joinLeft(array('pf' => TB_PESSOA_FISICA), 'p.id = pf.id_pessoa', array('nr_cpf'));

        if ($tipo == 'J' || !$tipo)
            $listaPessoas->joinLeft(array('pj' => TB_PESSOA_JURIDICA), 'p.id = pj.id_pessoa', array('nr_cnpj'));

        if ($where)
            $listaPessoas->where($where);

        return $listaPessoas->where('p.fl_status = "1"')->query()->fetchAll();
    }

    /**
     * Pesquisa as pessoas físicas por nome
     *
     * @return array
     */
    public function findPessoasByNmpessoa($termo)
    {
        return $this->findPessoas(null, 'p.nm_pessoa LIKE "%' . $termo . '%" OR pf.nr_cpf LIKE "%' . $termo . '%" OR pj.nr_cnpj LIKE "%' . $termo . '%" ');
    }

    /**
     * Pesquisa as pessoas físicas por id
     *
     * @return array
     */
    public function findPessoasById($id)
    {
        return $this->findPessoas(null, 'p.id ="' . $id . '"');
    }


    /**
     * Busca os dados contratuais de pessoas físicas por empreendimento e com parametros informados
     * @param $idEmpreendimento
     * @param null $where
     * @return array
     */
    public function findPessoasFisicasDadosByEmpreendimento($where = null)
    {
        $listaPessoas = $this->getDefaultAdapter()->select()
            ->from(array('p' => TB_PESSOA), array('idPessoa' => 'id', 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'cd_cidade', 'email', 'dt_cadastro'))
            ->joinLeft(array('u' => TB_USUARIO), 'p.id = u.id', null)
            ->joinLeft(array('pf' => TB_PESSOA_FISICA), 'p.id = pf.id_pessoa', array('nr_cpf'))
            ->joinLeft(array('pj' => TB_PESSOA_JURIDICA), 'p.id = pj.id_pessoa', array('nr_cnpj'))
            ->joinLeft(array('c' => TB_CONTRATO), 'p.id = c.id_pessoa', array('idContrato' => 'id', 'dt_contrato', 'fl_distrato'))
            ->joinLeft(array('d' => TB_DISTRATO), 'c.id = d.id_contrato', array('dt_distrato'))
            ->joinLeft(array('l' => TB_LOTES), 'l.id = c.id_lote', array('lote', 'quadra'))
            ->joinLeft(array('e' => TB_EMPREENDIMENTO), 'l.id_empreendimento = e.id', array('idEmpreendimento' => 'id'))
            ->joinLeft(array('cid' => TB_CIDADE), 'cid.id = p.cd_cidade', array('nm_cidade' => 'nome', 'uf'))
            ->where('u.id IS NULL');

        if ($where)
            $listaPessoas->where($where);

        return $listaPessoas->order(array('nm_pessoa', 'dt_contrato DESC'))->query()->fetchAll();
    }

    /**
     * Faz o insert da pessoa na tabela
     *
     * @param $dados = array onde a chave e o nome do campo e o valor da chave o valor do campo
     * @return mixed = retorna o id da pessoa
     */
    public function savePessoa($dados)
    {

        $pessoa = $this->createRow();

        foreach ($dados as $campo => $valor) {
            $pessoa->$campo = $valor;
        }

        return $pessoa->save();

    }

    //Busca o nome de uma pessoa pelo Id
    public static function findNomeById($id){
        $pessoa = self::getDefaultAdapter()->select()
            ->from(array('p' => TB_PESSOA), array('nm_pessoa'))
            ->where('id = ?', $id)
            ->query()->fetch();

        return $pessoa['nm_pessoa'];
    }

}

