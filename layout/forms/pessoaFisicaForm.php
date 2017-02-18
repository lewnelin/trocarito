<form role="form" action="" method="post" id="registerPessoa">
<input type="hidden" name="tpCadastro" value="PF">
<fieldset>
<legend>Dados Pessoais <span style="font-size: 10px;">(Campos com <span class="text-danger" title="Este campo é obrigatório." style="font-size: 13 px;">*</span> são obrigatórios.)</span></legend>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nmPessoa" class="control-label">Nome: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <input type="text" class="form-control" id="nmPessoa" name="nmPessoa" placeholder="Digite o nome da pessoa." required="required">
        </div>
        <div class="form-group col-sm-6">
            <label for="nrCpf" class="control-label">CPF: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <input type="text" class="form-control maskCpf" id="nrCpf" name="nrCpf" placeholder="Digite o CPF da pessoa." required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nrRg" class="control-label">RG: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <input type="text" class="form-control" id="nrRg" name="nrRg" placeholder="Digite o rg da pessoa." required="required">
        </div>
        <div class="form-group col-sm-6">
            <label for="nmProfissao" class="control-label">Profissão: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <input type="text" class="form-control" id="nmProfissao" name="nmProfissao" placeholder="Digite o nome da profissão." required="required">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nmNacionalidade" class="control-label">Nacionalidade: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <input type="text" class="form-control" id="nmNacionalidade" name="nmNacionalidade" placeholder="Digite nacionalidade da pessoa." required="required">
        </div>
        <div class="form-group col-sm-6">
            <label for="cdCidadeNascimento" class="control-label">Cidade de Nascimento: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <br><select class="form-control select2BigData" name="cdCidadeNascimento" required="required" style="width:382px;">
                <?php Helper::geraOptionsSelect($this->get("listaCidades"), 'id', array('[nome]/[uf]',array('nome','uf'))); ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="estCivil" class="control-label">Estado Civil: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <br><select class="form-control" name="estCivil" required="required" style="width:382px;">
                <?php Helper::geraOptionsSelect($this->get("listaEstadoCivil"), 'idCampo', 'descricao'); ?>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="sexo" class="control-label">Sexo:</label><br />
            <?php
            foreach($this->get('listaSexo') as $sexo) {
                $idSexo = 'sexo'.$sexo['idCampo'];
                $checked = ($sexo['idCampo'])?'checked':'';
                ?>
                <label for="<?= $idSexo?>" class="control-label" style="padding-top: 8px; cursor:pointer;">
                    <?= $sexo['descricao']?> <input type="radio" id="<?= $idSexo?>" name="sexo" value="<?= $sexo['idCampo']?>" <?= $checked?>/>
                </label>
            <?php
            }
            ?>
        </div>
    </div>
</div>

<!-- CONJUGE -->
<hr class="infoConjuge">

<div class="row infoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6" id="divNmConjuge">
            <label for="nrCpfConjuge" class="control-label">CPF do Cônjuge: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <input type="text" class="form-control maskCpf" name="nrCpfConjuge" placeholder="Digite o CPF do cônjuge.">
        </div>
        <div class="form-group col-sm-1 divBtnLimparConjuge" style="display:none">
            <span class="input-group-btn btnLimparConjuge" style="padding-top: 24px;">
                <button tabindex="-1" style="height: 27px" class="btn btn-primary btn-sm" type="button" data-toggle="tooltip" data-original-title="Limpar Campos Cônjuge">
                    <i class="icon-erase"></i>
                </button>
            </span>
        </div>
        <div class="form-group col-sm-6">
            <label for="nmPessoaConjuge" class="control-label">Nome do Cônjuge:</label>
            <input type="text" class="form-control" name="nmPessoaConjuge" placeholder="Digite o nome do cônjuge.">
        </div>
    </div>
</div>
<div class="row infoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nrRgConjuge" class="control-label">RG do Cônjuge:</label>
            <input type="text" class="form-control" name="nrRgConjuge" placeholder="Digite o rg do cônjuge.">
        </div>
        <div class="form-group col-sm-6">
            <label for="dtNascimentoConjuge" class="control-label">Data de Nascimento do Cônjuge:</label>
            <input type="text" class="form-control dtpicker" name="dtNascimentoConjuge" placeholder="Digite a data de nascimento do cônjuge.">
        </div>
    </div>
