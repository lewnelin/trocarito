<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$tabelaPreco = $this->get('tabelaPreco');
$tb_tb_preco_lote = $this->get('tb_tb_preco_lote');
?>
    <fieldset>
        <form id="editarTabelaPrecoLotes" enctype="multipart/form-data" class="form" role="form" method="POST"
              action="<?php echo '?m=' . $_GET['m'] . '&c=' . $_GET['c'] . '&a=' . $_GET['a'] . "&id=" . $_GET['id']; ?>">
            <fieldset>
                <legend>Importar Lotes</legend>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group col-sm-6" style="max-width: 50%">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <!-- Selecionar empreendimento. Se houver mudanças na tabela, perguntar se o usuário tem certeza
                        que quer trocar de empreendimento e descartar todas as mudanças-->
                                    <label for="Empreendimento" class="control-label cl-xs-3">Empreendimento:<span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                    <select name="Empreendimento" id="Empreendimento" class="form-control"
                                            title="Escolha o Empreendimento" emptyText="Escolha o Empreendimento"
                                            useEmpty="true"
                                            disabled>
                                        <?php echo '<option value="' . $tabelaPreco['id_empreendimento'] . '">' . $tabelaPreco['nm_empreendimento'] . '</option>'; ?>
                                    </select>
                                    <input type="hidden" id="empreendimentoHidden" name="empreendimentoHidden">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <br>
                        <span class="file-input-wrapper">
                            <input type="file" id="arquivoCadastro" name="arquivoCadastro"
                                   class="btn btn-default"
                                   title="Selecione o arquivo">
                        </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-12">
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
                                <input type="hidden" name="id_tabela_preco" value="<?php echo $_GET['id'] ?>">
                                <label for="nm_tabela" class="control-label cl-xs-3">Nome da tabela<span class="text-danger" title="Este campo é obrigatório">*</span></label>
                                <input type="text" obrigatorio="true" nmFormatado="Nome da Tabela"
                                       class="form-control cl-xs-3"
                                       id="nm_tabela" name="nm_tabela"
                                       value="<?php echo $tabelaPreco['nm_tabela'] ?>" placeholder="Nome da tabela"
                                       required><br>
                                <label for="fl_padrao" class="control-label cl-xs-3">Tabela Padrão</label><br>
                                <input type="checkbox" class="ios-switch ios-switch-success fl_padrao ios-switch-sm <?php echo ($tabelaPreco['fl_padrao'] == 1) ? 'on' : 'off' ?>" name="fl_padrao" id="fl_padrao"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="ds_tabela" class="control-label cl-xs-3">Descrição da tabela</label>

                                <textarea class="form-control cl-xs-3" id="ds_tabela" name="ds_tabela"
                                          placeholder="Descrição da tabela"
                                          rows="5"><?php echo $tabelaPreco['ds_tabela'] ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <legend>Edição de Lotes</legend>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-6">
                            <label for="quadra" class="control-label">Quadra:</label>
                            <select class="form-control" name="quadra" id="quadra">
                                <?php Helper::geraOptionsSelect($this->get("listaQuadras"), 'quadra', array('Quadra: [quadra]',array('quadra'))); ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="lote" class="control-label">Lote:</label>
                            <select class="form-control" name="lote" id="lote">
                                <?php $tb_tb_preco_lote['idLote']; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="vl_total" class="control-label">Valor do Lote</label>
                                    <input type="text" class="form-control maskDinheiro"
                                           id="vl_total" name="vl_total"
                                          placeholder="Digite o Valor do Lote"><br>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="vl_parcela" class="control-label">Valor da Parcela</label>
                                    <input type="text"  class="form-control maskDinheiro" id="vl_parcela" name="vl_parcela"
                                           placeholder="Digite o Valor da Parcela">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="vl_sinal" class="control-label">Valor do Sinal</label>
                                    <input type="text"  class="form-control maskDinheiro" id="vl_sinal" name="vl_sinal"
                                           placeholder="Digite o Valor do Sinal">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="vl_intercalada" class="control-label">Valor da Intercalada</label>
                                    <input type="text"  class="form-control maskDinheiro" id="vl_intercalada" name="vl_intercalada"
                                           placeholder="Digite o Valor da Intercalada">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="qt_intercalada" class="control-label">Qtde Intercaladas</label>
                                    <input type="text"  class="form-control" id="qt_intercalada" name="qt_intercalada"
                                           placeholder="Digite a Quantidade de Intercaladas">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="dt_atualizacao" class="control-label">Data de Intercaladas</label>
                                    <input type="text"  class="form-control dtpicker maskData" id="dt_atualizacao" name="dt_atualizacao"
                                           placeholder="Digite a Data de Atualização">
                                </div>
                            </div>
                        </div>
                    </div>

                    <legend><strong>Operações</strong></legend>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-2">
                                    <input type="submit" class="btn btn-primary col-sm-12" value="Salvar"/>
                                </div>
                                <div class="col-sm-2">
                                    <a href="?m=<?php echo $_GET['m'] ?>&c=<?php echo $_GET['c']; ?>&a=listar"
                                       class="btn btn-danger col-sm-12">
                                        Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
        </form>
    </fieldset>
<?php require_once 'layout/includes/footer.php'; ?>