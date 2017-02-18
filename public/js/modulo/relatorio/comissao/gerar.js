$(document).ready(function () {

    var fields = {
        emp: {
            validators: {
                notEmpty: {
                    message: 'Empreendimento é obrigatório.'
                }
            }
        },
        corretor: {
            validators: {
                notEmpty: {
                    message: 'Corretor é obrigatório.'
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
                    message: 'A data final é obrigatório'
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

    selectEmpreendimento = $("select#comissao_emp").select2({
        'placeholder': 'Escolha o empreendimento',
        'allowClear': true
    });

    selectCorretor = $("select#corretor_emp").select2({
        'placeholder': 'Escolha o corretor',
        'allowClear': true
    });

    if (selectEmpreendimento.val() != "") {
        buscarCorretores();
    }

    $("select#comissao_emp").on("change", buscarCorretores);

    $("#dataDe").on("change", function () {
        if ($(this).val.length > 0) {
            $('#registerForm').bootstrapValidator('updateStatus', 'dataDe', 'VALID');
            $('#divDatas').removeClass('has-error');
            $('#msgDatas').hide();
        } else {
            $('#registerForm').bootstrapValidator('updateStatus', 'dataDe', 'VALID');
        }

    });

    $("#dataAte").on("change", function () {
        if ($(this).val.length > 0) {
            $('#registerForm').bootstrapValidator('updateStatus', 'dataAte', 'VALID');
            $('#divDatas').removeClass('has-error');
            $('#msgDatas').hide();
        } else {
            $('#registerForm').bootstrapValidator('updateStatus', 'dataAte', 'INVALID');
        }
    });

    $("#gerarRelatorio").on("click", function (e) {

        var $form = $('#registerForm'),
            bv = $form.data('bootstrapValidator'),
            tpParcelas = '';

        $(".tp_parcelas").each(function () {
            tpParcelas += $(this).val() + ', ';
        });

        //Verifica se o formulário inteiro está validado
        if (bv.isValid()) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "index.php?m=relatorio&c=comissao&a=findContratoByEmpreendimento",
                data: {
                    cdEmpreendimento: $('#comissao_emp').val(),
                    cdCorretor: $('#corretor_emp').val(),
                    tp_parcela: tpParcelas,
                    dataDe: $('#dataDe').val(),
                    dataAte: $('#dataAte').val()
                },
                success: function (json) {
                    var json = JSON.parse(json);

                    if (json == true) {
                        $('#divDatas').removeClass('has-error');
                        $('#msgDatas').hide();
                        $('#registerForm').submit();
                        $("#gerarRelatorio").removeAttr('disabled')
                    } else {
                        $('#divDatas').addClass('has-error');
                        $('#msgDatas').show();
                    }
                }
            });

        }

    });

    /*
     * Busca corretores por empreendimento
     */
    function buscarCorretores() {

        $.ajax({
            type: "POST",
            url: "index.php?m=relatorio&c=comissao&a=corretor_by_emp",
            data: "emp=" + $("select#comissao_emp").val(),
            success: function (response) {

                var options = JSON.parse(response);

                $("select#corretor_emp").prop("disabled", false);
                $("select#corretor_emp").empty();
                selectCorretor = $("select#corretor_emp").select2({
                    'data': options,
                    'placeholder': 'Escolha o corretor',
                    'allowClear': true
                });
            }
        });
    }

});