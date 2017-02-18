var selectEmpreendimento;

$(document).ready(function () {

    $(document).on('change', '[name="arquivoCadastro"]', function () {
        readURL(this);
    });

    fields = {
        "arquivoCadastro": {
            validators: {
                file: {
                    extension: "jpg,bmp,JPG,BMP,png,PNG",
                    maxSize: 2097152, //em bytes
                    message: 'A extensão do arquivo selecionado não é válida.'
                }
            }
        }
    };

    var submitHandler = function(validator, form, submitButton){
        $("#empreendimentoHidden").val($("#Empreendimento").val());
        validator.defaultSubmit();
    };

    formValidator(fields, "#editarTabelaPrecoLotes", submitHandler);

    $('#arquivoCadastro').change(function (event) {
        fileData = event.target.files; // para apenas 1 arquivo
        fdata = new FormData();
        $.each(fileData, function (key, value) {
            fdata.append(key, value);
        });
    });

});

