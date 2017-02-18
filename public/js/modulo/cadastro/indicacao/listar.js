$(document).ready(function () {

    /*
     * Listar
     */

    $('#TabelaListar').DataTable({
        "processing": true,
        ajax: "?m=cadastro&c=indicacao&a=listar",
        "columns": [
            {"data": "idIndicacaoContrato"},
            {"data": "dataIndicacao"},
            {"data": "indicado"},
            {"data": "cliente"},
            {"data": "responsavel"},
            {"data": "ultimoTipoContato"},
            {"data": "acoes"}
        ],
        //Define a coluna default que será ordenada quando abrir a página
        "order": [[0, "desc"]],

        //Organiza os botoes, paginação, campos e notificações
        dom: 'T<"btnAdicionar">lfrtip',

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
                            "sButtonText": "Copiar Tabela",
                            "sMessage": "Tabela copiada (ctrl+c) com sucesso"
                        },
                        {
                            "sExtends": "print",
                            "sInfo": "Pressione ESC para voltar"
                        },
                        "csv",
                        "pdf"
                    ]
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
            var btnAdicionar = '<a class="btn btn-success btn-sm" href="?m=cadastro&c=indicacao&a=adicionar"><i class="fa fa-plus-circle"></i> Adicionar</a>';

            $("div.btnAdicionar").html(btnAdicionar);
        }
    });
});