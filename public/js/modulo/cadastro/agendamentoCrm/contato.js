$(document).ready(function () {

    $('#btnNovoContato').on("click", function () {
        $('.blocoBtnNovoContato').hide('fast');

        //limpa todos campos e adiciona valor 1 no campo hidden novo contato
        $('.limpaCampos').val('');
        $('#idTipoContato').val('').trigger("change");
        $('#novoContato').val('1');
        $('#dtContato').val(dataAtual());
        $('#hrContato').val(horaAtual());
    });

    //Verifica se o contato está selecionado para fechamento
    $("#btnSalvarContato").on('click', function() {
        if ($(".flStatus").hasClass('on')) {
            nconfirm('O contato será fechado e enviado ao histórico. <br>Você deseja continuar?');
        } else {
            document.getElementById("registerForm").submit();
        }
    });

    //Caso seja selecionado sim submite o formulário
    $(document).on('click', '.yes', function () {
        document.getElementById("registerForm").submit();
    });

    var fields = {
        dtContato: {
            validators: {
                notEmpty: {
                    message: 'A Data da Parc. Normal é obrigatório'
                },
                date: {
                    format: 'DD/MM/YYYY',
                    message: 'Data inválida'
                }
            }
        },
        dtFechamento: {
            validators: {
                date: {
                    format: 'DD/MM/YYYY',
                    message: 'Data inválida'
                }
            }
        },

        hrContato: {
            validators: {
                notEmpty: {
                    message: 'Campo obrigatório'
                }
            }
        },
        idTipoContato: {
            validators: {
                notEmpty: {
                    message: 'Campo obrigatório'
                }
            }
        }
    }

    formValidator(fields);

});