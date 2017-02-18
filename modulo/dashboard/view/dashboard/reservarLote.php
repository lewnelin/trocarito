<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$listaCorretorEmpreendimento = $this->get("listaCorretorEmpreendimento");
$lote = $this->get("infoLote");

?>

    <div class="page-heading">
        <h1><i class='icon-clock-circled'></i> Reservar <strong>Lote</strong></h1>
    </div>

    <div class="widget">

        <div class="widget-content padding">

            <form role="form" action="" method="post" name="registerForm" id="registerForm">

                <fieldset>
                    <legend>Informações de Reserva - Lote <b><?= $lote['lote'] ?></b> - Quadra -
                        <b><?= $lote['quadra'] ?></b></legend>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group col-sm-6">
                                <label for="nmCliente" class="control-label">Nome do Cliente: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                                <input type="text" class="form-control" id="nmCliente" name="nmCliente" placeholder="Digite o nome do Cliente" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="nrCpf" class="control-label">CPF: </label>
                                <input type="text" class="form-control maskCpf" id="nrCpf" name="nrCpf"
                                       data-mask="999.999.999-99" placeholder="Digite o CPF">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group select2 col-sm-6">
                                <label for="idCorretor" class="control-label">Corretor: <span class="text-danger" title="Este campo é obrigatório.">*</span></label>
                                <select class="form-control" name="idCorretor" id="idCorretor" required>
                                    <?php Helper::geraOptionsSelect($listaCorretorEmpreendimento, 'id_corretor', 'nm_pessoa',(count($listaCorretorEmpreendimento) == 1)?$listaCorretorEmpreendimento[0]['id_corretor']:null); ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="nrTelefone" class="control-label">Telefone: </label>
                                <input type="text" class="form-control maskTelefone" id="nrTelefone" name="nrTelefone"
                                       placeholder="Digite o Telefone. Ex:(83) 98872-3333">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group col-sm-6">
                                <label for="dsObservacao" class="control-label">Observação: </label>
                                <textarea data-bv-field="observacao" id="dsObservacao" name="dsObservacao"
                                          class="form-control" cols="6" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend><h4>Operações</h4></legend>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary" id="btnSalvar">Salvar</button>
                                <button type="button" class="btn btn-primary" id="btnSalvar" onclick="location.href = '<?php echo $this->_helper->getLink(array("m" => "dashboard", "c" => "dashboard", 'a'=>'painelVenda', "idEmpreedimento" => $_GET['idEmpreedimento'], "quadraAtual" => $_GET['quadraAtual'], "statusAtual" => $_GET['statusAtual'], "tabelaPrecoAtual" => $_GET['tabelaPrecoAtual'])); ?>';">
                                    Voltar
                                </button>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </form>

        </div>
    </div>

<?php require_once 'layout/includes/footer.php'; ?>