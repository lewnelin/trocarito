var selectEmpreendimento;

$(document).ready(function () {

    fields = {
        "Empreendimento": {
            validators: {
                notEmpty: {
                    message: 'Este campo é obrigatório'
                }
            }
        },
        "tpComissao": {
            validators: {
                notEmpty: {
                    message: 'Este campo é obrigatório'
                }
            }
        },
        "nmParametro": {
            validators: {
                notEmpty: {
                    message: 'Este campo é obrigatório'
                }
            }
        },
        "tpInsidencia": {
            validators: {
                notEmpty: {
                    message: 'Este campo é obrigatório'
                }
            }
        },
        "qtParcelas": {
            validators: {
                notEmpty: {
                    message: 'Este campo é obrigatório'
                },
                regexp: {
                    regexp: /^[0-9]+$/i,
                    message: 'Digite apenas números no campo'
                },
                greaterThan: {
                    value: 1,
                    message: 'Digite apenas números maiores que zero'
                }
            }
        },
        "vlCorretor": {
            validators: {
                callback: {
                    message: ' ',
                    callback: function (value, validator, $field) {
                        return verificaValores();
                    }
                }
            }
        },
        "vlCoordenador": {
            validators: {
                callback: {
                    message: ' ',
                    callback: function (value, validator, $field) {
                        return verificaValores(value);
                    }
                }
            }
        },
        "vlIndicador": {
            validators: {
                callback: {
                    message: ' ',
                    callback: function (value, validator, $field) {
                        return verificaValores(value);
                    }
                }
            }
        },
        "vlImobiliaria": {
            validators: {
                callback: {
                    message: ' ',
                    callback: function (value, validator, $field) {
                        return verificaValores(value);
                    }
                }
            }
        },
        "vlOutros": {
            validators: {
                callback: {
                    message: ' ',
                    callback: function (value, validator, $field) {
                        return verificaValores(value);
                    }
                }
            }
        }
    };

    formValidator(fields, "#adicionarParametroComissao");

    /*
     * Opções do select empreendimento
     */
    selectEmpreendimento = $("#Empreendimento").select2({
        'placeholder': 'Escolha o empreendimento',
        'allowClear': true
    });

    $("#tpComissao").on('change', function () {
        var vary = '';
        var tipo = $("#tpComissao").val();

        if (tipo == 'F') {
            $("#textoTotalComissao").html('Valor Total Comissões: R$ ');
            $("#textoTotalComissao2").html('');
            vary = 'R$';
            $.each($(".valores"), function (key, value) {
                $(this).mask('000.000.000.000.000,00', {reverse: true});
            });
            $("#vlTotalComissao").html(floatToMoeda(moedaToFloat($("#vlImobiliaria").val()) + moedaToFloat($("#vlOutros").val()) + moedaToFloat($("#vlIndicador").val()) +
            moedaToFloat($("#vlCoordenador").val()) + moedaToFloat($("#vlCorretor").val())));
        }
        if (tipo == 'P') {
            $("#textoTotalComissao").html('Porcentagem Total Comissões: ');
            $("#textoTotalComissao2").html('%');
            vary = '%';
            $.each($(".valores"), function (key, value) {
                $(this).mask('###', {reverse: true});
            });
        }

        $.each($(".vary"), function (key, value) {
            this.textContent = vary;
        });
        $.each($(".valores"), function (key, value) {
            if (this.value != '') {
                verificaValores();
            }
        });
    });

    if($("#tpComissao").val() == 'F'){
        $("#textoTotalComissao").html('Valor Total Comissões: R$ ');
        $("#textoTotalComissao2").html('');
        $("#vlTotalComissao").html(floatToMoeda(moedaToFloat($("#vlImobiliaria").val()) + moedaToFloat($("#vlOutros").val()) + moedaToFloat($("#vlIndicador").val()) +
        moedaToFloat($("#vlCoordenador").val()) + moedaToFloat($("#vlCorretor").val())));
        $.each($(".vary"), function (key, value) {
            this.textContent = 'R$';
        });
    }
    if($("#tpComissao").val() == 'P'){
        $("#textoTotalComissao").html('Porcentagem Total Comissões: ');
        $("#textoTotalComissao2").html('%');
        $("#vlTotalComissao").html(parseInt($("#vlImobiliaria").val()) + parseInt($("#vlOutros").val()) + parseInt($("#vlIndicador").val()) +
        parseInt($("#vlCoordenador").val()) + parseInt($("#vlCorretor").val()));
        $.each($(".vary"), function (key, value) {
            this.textContent = '%';
        });
    }

    $(".valores").on('change', function () {
        $("#adicionarParametroComissao").bootstrapValidator('validateField', "vlImobiliaria")
            .bootstrapValidator('validateField', "vlIndicador")
            .bootstrapValidator('validateField', "vlCoordenador")
            .bootstrapValidator('validateField', "vlCorretor")
            .bootstrapValidator('validateField', "vlOutros");

        if ($("#tpComissao").val() == 'F') {
            $("#vlTotalComissao").html(floatToMoeda(moedaToFloat($("#vlImobiliaria").val()) + moedaToFloat($("#vlOutros").val()) + moedaToFloat($("#vlIndicador").val()) +
            moedaToFloat($("#vlCoordenador").val()) + moedaToFloat($("#vlCorretor").val())));
        }
    })

});

function verificaValores(value) {
    var valid = false;
    var tipo = $("#tpComissao").val();
    var acumulador = 0;
    var mensagem = '';

    $.each($(".valores"), function (key, value) {
        var valor = this.value;

        if (valor != '') {
            valor = replaceAll(valor, '.', '');
            valor = replaceAll(valor, ',', '.');
            valor = parseFloat(valor);
        } else {
            valor = 0;
        }

        if (tipo == 'F') {
            if (valor > 0) {
                valid = true;
            }
        }
        if (tipo == 'P') {
            acumulador = acumulador + valor;
            $("#vlTotalComissao").html(acumulador);
        }

        $.each($(".vary"), function (key, value) {
            this.textContent = vary;
        });
    });

    if (acumulador == 100) {
        valid = true;
    }

    if (valid == true) {
        $("#adicionarParametroComissao").bootstrapValidator('updateStatus', "vlImobiliaria", 'VALID')
            .bootstrapValidator('updateStatus', "vlIndicador", 'VALID')
            .bootstrapValidator('updateStatus', "vlCoordenador", 'VALID')
            .bootstrapValidator('updateStatus', "vlOutros", 'VALID')
            .bootstrapValidator('updateStatus', "vlCorretor", 'VALID');
    } else {
        $("#adicionarParametroComissao").bootstrapValidator('updateStatus', "vlImobiliaria", 'INVALID')
            .bootstrapValidator('updateStatus', "vlIndicador", 'INVALID')
            .bootstrapValidator('updateStatus', "vlCoordenador", 'INVALID')
            .bootstrapValidator('updateStatus', "vlOutros", 'INVALID')
            .bootstrapValidator('updateStatus', "vlCorretor", 'INVALID');
    }
    if (valid == false) {
        if (tipo == 'F') {
            mensagem = 'Ao menos um valor deve ser preenchido';
        } else if (tipo == 'P') {
            mensagem = 'Valores devem somar 100%';
        } else {
            mensagem = 'Selecione antes o tipo de Valores para comissão';
        }
    } else {
        mensagem = '';
    }
    $("#ErrorMessage").text(mensagem);


    return valid;
}
