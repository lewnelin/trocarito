<?php

class Empreendimento extends Zend_Db_Table_Abstract
{

    protected $_name = TB_EMPREENDIMENTO;

    /**
     * Lista todas quadras de um empreendimento
     *
     * @param integer $id
     * @return array
     */
    public function listaQuadraEmpreendimento($idEmpreendimento = false)
    {
        if ($idEmpreendimento) {
            $idEmpreendimento = $this->getDefaultAdapter()->select()
                ->from(array('e' => TB_EMPREENDIMENTO), array('*'))
                ->join(array('l' => TB_LOTES), 'l.id_empreendimento = e.id', array('*'))
                ->where('e.id = ' . $idEmpreendimento)
                ->group('l.quadra')
                ->order('l.quadra')
                ->query()->fetchAll();
        }
        return $idEmpreendimento;
    }


    /**
     * Lista todas as tabelas de preço do empreendimento começando pela padrao
     * @param bool $idEmpreendimento
     * @return array|bool
     */
    public function listaTabelaPrecoEmpreendimento($idEmpreendimento = false)
    {
        if ($idEmpreendimento) {
            $idEmpreendimento = $this->getDefaultAdapter()->select()
                ->from(array('tp' => TB_TABELA_PRECO), array('*'))
                ->where('tp.id_empreendimento = ' . $idEmpreendimento)
                ->order('tp.fl_padrao ASC')
                ->query()->fetchAll();
        }
        return $idEmpreendimento;
    }

    /**
     * Busca as pessoas em parceria pelo id do empreendimento
     * @param $idEmp
     * @return array
     */
    public static function getParcerias($idEmp)
    {
        $parcerias = self::getDefaultAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('*'))
            ->join(array('parc' => TB_PARCERIA), 'parc.id_empreendimento = e.id', array('*'))
            ->join(array('p' => TB_PESSOA), 'parc.id_pessoa = p.id', array('*', 'tipoPessoa' => 'p.tp_pessoa'))
            ->joinLeft(array('cid' => TB_CIDADE), 'p.cd_cidade = cid.id', array('cidade' => 'CONCAT(cid.nome, " - ", cid.uf)'))
            ->joinLeft(array('pf' => TB_PESSOA_FISICA), 'pf.id_pessoa = p.id', '*')
            ->joinLeft(array('pj' => TB_PESSOA_JURIDICA), 'pj.id_pessoa = p.id', '*')
            ->where('e.id = ' . $idEmp)
            ->query()->fetchAll();

        return $parcerias;
    }

    /**
     * Retorna uma lista dos empreendimentos associados ao usuário atual
     *
     * @return array = lista ed empreendimentos
     *
     */
    public function getEmpreendimentosUsuario($verificarPermissao = true)
    {

        //Buscando a lista de empreendimentos para o select
        $listaEmpreendimentos = $this->getDefaultAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('id', 'nm_empreendimento'));

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario() && $verificarPermissao) {

            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaEmpreendimentos = $listaEmpreendimentos->where("e.id IN({$idsUsuarioEmpreendimento})");
            }

        }

        return $listaEmpreendimentos->order('e.nm_empreendimento')->query()->fetchAll();


    }

    /**
     * Retorna os empreendimentos que não possuem logo cadastrado do usuário
     * @param bool $verificarPermissao
     * @return array
     */
    public function getEmpreendimentosUsuarioLogo($verificarPermissao = true)
    {
        //Buscando a lista de empreendimentos para o select
        $listaEmpreendimentos = $this->getDefaultAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('id', 'nm_empreendimento'))
            ->joinLeft(array('le' => TB_LOGO_EMPREENDIMENTO), 'e.id = le.id_empreendimento');

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario() && $verificarPermissao) {

            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaEmpreendimentos = $listaEmpreendimentos->where("e.id IN({$idsUsuarioEmpreendimento})");
            }

        }
        $listaEmpreendimentos = $listaEmpreendimentos->order('e.nm_empreendimento')->query()->fetchAll();

        $aux = array();
        foreach ($listaEmpreendimentos as &$empreendimento) {
            if ($empreendimento['id_logo_empreendimento'] == null) {
                $aux[] = $empreendimento;
            }
        }

        return $aux;


    }

    /**
     * Retorna os empreendimentos que não possuem parametro de comissao cadastrado do usuário
     * @param bool $verificarPermissao
     * @return array
     */
    public function getEmpreendimentosUsuarioParametro($verificarPermissao = true)
    {
        //Buscando a lista de empreendimentos para o select
        $listaEmpreendimentos = $this->getDefaultAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('id', 'nm_empreendimento'))
            ->joinLeft(array('pc' => TB_PARAMETRO_COMISSAO), 'e.id = pc.id_empreendimento');

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario() && $verificarPermissao) {

            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaEmpreendimentos = $listaEmpreendimentos->where("e.id IN({$idsUsuarioEmpreendimento})");
            }

        }
        $listaEmpreendimentos = $listaEmpreendimentos->order('e.nm_empreendimento')->query()->fetchAll();

        //Verifica se o empreendimento possui 2 tipos de comissão
        $aux = array();
        foreach ($listaEmpreendimentos as $i => &$empreendimento) {
            if ($empreendimento['id_parametro_comissao'] != null) {
                if (in_array($empreendimento['id'], $aux)) {
                    //Remove os 2 ultimos registros (O atual e o anterior que também possui parametro de comissao)
                    unset($listaEmpreendimentos[$i]);
                    unset($listaEmpreendimentos[$i - 1]);
                } else {
                    $aux[] = $empreendimento['id'];
                }
            }
        }

        return $listaEmpreendimentos;
    }

    /**
     * Retorna os empreendimentos que não possuem parametro de comissao cadastrado do usuário
     * @param bool $verificarPermissao
     * @return array
     */
    public static function getNomeById($id)
    {

        //Buscando a lista de empreendimentos para o select
        $empreendimento = self::getDefaultAdapter()->select()
            ->from(array('e' => TB_EMPREENDIMENTO), array('id', 'nm_empreendimento'))
            ->where("e.id ={$id}")->order('e.nm_empreendimento')->query()->fetch();

        return $empreendimento['nm_empreendimento'];
    }

    /**
     * Pesquisa todos os corretores associados ao empreendimento
     *
     * @param $idEmpreendimento = id do empreendimento
     * @return array = retorna lista com id e nome de todos corretores
     */
    public function findCorretoresByEmp($idEmpreendimento)
    {
        return $this->getAdapter()->select()->from(array('c' => TB_CORRETOR_EMPREENDIMENTO), '')
            ->join(array('p' => TB_PESSOA), 'c.id_corretor = p.id', array('id', 'nm_pessoa'))
            ->where('c.id_empreendimento = ' . $idEmpreendimento)
            ->order('p.nm_pessoa')->query()->fetchAll();
    }

}