<form role="form" action="" method="post" id="registerPessoa">
    <input type="hidden" name="tpCadastro" value="PJ">

    <fieldset>
        <legend>Dados da Pessoa <span style="font-size: 10px;">(Campos com <span class="text-danger"
                                                                                 title="Este campo é obrigatório"
                                                                                 style="font-size: 13 px;">*</span> são obrigatórios.)</span>
        </legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="nmPessoa" class="control-label">Razão Social: <span class="text-danger"
                                                                                    title="Este campo é obrigatório">*</span></label>
                    <input type="text" class="form-control" name="nmPessoa" id="nmPessoa"
                           placeholder="Digite a razão social" required="required">
                </div>
                <div class="form-group col-sm-6">
                    <label for="nmFantasia" class="control-label">Nome Fantasia: <span class="text-danger"
                                                                                       title="Este campo é obrigatório">*</span></label>
                    <input type="text" class="form-control" name="nmFantasia" id="nmFantasia"
                           placeholder="Digite o nome fantasia" required="required">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="nrCnpj" class="control-label">CNPJ: <span class="text-danger"
                                                                          title="Este campo é obrigatório">*</span></label>
                    <input type="text" class="form-control maskCnpj" name="nrCnpj" placeholder="Digite o cnpj"
                           required="required">
                </div>
                <div class="form-group col-sm-6">
                    <label for="nrInscricaoEstadual" class="control-label">Inscrição Estadual:</label>
                    <input type="text" class="form-control" name="nrInscricaoEstadual" id="nrInscricaoEstadual"
                           placeholder="Digite o nome da profissão">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="nrTelefone" class="control-label">Telefone:</label>
                    <input type="text" class="form-control maskTelefone" name="nrTelefone" id="nrTelefone"
                           placeholder="Digite o número do telefone">
                </div>
                <div class="form-group col-sm-6">
                    <label for="nrFax" class="control-label">Fax:</label>
                    <input type="text" class="form-control maskTelefone" name="nrFax" id="nrFax"
                           placeholder="Digite o número do fax">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="nrCelular" class="control-label">Celular:</label>
                    <input type="text" class="form-control maskTelefone" name="nrCelular" id="nrCelular"
                           placeholder="Digite o número do celular">
                </div>
                <div class="form-group col-sm-6">
                    <label for="nrCep" class="control-label">CEP:<span class="text-danger"
                                                                       title="Este campo é obrigatório.">*</span>
                    </label>
                    <input type="text" class="form-control maskCep" name="nrCep" id="nrCep"
                           placeholder="Digite o cep da pessoa" required="required">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="endereco" class="control-label">Endereço:<span class="text-danger"
                                                                               title="Este campo é obrigatório.">*</span>
                    </label>
                    <input type="text" class="form-control" name="endereco" id="endereco"
                           placeholder="Digite o endereço da pessoa" required="required">
                </div>
                <div class="form-group col-sm-6">
                    <label for="nmBairro" class="control-label">Bairro:<span class="text-danger"
                                                                             title="Este campo é obrigatório.">*</span>
                    </label>
                    <input type="text" class="form-control" name="nmBairro" id="nmBairro"
                           placeholder="Digite o bairro da pessoa">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-6">
                    <label for="cdCidade" class="control-label">Cidade:<span class="text-danger"
                                                                             title="Este campo é obrigatório.">*</span></label>
                    <br><select class="form-control" name="cdCidade" id="cdCidade" required="required" style="">
                        <?php Helper::geraOptionsSelect($this->get("listaCidades"), 'id', array('[nome]/[uf]', array('nome', 'uf'))); ?>
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label for="dsObservacao" class="control-label">Observação: </label>
                    <textarea class="form-control" name="dsObservacao" id="dsObservacao"
                              placeholder="Digite a observação"></textarea>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Representantes</legend>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-12">
                    <label for="cdRepresentantes" class="control-label">Representantes:<span class="text-danger"
                                                                                             title="Este campo é obrigatório.">*</span></label>
                    <select class="form-control" name="cdRepresentantes[]" id="cdRepresentantes" required="required"
                            style="" multiple="multiple"></select>
                </div>
            </div>
        </div>
    </fieldset>

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