</div>
<div class="row infoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nmProfissaoConjuge" class="control-label">Profissão do Cônjuge:</label>
            <input type="text" class="form-control" name="nmProfissaoConjuge" placeholder="Digite nacionalidade do cônjuge.">
        </div>
        <div class="form-group col-sm-6">
            <label for="nmNacionalidadeConjuge" class="control-label">Nacionalidade do Cônjuge:</label>
            <input type="text" class="form-control" name="nmNacionalidadeConjuge" placeholder="Digite nacionalidade do cônjuge">
        </div>
    </div>
</div>
<div class="row infoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nrTelefoneConjuge" class="control-label">Telefone do Cônjuge:</label>
            <input type="text" class="form-control maskTelefone" name="nrTelefoneConjuge" placeholder="Digite o telefone do cônjuge">
        </div>
        <div class="form-group col-sm-6">
            <label for="sexoConjuge" class="control-label" style="cursor:pointer;">Sexo do Cônjuge:</label><br />
            <?php
            foreach($this->get('listaSexo') as $sexo) {
                $idSexo = 'sexoConjuge'.$sexo['idCampo'];
                $checked = ($sexo['idCampo'])?'checked':'';
                ?>
                <label for="<?= $idSexo?>" class="control-label" style="padding-top: 8px;  cursor:pointer;">
                    <?= $sexo['descricao']?> <input type="radio" id="<?= $idSexo?>" name="sexoConjuge" value="<?= $sexo['idCampo']?>" <?= $checked?>/>
                </label>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div class="row infoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label class="control-label">Utilizar o mesmo endereço da Pessoa Física?</label><br />
            <label for="utilizarEnderecoConjugeS" class="control-label" style="padding-top: 8px; cursor:pointer;">
                Sim <input type="radio" id="utilizarEnderecoConjugeS" name="utilizarEnderecoConjuge" value="1" checked/>
            </label>
            <label for="utilizarEnderecoConjugeN" class="control-label" style="padding-top: 8px; cursor:pointer;">
                Não <input type="radio" id="utilizarEnderecoConjugeN" name="utilizarEnderecoConjuge" value="0"/>
            </label>
        </div>
    </div>
</div>

<!-- ENDERECO CONJUGE -->
<div class="row enderecoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nrCepConjuge" class="control-label">CEP do Cônjuge:</label>
            <input type="text" class="form-control maskCep" name="nrCepConjuge" placeholder="Digite o CEP do cônjuge.">
        </div>
        <div class="form-group col-sm-6">
            <label for="enderecoConjuge" class="control-label">Endereço do Cônjuge:</label>
            <input type="text" class="form-control" name="enderecoConjuge" placeholder="Digite o endereço do cônjuge.">
        </div>
    </div>
</div>
<div class="row enderecoConjuge">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nmBairroConjuge" class="control-label">Bairro do Cônjuge:</label>
            <input type="text" class="form-control" name="nmBairroConjuge" placeholder="Digite o bairro do cônjuge.">
        </div>
        <div class="form-group col-sm-6">
            <label for="cdCidadeConjuge" class="control-label">Cidade do Conjuge:</label>
            <br><select class="form-control select2BigData" name="cdCidadeConjuge" style="width:382px;">
                <?php Helper::geraOptionsSelect($this->get("listaCidades"), 'id', array('[nome]/[uf]',array('nome','uf'))); ?>
            </select>
        </div>
    </div>
</div>

<hr class="infoConjuge">
<!-- FIM DE CONJUGE -->

<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="dtNascimento" class="control-label">Data de Nascimento: </label>
            <input type="text" class="form-control dtpicker" name="dtNascimento" placeholder="Digite data de nascimento da pessoa" size="100">
        </div>
        <div class="form-group col-sm-6">
            <label for="email" class="control-label">Email:</label>
            <input type="text" class="form-control" name="email" placeholder="Digite o email da pessoa">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nrTelefone" class="control-label">Nº do Telefone: </label>
            <input type="text" class="form-control maskTelefone" name="nrTelefone" placeholder="Digite o telefone da pessoa">
        </div>
        <div class="form-group col-sm-6">
            <label for="nrCelular" class="control-label">Nº do Celular: </label>
            <input type="text" class="form-control maskTelefone" name="nrCelular" placeholder="Digite o número do celular da pessoa">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nrRecado" class="control-label">Nº Tel. Recado: </label>
            <input type="text" class="form-control maskTelefone" name="nrRecado" placeholder="Digite o número de telefone para recado da pessoa">
        </div>
        <div class="form-group col-sm-6">
            <label for="nrCep" class="control-label">CEP:<span class="text-danger" title="Este campo é obrigatório.">*</span> </label>
            <input type="text" class="form-control maskCep" name="nrCep" placeholder="Digite o cep da pessoa" required="required">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="endereco" class="control-label">Endereço:<span class="text-danger" title="Este campo é obrigatório.">*</span> </label>
            <input type="text" class="form-control" name="endereco" placeholder="Digite o endereço da pessoa" required="required">
        </div>
        <div class="form-group col-sm-6">
            <label for="nmBairro" class="control-label">Bairro:<span class="text-danger" title="Este campo é obrigatório.">*</span> </label>
            <input type="text" class="form-control" name="nmBairro" placeholder="Digite o bairro da pessoa">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="cdCidade" class="control-label">Cidade:<span class="text-danger" title="Este campo é obrigatório.">*</span></label>
            <br><select class="form-control select2BigData" name="cdCidade" required="required" style="width:382px;">
                <?php Helper::geraOptionsSelect($this->get("listaCidades"), 'id', array('[nome]/[uf]',array('nome','uf'))); ?>
            </select>
        </div>
        <div class="form-group col-sm-6">
            <label for="nmPai" class="control-label">Nome do Pai: </label>
            <input type="text" class="form-control" name="nmPai" placeholder="Digite o nome do pai da pessoa">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group col-sm-6">
            <label for="nmMae" class="control-label">Nome da Mãe:</label>
            <input type="text" class="form-control" name="nmMae" placeholder="Digite o nome da mãe da pessoa">
        </div>
        <div class="form-group col-sm-6">
            <label for="dsObservacao" class="control-label">Observação: </label>
            <textarea class="form-control" name="dsObservacao" placeholder="Digite a observação"></textarea>
        </div>
    </div>
</div>
</fieldset>

<!--<fieldset>-->
<!--    <legend>Documento(s) da Pessoa Física</legend>-->
<!---->
<!--    <div class="row" >-->
<!--        <div class="col-sm-12">-->
<!--            <div class="form-group col-sm-12">-->
<!--                <span class="input-group-btn" id="btnAddCampoArquivo">-->
<!--                    <button tabindex="-1" style="height: 33px" class="form-control btn btn-default btn-sm" type="button">-->
<!--                        <i class="icon-doc-new"></i> Adicionar outro documento-->
<!--                    </button>-->
<!--                </span>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="row" id="divDocumento">-->
<!--        <div class="col-sm-12 divDocs">-->
<!--            <div class="form-group col-sm-6">-->
<!--                <label for="tpDocumento" class="control-label">Tipo de Documento: </label>-->
<!--                <br><select class="form-control" name="tpDocumento[]" style="width:382px;">-->
<!--                    --><?php //Helper::geraOptionsSelect($this->get("listaTipoDocumento"), 'idCampo', 'descricao'); ?>
<!--                </select>-->
<!--            </div>-->
<!--            <div class="form-group col-sm-5">-->
<!--                <label for="arquivoDocumento" class="control-label">Arquivo do Documento:</label>-->
<!--                <input type="file" class="form-control btn btn-primary" id="arquivoDocumento" name="arquivoDocumento[]" title='Escolher arquivo'/>-->
<!--            </div>-->
<!--            <div class="form-group col-sm-1">-->
<!--                <label class="control-label">Excluir</label>-->
<!--                <button tabindex="-1" style="height: 35px" class="form-control btn btn-red-1 btn-sm btnExcluirDocumento" type="button">-->
<!--                    <i class="icon-trash"></i>-->
<!--                </button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</fieldset>-->

<fieldset>
    <legend>Operações</legend>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group col-sm-6">
                <button type="button" class="form-control btn btn-primary" id="btnSalvar">Salvar</button>
            </div>
            <div class="form-group col-sm-6">
                <button type="button" class="form-control btn btn-primary" id="buttonBack">Cancelar</button>
            </div>
        </div>
    </div>
</fieldset>
</form>