$(document).ready(function () {

    var fields = {
        captacaoEmpreendimento: {
            validators: {
                notEmpty: {
                    message: 'Empreendimento é obrigatório.'
                }
            }
        },
        captador: {
            validators: {
                notEmpty: {
                    message: 'Captador é obrigatório.'
                }
            }
        },
        dataDe: {
            validators: {
                notEmpty: {
                    message: 'A data inicial é obrigatória'
                },
                date: {
                    format: 'DD/MM/YYYY',
                    message: 'Data inválida'
                },
                regexp: {
                    regexp: /^[0-9/]+$/i,
                    message: 'Digite apenas números e barras no campo'
                }
            }
        },
        dataAte: {
            validators: {
                notEmpty: {
                    message: 'A data final é obrigatória'
                },
                date: {
                    format: 'DD/MM/YYYY',
                    message: 'Data inválida'
                },
                regexp: {
                    regexp: /^[0-9/]+$/i,
                    message: 'Digite apenas números e barras no campo'
                }
            }
        }
    };

    formValidator(fields);

    selectEmpreendimento = $("select#captacaoEmpreendimento").select2({
        'placeholder': 'Escolha o empreendimento',
        'allowClear': true
    });

    selectEmpreendimento.on("change", buscarCaptadores);

    $("#gerarRelatorio").on('click', function (e) {
        var $form = $('#registerForm'),
            bv = $form.data('bootstrapValidator');

        if (bv.isValid()) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '?m=relatorio&c=captacao&a=findResultados',
                data: {
                    idEmpreendimento: $("#captacaoEmpreendimento").val(),
                    captacao: $("[name=radio]:checked").val(),
                    captador: $("#captador").val(),
                    dataDe: $("#dataDe").val(),
                    dataAte: $("#dataAte").val()
                },
                success: function (json) {
                    var registros = JSON.parse(json);

                    if (registros > 0) {
                        var formulario = document.getElementById('registerForm');
                        formulario.submit();
                    } else {
                        $("#warning").html('Não há resultados com os filtros selecionados.').show(); // esconder
                        setTimeout(function () {
                            $("#warning").hide("slow");
                        }, 8000);
                    }
                }
            });
        }
    });

    /*
     * Busca captadores por empreendimento
     */
    function buscarCaptadores() {

        $.ajax({
            type: "POST",
            url: "index.php?m=relatorio&c=captacao&a=captadoresByEmpreendimento",
            data: "emp=" + $("select#captacaoEmpreendimento").val(),
            success: function (response) {

                var options = JSON.parse(response);

                $("select#captador").prop("disabled", false);
                $("select#captador").empty();
                selectCorretor = $("select#captador").select2({
                    'data': options,
                    'placeholder': 'Escolha o captador',
                    'allowClear': true
                });

            }
        });
    }

});