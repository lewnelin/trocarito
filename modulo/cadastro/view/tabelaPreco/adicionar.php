<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaEmpreendimentos = $this->get('listaEmpreendimentos');

?>
<fieldset>
    <form id="adicionarTabelaPrecoLotes" enctype="multipart/form-data" class="form" role="form" method="POST"
          action="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=<?php echo $_GET['a']; ?>">
        <fieldset>
            <legend>Importar Lotes</legend>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-6" style="max-width: 50%">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <!-- Selecionar empreendimento. Se houver mudanças na tabela, perguntar se o usuário tem certeza
                    que quer trocar de empreendimento e descartar todas as mudanças-->
                                <label for="Empreendimento" class="control-label cl-xs-3">Empreendimento:<span
                                        class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <select name="Empreendimento" id="Empreendimento" class="form-control"
                                        title="Escolha o Empreendimento" emptyText="Escolha o Empreendimento"
                                        useEmpty="true">
                                    <?php
                                    Helper::geraOptionsSelect($listaEmpreendimentos, 'id', 'nm_empreendimento');
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <br>
                        <span class="file-input-wrapper">
                            <input type="file" id="arquivoCadastro" disabled name="arquivoCadastro"
                                   class="btn btn-default"
                                   title="Selecione o arquivo">
                        </span>
                            </div>
                        </div>
                        <div class="row" id="mensagemDiv">
                            <div class="col-sm-12">
                                <div class="form-group col-sm-12 alert-warning">
                                    <span class="has-error" id="erroImportar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6" style="max-width: 50%">
                        <label>
                            1 - Adicionar apenas arquivo com extensão .xls, .xlsx ou csv<br>
                            2 - Não adicionar arquivo protegido por senha<br>
                            3 - Não adicionar arquivo compactado (zip, rar)<br>
                            4 - Excluir o cabeçalho do arquivo, deixar apenas os dados<br>
                            5 - Padrão: <br>
                            <table class="tableFix table-bordered" style="font-size: smaller">
                                <tr>
                                    <td>Quadra</td>
                                    <td>Lote</td>
                                    <td>Valor Lote</td>
                                    <td>Valor Sinal</td>
                                    <td>Valor Parcela</td>
                                    <td>Valor intercalada</td>
                                    <td>Quant. intercaladas</td>
                                    <td>Data de Atualização</td>
                                </tr>
                            </table>
                        </label>
                    </div>
                </div>
            </div>
            <legend>Tabela de Preços</legend>

            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-6">
                        <div class="form-group">

                            <label for="nm_tabela" class="control-label cl-xs-3">Nome da tabela<span class="text-danger"
                                                                                                     title="Este campo é obrigatório">*</span></label>
                            <input type="text" obrigatorio="true" nmFormatado="Nome da Tabela"
                                   class="form-control cl-xs-3"
                                   id="nm_tabela" name="nm_tabela"
                                   value="" placeholder="Nome da tabela" required><br>
                            <label for="nm_tabela" class="control-label cl-xs-3">Tabela Padrão</label><br>
                            <input type="checkbox" checked class="ios-switch ios-switch-success ios-switch-sm" name="fl_padrao" id="fl_padrao"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="ds_tabela" class="control-label cl-xs-3">Descrição da tabela</label>

                            <textarea class="form-control cl-xs-3" id="ds_tabela" name="ds_tabela"
                                      value=""
                                      placeholder="Descrição da tabela" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <div class="form-group">
            <legend><strong>Operações</strong></legend>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-2">
                            <input type="submit" id="importar" class="btn btn-primary col-sm-12" disabled
                                   value="Salvar"/>
                        </div>
                        <div class="col-sm-2">
                            <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar"
                               class="btn btn-danger col-sm-12">
                                Cancelar </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</fieldset>
<?php require_once 'layout/includes/footer.php'; ?>
