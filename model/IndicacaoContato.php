<?php

class IndicacaoContato extends Zend_Db_Table_Abstract
{

    protected $_name = TB_INDICACAO_CONTATO;

    /**
     * Retorna todos os contatos das indicações cadastrados
     *
     * @return array
     */
    public function getIndicacoesContatos($id = null)
    {

        $listaContatoIndicacao = $this->getAdapter()->select()
            ->from(array('ic' => TB_INDICACAO_CONTATO), array('*'))
            ->join(array('tc' => TB_AGRUPADA), 'tc.idTabela = 13 AND tc.idCampo = ic.id_tipo_contato', array('nmTipoContato' => 'descricao'))
            ->join(array('i' => TB_INDICACAO), 'i.id_indicacao = ic.id_indicacao', array('*', 'nrTelefoneIndicado' => 'nr_telefone'))
            ->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'))
            ->joinLeft(array('pif' => TB_PESSOA_FISICA), 'pif.id_pessoa = i.id_cliente', array('nr_cpf'))
            ->joinLeft(array('pij' => TB_PESSOA_JURIDICA), 'pij.id_pessoa = i.id_cliente', array('nr_cnpj'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento', 'id'))
            ->join(array('u' => TB_USUARIO), 'u.id = ic.id_usuario', 'login')
            ->join(array('p' => TB_PESSOA), 'p.id = u.id', array('nmPessoaUsuario' => 'nm_pessoa'))
            ->where('i.fl_status = "1"');

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario()) {
            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaContatoIndicacao->where("e.id IN({$idsUsuarioEmpreendimento})");
            }
        }

        if ($id)
            $listaContatoIndicacao->where('i.id_indicacao = ' . $id);

        $listaContatoIndicacao = $listaContatoIndicacao->order('id_indicacao_contato DESC')
            ->query()->fetchAll();

        return $listaContatoIndicacao;

    }


    /**
     * Para exibição da tela de listar, agrupa os contatos por indicação
     * @param null $id
     * @return array|Zend_Db_Select
     */
    public function getIndicacoesAgrupadasContatos($id = null)
    {

        $listaContatoIndicacao = $this->getAdapter()->select()
            ->from(array('ic' => TB_INDICACAO_CONTATO), null)
            ->join(array('tc' => TB_AGRUPADA), 'tc.idTabela = 13 AND tc.idCampo = ic.id_tipo_contato', array('nmTipoContato' => 'descricao'))
            ->join(array('i' => TB_INDICACAO), 'i.id_indicacao = ic.id_indicacao', array('*', 'nrTelefoneIndicado' => 'nr_telefone'))
            //Pega a ultima para exibir
            ->join(array('icu' => TB_INDICACAO_CONTATO), 'icu.id_indicacao_contato = (SELECT MAX(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', '*')
            ->join(array('tcu' => TB_AGRUPADA), 'tcu.idCampo = icu.id_tipo_contato AND tcu.idTabela = 13', array('dsTipoContato' => 'descricao'))
            ->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'))
            ->joinLeft(array('pif' => TB_PESSOA_FISICA), 'pif.id_pessoa = i.id_cliente', array('nr_cpf'))
            ->joinLeft(array('pij' => TB_PESSOA_JURIDICA), 'pij.id_pessoa = i.id_cliente', array('nr_cnpj'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento', 'id'))
            ->join(array('u' => TB_USUARIO), 'u.id = ic.id_usuario', 'login')
            ->join(array('p' => TB_PESSOA), 'p.id = u.id', array('nmPessoaUsuario' => 'nm_pessoa'))
            ->where('i.fl_status = "1"');

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario()) {
            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaContatoIndicacao->where("e.id IN({$idsUsuarioEmpreendimento})");
            }
        }

        if ($id)
            $listaContatoIndicacao->where('i.id_indicacao = ' . $id);

        $listaContatoIndicacao = $listaContatoIndicacao
            ->group('i.id_indicacao')
            ->query()->fetchAll();

        return $listaContatoIndicacao;

    }

