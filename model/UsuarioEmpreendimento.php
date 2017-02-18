<?php

class UsuarioEmpreendimento extends Zend_Db_Table_Abstract {

    protected $_name = TB_USUARIO_EMPREENDIMENTO;

    /**
     *
     * @param Db_Usuario $usuario
     * @return Array $id_empreencimento
     */
    public function listaEmpreendimentosRepresentante(Db_Usuario $usuario) {
        $id = array();

        try {

            $repres[] = (INT) $usuario->getId();

            $bind = implode(',',$repres);

            $selectParceria = $this->getDefaultAdapter();
            $ids_empreendimentos = $selectParceria->select()->distinct('id_empreendimento')
                ->from(TB_USUARIO_EMPREENDIMENTO, 'id_empreendimento')
                ->where("id_pessoa_fisica IN({$bind})")
                ->query()->fetchAll();

            if(count($ids_empreendimentos)) {
                foreach ($ids_empreendimentos AS $id_empreendimento) {
                    $id[] = (int)$id_empreendimento['id_empreendimento'];
                }
            }
        } catch (Exception $e) {

        }

        return $id;
    }

    public function getAllUsuariosEmpreendimento($where = null) {

        if(! empty($where)){
            $where = addslashes($where);
            $consulta = " us.login like '%{$where}%' ".
                " or pe.nm_pessoa like '%{$where}%' ".
                " or em.nm_empreendimento like '%{$where}%' ";
        }else{
            $consulta = ' 1 = 1 ';
        }
        $query = $this->select()->from(TB_USUARIO_EMPREENDIMENTO, '')
            ->setIntegrityCheck(false)
            ->join(array('em' => TB_EMPREENDIMENTO), 'em.id = id_empreendimento', array('empreendimento' => 'nm_empreendimento'))
            ->join(array('pf' => TB_PESSOA_FISICA), 'pf.id_pessoa = id_pessoa_fisica', '')
            ->join(array('pe' => TB_PESSOA), 'pe.id = pf.id_pessoa', array('nome' => 'nm_pessoa'))
            ->join(array('us' => TB_USUARIO), 'us.id = pe.id', array('id', 'login'))->where($consulta);
        return $this->fetchAll($query)->toArray();
    }



}