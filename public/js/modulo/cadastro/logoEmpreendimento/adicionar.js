var selectEmpreendimento;
var fileData, fdata;

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

    formValidator(fields, "#adicionarLogoEmpreendimento", submitHandler);

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

        if($("#arquivoCadastro").val() != ''){
            $("#arquivoCadastro").change();
        }
    });

    $('#arquivoCadastro').change(function (event) {
        fileData = event.target.files; // para apenas 1 arquivo
        fdata = new FormData();
        $.each(fileData, function (key, value) {
            fdata.append(key, value);
        });
    });

});