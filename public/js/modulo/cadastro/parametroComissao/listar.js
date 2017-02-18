$(document).ready(function () {

    /*
     * Listar
     */
    //

    $('#TabelaListar').DataTable({
        "processing": true,
        ajax: "?m=cadastro&c=parametroComissao&a=listar",
        "columns": [
            {"data": 'checkbox'},
            {"data": "parametro"},
            {"data": "empreendimento"},
            {"data": "nome"},
            {"data": "comissao"},
            {"data": "insidencia"},
            {"data": "acoes"}
        ],
        //Define a coluna default que será ordenada quando abrir a página
        "order": [[1, "desc"]],

        //Organiza os botoes, paginação, campos e notificações
        dom: 'T<"btnMais"><"btnAdicionar">lfrtip',

        //Define as opções de quantidade de linhas que aparecão na lista
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

        //Define as colunas que não terão ordenação
        "columnDefs": [
            {
                "orderable": false,
                "targets": 0,
                "searchable": false
            },
            {
                "orderable": false,
                "targets": -1,
                "searchable": false
            }
        ],

        //Extensão do plugin que cria um botão com opções de copiar tabela, tirar print, salvar em csv ou pdf.
        tableTools: {
            "aButtons": [
                {
                    "sExtends": "collection",
                    "sButtonText": "<i class='icon-export'></i> Exportar",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "sButtonText": "Copiar Tabela"
                        },
                        "print",
                        "csv",
                        "pdf"]
                }
            ],

            "sSwfPath": "public/libs/jquery-datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
        },

        //Personalizando as mensagens que por default são em inglês e formatação de valores
        language: {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "",
            "sInfoEmpty": "",
            "sInfoFiltered": "",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "_MENU_",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Busca",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            },

            "decimal": ",",
            "thousands": "."
        },
        initComplete: function () {

            //Botão "Adicionar" da listagem de todas telas
            var btnAdicionar = '<a class="btn btn-success btn-sm" href="?m=cadastro&c=parametroComissao&a=adicionar"><i class="fa fa-plus-circle"></i> Adicionar</a>';

            //Botão "Mais" da listagem de todas telas
            var btnMais = '<div class="btn-group">' +
                '<button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown">' +
                'Mais <span class="caret"></span>' +
                '</button>' +
                '<ul class="dropdown-menu success" role="menu">' +
                '<li><a class="btnDeletar" data-toggle="modal" data-target="#" href="#"><i class="fa fa-trash-o"></i> Deletar </a></li>' +
                '</ul>' +
                '</div>';

            $("div.btnMais").html(btnMais);

            $("div.btnAdicionar").html(btnAdicionar);
        }
    });
});