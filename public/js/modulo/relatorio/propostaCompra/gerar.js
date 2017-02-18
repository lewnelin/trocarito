$(document).ready(function () {

    var fields = {
        emp: {
            validators: {
                notEmpty: {
                    message: 'O campo Empreendimento é obrigatório.'
                }
            }
        },
        quadra: {
            validators: {
                notEmpty: {
                    message: 'O campo Quadra é obrigatório.'
                }
            }
        },
        lote: {
            validators: {
                notEmpty: {
                    message: 'O campo Lote é obrigatório.'
                }
            }
        }
    };

    formValidator(fields);

    selectEmpreendimento = $("select#proposta_emp").select2({
        'placeholder': 'Escolha o empreendimento',
        'allowClear': true
    });

    if (selectEmpreendimento.val() != "") {
        buscarQuadra();
    }

    $("select#proposta_emp").on("change", buscarQuadra);

    $("select#quadra").on("change", buscarLote);

    /*
     * Busca corretores por empreendimento
     */
    function buscarQuadra() {

        $.ajax({
            type: "POST",
            url: "index.php?m=relatorio&c=propostaCompra&a=findQuadraByEmpreendimento",
            data: "cdEmpreendimento=" + $("select#proposta_emp").val(),
            success: function (response) {

                var options = JSON.parse(response);

                $("select#quadra").prop("disabled", false);
                $("select#quadra").empty();
                selectCorretor = $("select#quadra").select2({
                    'data': options['quadras'],
                    'placeholder': 'Escolha a quadra',
                    'allowClear': true
                });
            }
        });
    }

    function buscarLote() {

        $.ajax({
            type: "POST",
            url: "index.php?m=relatorio&c=propostaCompra&a=findLoteByQuadra",
            data: {
                cdEmpreendimento: $("select#proposta_emp").val(),
                quadra: $("select#quadra").val()
            },
            success: function (response) {

                var options = JSON.parse(response);

                $("select#lote").prop("disabled", false);
                $("select#lote").empty();
                selectLote = $("select#lote").select2({
                    'data': options['lotes'],
                    'placeholder': 'Escolha o lote',
                    'allowClear': true,
                    language: {
                        "noResults": function(){
                            return 'Não há lotes com esses filtros';
                        }
                    }
                });

            }
        });
    }

});