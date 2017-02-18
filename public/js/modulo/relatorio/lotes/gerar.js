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

    $("select#proposta_emp").on("change", buscarTabelaPreco);

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

    function buscarTabelaPreco() {
        $.ajax({
            type: "POST",
            url: "index.php?m=relatorio&c=lotes&a=findTabelaPrecobyEmpreendimento",
            data: {
                cdEmpreendimento: $("select#proposta_emp").val(),
                quadras: $("select#quadra").val()
            },
            success: function (response) {

                var options = JSON.parse(response);

                $("select#tabela").prop("disabled", false);
                $("select#tabela").empty();
                selectTabela = $("select#tabela").select2({
                    'data': options['tabela'],
                    'placeholder': 'Escolha a tabela de preço',
                    'allowClear': true,
                    language: {
                        "noResults": function () {
                            return 'Não há Tabela de Preços com esses filtros';
                        }
                    }
                });

            }
        });
    }

    $("#gerarRelatorio").on('click', function (e) {
        var $form = $('#registerForm'),
            bv = $form.data('bootstrapValidator');

        if (bv.isValid()) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '?m=relatorio&c=lotes&a=findResultados',
                data: {
                    cdEmpreendimento: $("#proposta_emp").val(),
                    quadras: $("#quadra").val(),
                    tabela: $("#tabela").val()
                },
                success: function (json) {
                    var registros = JSON.parse(json);

                    if (registros > 0) {
                        var formulario = document.getElementById('registerForm');
                        formulario.submit();
                    } else {
                        $("#warning").html('Não há resultados com os filtros selecionados.').show(); // esconder
                        setTimeout(function () {          // temporizador
                            $("#warning").hide("slow");
                        }, 8000);
                    }
                }
            });
        }
    });

});