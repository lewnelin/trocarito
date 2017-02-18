<?php
require_once 'layout/includes/header.php';
require_once 'layout/includes/menu.php';
?>

<div class="table-responsive">
    <table id="TabelaListar"
           class="table table-striped table-bordered table-hover table-condensed order-column"
           cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Cod.</th>
            <th>Data Indicação</th>
            <th>Indicado</th>
            <th>Indicado Por</th>
            <th>Captador</th>
            <th>Último Contato</th>
            <th width="120px">
                <div align="center">Ações</div>
            </th>
        </tr>
        </thead>
    </table>
</div>

<?php require_once 'layout/includes/footer.php'; ?>
