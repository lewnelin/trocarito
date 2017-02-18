$(document).ready(function() {

    var registerForm = {

        nrCpf: {
            enabled: false,
            validators: {
                id: {
                    country: 'BR',
                    message: 'CPF Inválido'
                }
            }
        }
    };

    formValidator(registerForm);

    //Exibe os campos do cônjuge
    $('[name="nrCpf"]').on('keyup', function() {

        var isEmpty = $(this).val().length > 0;

        //Ativa a validação do bootstrapValidator no campo
        $('#registerForm').bootstrapValidator('enableFieldValidators', 'nrCpf', isEmpty);

        //Revalida o campo
        if (isEmpty)
            $('#registerForm').bootstrapValidator('validateField', 'nrCpf');
    });

});