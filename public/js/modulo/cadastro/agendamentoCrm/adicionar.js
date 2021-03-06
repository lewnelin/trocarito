$(document).ready(function () {

    //autocomplete utilizando o plugin select2
    select2RemoteData(
        '[name="idCliente"]',
        false,
        true,
        'Digite o nome, cpf ou cnpj do cliente',
        '?m=cadastro&c=indicacao&a=findPessoasByNome'
    );

    $('#idCliente').on("select2:select", function (e) {

        var info = e.params.data.info,
            nr_telefone = (info.nr_telefone) ? info.nr_telefone : '',
            nr_celular = (info.nr_celular) ? ' ' + info.nr_celular : '',
            nr_recado = (info.nr_recado) ? ' ' + info.nr_recado : '',
            nr_fax = (info.nr_fax) ? ' ' + info.nr_fax : '';

        $('#infoTelefone').text(nr_telefone + nr_celular + nr_recado + nr_fax);
    });

    var fields = {
        idEmpreendimento: {
            validators: {
                notEmpty: {
                    message: 'O campo é obrigatório'
                }
            }
        },
        nmIndicado: {
            validators: {
                notEmpty: {
                    message: 'O campo é obrigatório'
                }
            }
        },
        nrTelefone: {
            validators: {
                notEmpty: {
                    message: 'O campo é obrigatório'
                }
            }
        }
    };

    formValidator(fields);

});