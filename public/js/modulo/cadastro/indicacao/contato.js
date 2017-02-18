$(document).ready(function () {

    var fields = {
        dtContato: {
            validators: {
                notEmpty: {
                    message: 'A Data da Parc. Normal é obrigatório'
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
        hrContato: {
            validators: {
                notEmpty: {
                    message: 'O campo é obrigatório'
                }
            }
        },
        idTipoContato: {
            validators: {
                notEmpty: {
                    message: 'O campo é obrigatório'
                }
            }
        }
    }

    formValidator(fields);

});