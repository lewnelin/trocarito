$(document).ready(function () {

    var fields = {
        clienteEmpreendimento: {
            validators: {
                notEmpty: {
                    message: 'Empreendimento é obrigatório.'
                }
            }
        },
        dataDe: {
            validators: {
                notEmpty: {
                    message: 'A data inicial é obrigatória'
                },
                callback: {
                    message: 'Período não pode ser superior a um ano',
                    callback: function(value, validator, $field) {
                        if($("#dataAte").val() != '') {
                            return (diferencaDatasDias($("#dataAte").val(), value) < 360)
                        } else return true;
                    }
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
                callback: {
                    message: 'Período não pode ser superior a um ano',
                    callback: function(value, validator, $field) {
                        if($("#dataDe").val() != ''){
                            return (diferencaDatasDias(value, $("#dataDe").val()) < 366)
                        } else return true;
                    }
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

    selectEmpreendimento = $("select#clienteEmpreendimento").select2({
        'placeholder': 'Escolha o empreendimento',
        'allowClear': true
    });

    $("#tpCliente").on('change', function (e) {

        switch ($(this).val()) {
            case 'cc':
                $("#labelData").html('Data do Contrato:');
                break;
            case 'cd':
                $("#labelData").html('Data do Distrato:');
                break;
            case 'sc':
                $("#labelData").html('Data do Cadastro:');
                break;
            default :
                $("#labelData").html('Data do Contrato:');
                break;
        }
    });

    $("#gerarRelatorio").on('click', function (e) {
        var $form = $('#registerForm'),
            bv = $form.data('bootstrapValidator');

        if (bv.isValid()) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '?m=relatorio&c=cliente&a=findResultados',
                data: {
                    idEmpreendimento: $("#clienteEmpreendimento").val(),
                    tpCliente: $("#tpCliente").val(),
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
                        setTimeout(function () {          // temporizador
                            $("#warning").hide("slow");
                        }, 10000);
                    }
                }
            });
        }
    });

});