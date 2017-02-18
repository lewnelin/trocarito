var selectEmpreendimento;
var fileData, fdata;

$(document).ready(function () {

    fields = {
        "arquivoCadastro": {
            validators: {
                callback: {
                    message: 'O Arquivo não possui registros válidos.',
                    callback: function (value, validator, $field) {
                        fileData = event.target.files; // para apenas 1 arquivo
                        fdata = new FormData();
                        fdata.append(0, fileData[0]);
                        var valid = false;

                        $("#erroImportar").html('Processando . . .<i style="float: left" class="loading"></i>');
                        if (confirm('O arquivo será analisado. Isso demorará alguns instantes.')) {
                            $.ajax({
                                url: "?m=cadastro&c=tabelaPreco&a=contagemRegistros&idEmp=" + $("#Empreendimento").val(),
                                type: 'POST',
                                data: fdata,
                                dataType: "json",
                                processData: false,
                                contentType: false,
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (json) {
                                },
                                complete: function (data) {
                                    var count = data.responseJSON;
                                    if (count > 0) {
                                        $("#erroImportar").text('O arquivo selecionado possui ' + count + ' registros válidos');
                                        valid = true;
                                    } else {
                                        $("#arquivoCadastro").val('');
                                        $("#erroImportar").text('O arquivo selecionado não possui registros válidos');
                                    }
                                }
                            });
                            return valid;
                        }
                    }
                }
            },
            file: {
                extension: "xls,xlsx,csv",
                message: 'A extensão do arquivo selecionado não é válida.'
            }
        }
    };

    var submitHandler = function (validator, form, submitButton) {
        var data = new FormData();
        $.each(fileData, function (key, value) {
            data.append(key, value);
        });
        $.ajax({
            type: 'POST',
            data: data,
            async: false,
            cache: false,
            contentType: false,
            processData: false
        });


        validator.defaultSubmit();
    };

    formValidator(fields, "#adicionarTabelaPrecoLotes", submitHandler);

    /*
     * Opções do select empreendimento
     */
    selectEmpreendimento = $("#Empreendimento").select2({
        'placeholder': 'Escolha o empreendimento',
        'allowClear': true
    });

    $("#Empreendimento").on("change", function () {
        $("#arquivoCadastro").removeClass('disabled');
        $("#arquivoCadastro").removeAttr('disabled');

        if ($("#arquivoCadastro").val() != '') {
            $("#arquivoCadastro").change();
        }
    });

    $('#arquivoCadastro').change(function (event) {
        fileData = event.target.files; // para apenas 1 arquivo
        fdata = new FormData();
        fdata.append(0, fileData[0]);
    });

});