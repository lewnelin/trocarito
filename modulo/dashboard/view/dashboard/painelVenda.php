<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';
$listaEmpreendimentos = $this->get("listaEmpreendimentos");
?>
    <style>
        .widget {
            position: relative;
            background: #fff;
            color: #5b5b5b;
            margin-bottom: 20px;
            height: 170px;
        }
    </style>

<?php if (isset($_GET['permissao'])): ?>
    <div class="alert alert-danger alert-dismissable">
        <div align="center">
            <?= $_GET['permissao'] ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->_helper->getMensagens()) : ?>
    <?php foreach ($this->_helper->getMensagens() as $mensagem) : ?>
        <div class="alert alert-danger alert-dismissable">
            <div align="center">
                <?= $mensagem ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

    <div class="modal fade" id="informacoes" tabindex="-1" role="dialog" aria-labelledby="">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" align="center"><i class="icon-info-circled"></i> Informações </h4>
        </div>

        <div class="modal-body form-horizontal corpoModal">
            <div class="row carregando">
                <div class="col-sm-12">
                    <div class="form-group" align="center">
                        <i class="loading"></i>
                        <labe>Carregando</labe>
                    </div>
                </div>
            </div>
            <fieldset class="dadosPessoa" style="padding-left: 10px; padding-bottom: 8px">
                <div class="row camposInfo nome">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Nome</strong></label>

                            <div id="nome" class="col-sm-8"></div>
                        </div>
                    </div>
                </div>

                <div class="row camposInfo cpf">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>CPF</strong></label>

                            <div id="cpf" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset style="padding-left: 10px; padding-bottom: 8px">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Empreendimento</strong></label>

                            <div id="empreendimento" class="col-sm-8"></div>
                        </div>
                    </div>
                </div>

                <div class="row camposInfo corretor">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Corretor</strong></label>

                            <div id="corretor" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Quadra/Lote</strong></label>

                            <div id="quadraLote" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

            </fieldset>

            <fieldset class="valores" style="padding-left: 10px; padding-bottom: 8px">
                <div class="row camposInfo valor">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0" id="divValor">
                            <label class="col-sm-4"><strong>Valor</strong></label>

                            <div id="valor" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row camposInfo valor">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0" id="divValor">
                            <label class="col-sm-4"><strong>Valor do sinal</strong></label>

                            <div id="sinal" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row camposInfo valor">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0" id="divValor">
                            <label class="col-sm-4"><strong>Valor parcela normal</strong></label>

                            <div id="parcela" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row camposInfo valor">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0" id="divValor">
                            <label class="col-sm-4"><strong>Valor intercalada</strong></label>

                            <div id="vlintercalada" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row camposInfo valor">
                    <div class="col-sm-12">
                        <div class="form-group " style="margin-bottom: 0" id="divValor">
                            <label class="col-sm-4"><strong>Qtd. parc. intercalada</strong></label>

                            <div id="qtintercalada" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="dimensoes" style="padding-left: 10px; padding-bottom: 8px">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Área</strong></label>

                            <div id="tamanho" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Frente</strong></label>

                            <div id="loteFrente" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" style="padding-top: 3px">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Lado esquerdo</strong></label>

                            <div id="loteEsquerdo" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" style="padding-top: 3px">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Lado direito</strong></label>

                            <div id="loteDireito" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12" style="padding-top: 3px">
                        <div class="form-group" style="margin-bottom: 0">
                            <label class="col-sm-4"><strong>Fundo</strong></label>

                            <div id="loteFundo" class="col-sm-7"></div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="row camposInfo reservaTecnica">
                <hr/>
                <div class="col-sm-12">
                    <div class="form-group " style="margin-bottom: 0">
                        <div align="center" style="padding-top:0px; font-size: 20px;" class="col-sm-12">
                            <strong>Reserva Técnica</strong>
                        </div>
                    </div>
                </div>
                <hr/>
            </div>

            <div class="row camposInfo reservaTecnica">
                <div class="col-sm-12">
                    <div class="form-group " style="margin-bottom:0; text-align: justify">
                        <div id="obsReservaTecnica" style="padding-top:10px;" class="col-sm-12"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>

    <div class="row top-summary topo-painel-venda">

        <form id="formDashboard" method="post" action="?m=dashboard&c=dashboard&a=painelVenda">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group col-sm-3">
                        <select name="Empreendimento" id="Empreendimento" class="form-control"
                                title="Escolha o Empreendimento" data-placeholder="Escolha um Empreendimento">
                            <option value=""></option>
                            <?php
                            if ($listaEmpreendimentos) foreach ($listaEmpreendimentos as $empreendimentos) {
                                echo '<option value="' . $empreendimentos['id'] . '" ' . $select . '> ' . $empreendimentos['nm_empreendimento'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <select name="Quadra" id="Quadra" class="form-control" title="Escolha a Quadra"
                                data-placeholder="Escolha a Quadra" disabled>
                            <option value=""></option>
                            <option value="*">Todos</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <select name="statusLotes" id="statusLotes" class="form-control"
                                title="Escolha o Status dos lotes" data-placeholder="Escolha o Status dos Lotes"
                                disabled>
                            <option value=""></option>
                            <option selected value="*">Todos</option>
                            <option value="D">Disponível</option>
                            <option value="N">Negociação</option>
                            <option value="R">Reservado</option>
                            <option value="V">Vendido</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <select name="tabelaPreco" id="tabelaPreco" class="form-control"
                                title="Escolha a Tabela de Preço" data-placeholder="Escolha a Tabela de Preço"
                                disabled>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <input type="hidden" id="ultimoId" name="ultimoId" value=""/>
        <input type="hidden" id="idCorretorAtual" name="idCorretorAtual"
               value="<?= Login::getUsuario()->getId() . '_' . Login::getUsuario()->getSuper() ?>"/>

        <div class="row">
            <div class="col-sm-12" id="listaLotes">
                <div class="alert alert-info alert-dismissable" align="center">
                    Escolha o Empreendimento para poder listar os lotes.
                </div>
            </div>
        </div>

    </div>

<?php require_once 'layout/includes/footer.php'; ?>