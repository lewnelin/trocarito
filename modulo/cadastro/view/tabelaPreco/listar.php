<?php

require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';

?>
<form action="?m=cadastro&c=tabelaPreco&a=deletar" method="POST">
<div id="confirmDelet" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title" id="myModalLabel" align="center"><i class="fa fa-warning" ></i> Atenção</h2>
            </div>
            <div class="modal-body">
                <h4>Você realmente deseja deletar o(s) registro(s):</h4>

                <div class="tableFix">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-condensed">
                            <tbody id="tbodyDeletar" >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Confirmar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelModal">Fechar</button>
            </div>

        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="TabelaListar"
           class="table table-striped table-bordered table-hover table-condensed order-column"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th><div align="center"><input type="checkbox" class="rows-check" id="checkPrincipal"></div></th>
            <th>Cod.</th>
            <th>Empreendimento</th>
            <th>Nome</th>
            <th align="center">Padrão</th>
            <th align="center">Qtde Lotes</th>
            <th width="120px">
                <div align="center">Ações</div>
            </th>
        </tr>
        </thead>
    </table>
</div>
</form>

<?php require_once 'layout/includes/footer.php'; ?>