    /**
     * Para exibição da tela de listar do histórico, agrupa os contatos por indicação
     * @param null $id
     * @return array|Zend_Db_Select
     */
    public function getIndicacoesHistorico($id = null)
    {

        $listaContatoIndicacao = $this->getAdapter()->select()
            ->from(array('ic' => TB_INDICACAO_CONTATO), null)
            ->join(array('tc' => TB_AGRUPADA), 'tc.idTabela = 13 AND tc.idCampo = ic.id_tipo_contato', array('nmTipoContato' => 'descricao'))
            ->join(array('i' => TB_INDICACAO), 'i.id_indicacao = ic.id_indicacao', array('*', 'nrTelefoneIndicado' => 'nr_telefone'))
            //Pega a ultima para exibir
            ->join(array('icu' => TB_INDICACAO_CONTATO), 'icu.id_indicacao_contato = (SELECT MAX(id_indicacao_contato) FROM INDICACAO_CONTATO WHERE id_indicacao = i.id_indicacao)', '*')
            ->join(array('tcu' => TB_AGRUPADA), 'tcu.idCampo = icu.id_tipo_contato AND tcu.idTabela = 13', array('dsTipoContato' => 'descricao'))
            ->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'))
            ->joinLeft(array('pif' => TB_PESSOA_FISICA), 'pif.id_pessoa = i.id_cliente', array('nr_cpf'))
            ->joinLeft(array('pij' => TB_PESSOA_JURIDICA), 'pij.id_pessoa = i.id_cliente', array('nr_cnpj'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento', 'id'))
            ->join(array('u' => TB_USUARIO), 'u.id = ic.id_usuario', 'login')
            ->join(array('p' => TB_PESSOA), 'p.id = u.id', array('nmPessoaUsuario' => 'nm_pessoa'))
            ->where('i.fl_status = "0"');

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario()) {
            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaContatoIndicacao->where("e.id IN({$idsUsuarioEmpreendimento})");
            }
        }

        if ($id)
            $listaContatoIndicacao->where('i.id_indicacao = ' . $id);

        $listaContatoIndicacao = $listaContatoIndicacao
            ->group('i.id_indicacao')
            ->query()->fetchAll();

        return $listaContatoIndicacao;

    }

    /**
     * Retorna todos os contatos das indicações no histórico
     *
     * @return array
     */
    public function getIndicacoesContatosHistorico($id = null)
    {

        $listaContatoIndicacao = $this->getAdapter()->select()
            ->from(array('ic' => TB_INDICACAO_CONTATO), array('*'))
            ->join(array('tc' => TB_AGRUPADA), 'tc.idTabela = 13 AND tc.idCampo = ic.id_tipo_contato', array('nmTipoContato' => 'descricao'))
            ->join(array('i' => TB_INDICACAO), 'i.id_indicacao = ic.id_indicacao', array('*', 'nrTelefoneIndicado' => 'nr_telefone'))
            ->joinLeft(array('pi' => TB_PESSOA), 'pi.id = i.id_cliente', array('nmPessoaCliente' => 'nm_pessoa', 'endereco', 'nr_telefone', 'nr_celular', 'nr_fax', 'nr_recado', 'nm_bairro', 'nr_cep'))
            ->joinLeft(array('pif' => TB_PESSOA_FISICA), 'pif.id_pessoa = i.id_cliente', array('nr_cpf'))
            ->joinLeft(array('pij' => TB_PESSOA_JURIDICA), 'pij.id_pessoa = i.id_cliente', array('nr_cnpj'))
            ->join(array('e' => TB_EMPREENDIMENTO), 'e.id = i.id_empreendimento', array('nm_empreendimento', 'id'))
            ->join(array('u' => TB_USUARIO), 'u.id = ic.id_usuario', 'login')
            ->join(array('p' => TB_PESSOA), 'p.id = u.id', array('nmPessoaUsuario' => 'nm_pessoa'))
            ->where('i.fl_status = "0"');

        //verifica se o usuario tem o perfil de usuario de empreendimento
        if (Login::getUsuario()) {
            $instanceUsuarioEmpreendimento = new UsuarioEmpreendimento();
            $idsUsuarioEmpreendimento = $instanceUsuarioEmpreendimento->listaEmpreendimentosRepresentante(Login::getUsuario());

            if ($idsUsuarioEmpreendimento) {
                $idsUsuarioEmpreendimento = implode(',', $idsUsuarioEmpreendimento);
                $listaContatoIndicacao->where("e.id IN({$idsUsuarioEmpreendimento})");
            }
        }

        if ($id)
            $listaContatoIndicacao->where('i.id_indicacao = ' . $id);

        $listaContatoIndicacao = $listaContatoIndicacao->order('id_indicacao_contato DESC')
            ->query()->fetchAll();

        return $listaContatoIndicacao;

    }

}
