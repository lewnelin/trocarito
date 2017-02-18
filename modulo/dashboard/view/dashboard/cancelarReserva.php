<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

$lote = $this->get("infoLote");
?>

    <div class="page-heading">
        <h1><i class='icon-cancel-circled'></i> Cancelar <strong>Reserva</strong></h1>
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
                                <label for="dsObservacao" class="control-label">Motivo cancelamento: </label>
                                <textarea data-bv-field="observacao" id="dsObservacao" name="obs_cancelamento" class="form-control" cols="6" rows="3"></textarea>
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