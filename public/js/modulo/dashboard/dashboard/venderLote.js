//Tira o efeito para os alertas fecharem sozinhos após 10s
function fecharAlert() {
}

$(document).ready(function () {

    //autocomplete utilizando o plugin select2
    select2RemoteData(
        '[name="outrosCompradores[]"]',
        true,
        false,
        'Digite o nome do comprador e selecione',
        '?m=dashboard&c=dashboard&a=findPessoasFisicasByNmPessoa'
    );

    select2RemoteData(
        '#idCliente',
        false,
        true,
        'Digite o nome, cpf ou cnpj do Cliente',
        '?m=dashboard&c=dashboard&a=findPessoasByNome'
    );

    if ($("#vlSinal").val() != '') {
        $("#qtdSinal").val('1').trigger('change');
    }

    //limpa o html dentro do modal e exibe os botoes após modal fechado
    //foi preciso pois como o formulario possui muitos campos e o modal ficava lento
    $('.mAdicionaPessoa').on('hide.bs.modal', function () {
        $('#cadastroPessoa').html('');
        $('#buttonPessoaDiv').show();
    });

    var templateNormal = $('#rowNormal').clone(),
        templateIntercalada = $('#rowIntercalada').clone(),
        templateChave = $('#rowChave').clone();

    $("#adicionarNormal").on('click', function () {
        var nrNormais = parseInt($("#nrNormalAdicional").val()) + 1;
        $("#nrNormalAdicional").val(nrNormais);
        var rowId = nrNormais;
        var klon = templateNormal.clone();
        klon.attr('id', 'lineNormal_' + rowId)
            .insertBefore($("#rowNormal"))
            .removeClass('hide')
            .find('input')
            .each(function () {
                $(this)
                    .attr('name', $(this).attr('id').replace(/_(\d*)$/, "[]"))
                    .attr('id', $(this).attr('id').replace(/_(\d*)$/, "_" + rowId))
                    .on('blur', function () {
                        calculaValor()
                    });
                if ($(this).attr('name').indexOf('vl') != -1)
                    $(this).addClass('adicionais');
                if ($(this).attr('name').indexOf('reajustavel') != -1)
                    $(this).attr('checked','checked').parent().addClass('checked');
            });
        var valor = klon.find('input').eq(0),
            qtd = klon.find('input').eq(1);
        valor.mask('000.000.000.000.000,00', {reverse: true});
        qtd.mask('00000', {reverse: true});
        $('#registerForm')
            .bootstrapValidator('addField', valor.attr('name'), {
                validators: {
                    number: {
                        message: 'Apenas Dígitos'
                    }
                }
            })
            .bootstrapValidator('addField', qtd.attr('name'), {
                validators: {
                    notEmpty: {
                        message: 'Quantidade da Parc. é obrigatório'
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
            });
    });

    $("#removerNormal").on('click', function () {
        var nrNormais = $("#nrNormalAdicional").val();
        $("#lineNormal_" + nrNormais).remove();
        if (nrNormais >= 1)
            $("#nrNormalAdicional").val(parseInt(nrNormais) - 1);
        calculaValor()
    });

    $("#adicionarIntercalada").on('click', function () {
        var nrIntercaladas = parseInt($("#nrIntercaladaAdicional").val()) + 1;
        $("#nrIntercaladaAdicional").val(nrIntercaladas);
        var rowId = nrIntercaladas;
        var klon = templateIntercalada.clone();
        klon.attr('id', 'lineIntercalada_' + rowId)
            .insertBefore($("#rowIntercalada"))
            .removeClass('hide')
            .find('input')
            .each(function () {
                $(this)
                    .addClass('intercaladaAdicional_' + rowId)
                    .attr('name', $(this).attr('id').replace(/_(\d*)$/, "[]"))
                    .attr('id', $(this).attr('id').replace(/_(\d*)$/, "_" + rowId))
                    .on('blur', function () {
                        calculaValor()
                    });
                if ($(this).attr('name').indexOf('vl') != -1)
                    $(this).addClass('adicionais');
                if ($(this).attr('name').indexOf('reajustavel') != -1)
                    $(this).attr('checked','checked').parent().addClass('checked');
            });
        var valor = klon.find('input').eq(0),
            qtd = klon.find('input').eq(1),
            nr = klon.find('input').eq(2),
            dt = klon.find('input').eq(3);
        valor.mask('000.000.000.000.000,00', {reverse: true});
        qtd.mask('00000', {reverse: true});
        nr.mask('00000', {reverse: true});
        dt.mask('00/00/0000').datepicker({
            format: "dd/mm/yyyy"
        }).on('changeDate', function (e) {
            var bv = $('#registerForm').data('bootstrapValidator');
            bv.validateField($(this).attr('name'));
        });
        $('#registerForm')
            .bootstrapValidator('addField', valor.attr('name'), {
                validators: {
                    number: {
                        message: 'Apenas Dígitos'
                    }
                }
            })
            .bootstrapValidator('addField', nr.attr('name'), {
                validators: {
                    number: {
                        message: 'Apenas Dígitos'
                    }
                }
            })
            .bootstrapValidator('addField', dt.attr('name'), {
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
            })
            .bootstrapValidator('addField', qtd.attr('name'), {
                validators: {
                    notEmpty: {
                        message: 'Quantidade da Parc. é obrigatório'
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
            });
    });

    $("#removerIntercalada").on('click', function () {
        var nrIntercaladas = $("#nrIntercaladaAdicional").val();
        $("#lineIntercalada_" + nrIntercaladas).remove();
        if (nrIntercaladas >= 1)
            $("#nrIntercaladaAdicional").val(parseInt(nrIntercaladas) - 1);
        calculaValor()
    });

    $("#adicionarChave").on('click', function () {
        var nrChaves = parseInt($("#nrChaveAdicional").val()) + 1;
        $("#nrChaveAdicional").val(nrChaves);
        var rowId = nrChaves;
        var klon = templateChave.clone();
        klon.attr('id', 'lineChave_' + rowId)
            .insertBefore($("#rowChave"))
            .removeClass('hide')
            .find('input')
            .each(function () {
                $(this)
                    .attr('name', $(this).attr('id').replace(/_(\d*)$/, "[]"))
                    .attr('id', $(this).attr('id').replace(/_(\d*)$/, "_" + rowId))
                    .on('blur', function () {
                        calculaValor()
                    });
                if ($(this).attr('name').indexOf('vl') != -1)
                    $(this).addClass('adicionais');
                if ($(this).attr('name').indexOf('reajustavel') != -1)
                    $(this).attr('checked','checked').parent().addClass('checked');
            });
        var valor = klon.find('input').eq(0),
            qtd = klon.find('input').eq(1);
        valor.mask('000.000.000.000.000,00', {reverse: true});
        qtd.mask('00000', {reverse: true});
        $('#registerForm')
            .bootstrapValidator('addField', valor.attr('name'), {
                validators: {
                    number: {
                        message: 'Apenas Dígitos'
                    }
                }
            })
            .bootstrapValidator('addField', qtd.attr('name'), {
                validators: {
                    notEmpty: {
                        message: 'Quantidade da Parc. é obrigatório'
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
            });
    });

    $("#removerChave").on('click', function () {
        var nrChaves = $("#nrChaveAdicional").val();
        $("#lineChave_" + nrChaves).remove();
        if (nrChaves >= 1)
            $("#nrChaveAdicional").val(parseInt(nrChaves) - 1);
        calculaValor()
    });

    function calculaValor() {

        $('#vlTotalLote').html('0,00');

        var vlDesconto = moedaToFloat($('[name="vlDesconto"]').val()),
            vlParcelaNormal = moedaToFloat($('[name="vlParcelaNormal"]').val()),
            qtParcelaNormal = $('[name="qtdParcelaNormal"]').val(),
            vlSinal = moedaToFloat($('[name="vlSinal"]').val()),
            vlIntercalada = moedaToFloat($('[name="vlIntercalada"]').val()),
            qtIntercalada = $('[name="qtdIntercalada"]').val(),
            vlChave = moedaToFloat($('[name="vlChave"]').val()),
            qtChave = $('[name="qtdParcChave"]').val(),
            vlAdicionais = 0;
        var soma;

        if (qtParcelaNormal != '') {
            qtParcelaNormal = parseInt(qtParcelaNormal)
        }
        if (qtIntercalada != '') {
            qtIntercalada = parseInt(qtIntercalada)
        }
        if (qtChave != '') {
            qtChave = parseInt(qtChave)
        }

        //Adiciona os valores de parcelas adicionais ao total
        $.each($(".adicionais"), function () {
            var id = $(this).attr('id'),
                otherId = id.replace('vl', 'qt'),
                other = $("#" + otherId),
                valor = moedaToFloat($(this).val()),
                qtd = parseInt(other.val());

            if (valor > 0 && qtd > 0) {
                vlAdicionais += (valor * qtd);
            }
        });

        soma = ((vlParcelaNormal * qtParcelaNormal) + (vlSinal) + (vlIntercalada * qtIntercalada) + (vlChave * qtChave)) - vlDesconto;
        soma += vlAdicionais;

        if (soma > 0)
            $('#vlTotalLote').html(floatToMoeda(soma));
    }

    $('.adicionais').on('blur change', function () {
        calculaValor();
    });

    $('.calculaValores').on('blur change', function () {
        calculaValor();
    });

    //Cadastro de pessoa Fisica ou Juridica
    $('.buttonPessoa').on('click', function () {

        //Ativando o plugin para todos os select

        var tpPessoa = $(this).val();

        $.ajax({
            type: "POST",
            url: "index.php?m=dashboard&c=dashboard&a=exibeViewCadastro",
            data: {tpPessoa: tpPessoa},
            success: function (json) {

                $('#buttonPessoaDiv').hide();

                //Renderiza a view no modal
                $('#cadastroPessoa').html(json);

                //Ativando o plugin para todos os select
                $('[name="estCivil"]').select2({
                    placeholder: "Escolha uma opção...",
                    allowClear: true
                });

                //Ativando o plugin para todos os select
                $('[name="tpDocumento"]').select2({
                    placeholder: "Escolha uma opção...",
                    allowClear: true
                });

                //Ativando o plugin para todos os select
                $('[name="tpDocumentoConjuge"]').select2({
                    placeholder: "Escolha uma opção...",
                    allowClear: true
                });

                //Ativa plugin select2 e campos com muitos dados
                $('[name="cdCidadeNascimento"], [name="cdCidadeConjuge"], [name="cdCidade"]').select2({
                    placeholder: "Digite o nome da cidade",
                    allowClear: true,
                    minimumInputLength: 2,
                    language: {
                        inputTooShort: function () {
                            return "Digite no mínimo 2 letras para pesquisar.";
                        }
                    }
                });

                //Ativa plugin iCheck nos imput's radio
                $('input:not(.ios-switch)').iCheck({
                    checkboxClass: 'icheckbox_square-aero',
                    radioClass: 'iradio_square-aero',
                    increaseArea: '20%' // optional
                });

                //Formulario de Pessoa Fisica
                var registerPessoa = {
                    nrCep: {
                        validators: {
                            notEmpty: {
                                message: 'O campo CEP é obrigatório'
                            }
                        }
                    },
                    endereco: {
                        validators: {
                            notEmpty: {
                                message: 'O campo Endereço é obrigatório'
                            }
                        }
                    },
                    nmBairro: {
                        validators: {
                            notEmpty: {
                                message: 'O campo Bairro é obrigatório'
                            }
                        }
                    },
                    cdCidade: {
                        validators: {
                            notEmpty: {
                                message: 'O campo Cidade é obrigatório'
                            }
                        }
                    },
                    dtNascimento: {
                        validators: {
                            date: {
                                format: 'DD/MM/YYYY',
                                message: 'Data inválida'
                            }
                        }
                    }
                };

                if (tpPessoa == 'F') {
                    registerPessoa['nmPessoa'] = {
                        validators: {
                            notEmpty: {
                                message: 'O Nome da Pessoa é obrigatório'
                            }
                        }
                    };
                    registerPessoa['nrCpf'] = {
                        validators: {
                            id: {
                                country: 'BR',
                                message: 'CPF Inválido'
                            },
                            callback: {
                                message: 'O campo CPF é obrigatório',
                                callback: function (value, validator, $field) {
                                    //Testa se é estrangeiro ou brasileiro para obrigatoriedade
                                    var estrangeiro = $("[name='estrangeiro']:checked").val();

                                    if (estrangeiro == 'E') {
                                        return true;
                                    } else {
                                        if (value == '') {
                                            return false;
                                        } else {
                                            return true;
                                        }
                                    }

                                }
                            },
                            remote: {
                                message: 'O CPF já está sendo utilizado',
                                data: {
                                    nrCpf: 'nrCpf'
                                },
                                url: 'index.php?m=dashboard&c=dashboard&a=verificaCpfCnpj',
                                type: 'POST'
                            }
                        }
                    };
                    registerPessoa['nrCpfConjuge'] = {
                        enabled: false,
                        validators: {
                            callback: {
                                message: 'O campo CPF é obrigatório',
                                callback: function (value, validator, $field) {
                                    //Testa se é estrangeiro ou brasileiro para obrigatoriedade
                                    var estrangeiro = $("[name='estrangeiroConjuge']:checked").val();

                                    if (estrangeiro == 'E') {
                                        return true;
                                    } else {
                                        if (value == '') {
                                            return false;
                                        } else {
                                            return true;
                                        }
                                    }

                                }
                            },
                            id: {
                                country: 'BR',
                                message: 'CPF Inválido'
                            }
                        }
                    };
                    registerPessoa['nmPessoaConjuge'] = {
                        enabled: false,
                        validators: {
                            notEmpty: {
                                message: 'O Nome do Conjuge é obrigatório'
                            }
                        }
                    };
                    registerPessoa['nrRgConjuge'] = {
                        enabled: false,
                        validators: {
                            notEmpty: {
                                message: 'O Nome do Conjuge é obrigatório'
                            }
                        }
                    };
                    registerPessoa['nrRg'] = {
                        validators: {
                            notEmpty: {
                                message: 'O campo Documento é obrigatório'
                            }
                        }
                    };
                    registerPessoa['nmProfissao'] = {
                        validators: {
                            notEmpty: {
                                message: 'O campo Profissão é obrigatório'
                            }
                        }
                    };
                    registerPessoa['nmNacionalidade'] = {
                        validators: {
                            notEmpty: {
                                message: 'O campo Nacionalidade é obrigatório'
                            }
                        }
                    };
                    registerPessoa['cdCidadeNascimento'] = {
                        validators: {
                            enabled: true,
                            notEmpty: {
                                message: 'O campo Cidade de Nascimento é obrigatório'
                            }
                        }
                    };
                    registerPessoa['estCivil'] = {
                        validators: {
                            notEmpty: {
                                message: 'O campo Estado Civil é obrigatório'
                            }
                        }
                    };
                    registerPessoa['tpDocumento'] = {
                        validators: {
                            notEmpty: {
                                message: 'O campo Tipo de Documento é obrigatório'
                            }
                        }
                    };
                    registerPessoa['tpDocumentoConjuge'] = {
                        enabled: false,
                        validators: {
                            notEmpty: {
                                message: 'O campo Tipo de Documento é obrigatório'
                            }
                        }
                    };
                }
                else {
                    registerPessoa['nmPessoa'] = {
                        validators: {
                            notEmpty: {
                                message: 'A razão social é obrigatória'
                            }
                        }
                    };
                    registerPessoa['nmFantasia'] = {
                        validators: {
                            notEmpty: {
                                message: 'O nome fantasia é obrigatório'
                            }
                        }
                    };
                    registerPessoa['nrCnpj'] = {
                        validators: {
                            cnpj: {
                                message: 'O CNPJ Não é válido'
                            },
                            notEmpty: {
                                message: 'O CNPJ é obrigatório'
                            },
                            remote: {
                                message: 'O CNPJ já está sendo utilizado',
                                data: {
                                    nrCnpj: 'nrCnpj'
                                },
                                url: 'index.php?m=dashboard&c=dashboard&a=verificaCpfCnpj',
                                type: 'POST'
                            }
                        }
                    };

                    registerPessoa['cdRepresentantes[]'] = {
                        validators: {
                            notEmpty: {
                                message: 'O campo Representantes é obrigatório'
                            }
                        }
                    };
                }

                formValidator(registerPessoa, '#registerPessoa');

                //Entra no if quando está sendo feito a venda de um lote que foi reservado
                if (getUrl('reserva') == 1) {
                    var SPMaskBehavior = function (val) {
                            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                        },
                        spOptions = {
                            onKeyPress: function (val, e, field, options) {
                                field.mask(SPMaskBehavior.apply({}, arguments), options);
                            }
                        };

                    $('[name="nmPessoa"]').val($('[name="nmPessoaReserva"]').val());
                    $('[name="nrTelefone"]').val($('[name="telefonePessoaReserva"]').val()).mask(SPMaskBehavior, spOptions);
                    $('[name="nrCpf"]').val($('[name="cpfPessoaReserva"]').val());
                    $('[name="dsObservacao"]').val($('[name="obsReserva"]').val());
                    $('#registerPessoa')
                        .bootstrapValidator('validateField', 'nmPessoa')
                        .bootstrapValidator('validateField', 'nrCpf');
                }

                if (tpPessoa == 'F') {

                    //Exibe os campos do cônjuge
                    $('[name="estCivil"]').on('change', function () {

                        if ($(this).val() == 'CAS') {

                            $('.infoConjuge').show('slow');

                            $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'nrCpfConjuge', true);
                            $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'nmPessoaConjuge', true);
                            $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'nrRgConjuge', true);
                            $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'tpDocumentoConjuge', true);

                            $('#registerPessoa').bootstrapValidator('validateField', 'nrCpfConjuge');
                            $('#registerPessoa').bootstrapValidator('validateField', 'nmPessoaConjuge');
                            $('#registerPessoa').bootstrapValidator('validateField', 'nrRgConjuge');
                            $('#registerPessoa').bootstrapValidator('validateField', 'tpDocumentoConjuge');

                            $('[name="utilizarEnderecoConjuge"]').on('ifChecked', function () {

                                if ($(this).val() == '1') {
                                    $('.enderecoConjuge').hide('slow');
                                } else {
                                    $('.enderecoConjuge').show('slow');
                                }
                            });
                        } else {
                            $('.infoConjuge').hide('slow');
                            $('.enderecoConjuge').hide('slow');
                        }
                    });

                    //Remove a obrigatoriedade do cpf caso seja estrangeiro
                    $('[name="estrangeiro"]').on('ifChecked', function () {
                        if ($(this).val() == 'E') {
                            $("#dangerCPF").html('');
                            $("#dangerCdCidadeNascimento").html('');
                            $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'cdCidadeNascimento', false);
                        } else {
                            $("#dangerCPF").html('*');
                            $("#dangerCdCidadeNascimento").html('*');
                            $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'cdCidadeNascimento', true);
                        }
                    });

                    //Remove a obrigatoriedade do cpf caso seja estrangeiro
                    $('[name="estrangeiroConjuge"]').on('ifChecked', function () {
                        if ($(this).val() == 'E') {
                            $("#dangerCpfConjuge").html('');
                        } else {
                            $("#dangerCpfConjuge").html('*');
                        }
                        $('#registerPessoa').bootstrapValidator('enableFieldValidators', 'nrCpfConjuge', true);
                        $('#registerPessoa').bootstrapValidator('validateField', 'nrCpfConjuge');
                    });

                    //Exibe os campos do endereço do cônjuge
                    $('[name="utilizarEnderecoConjuge"]').on('ifChecked', function () {
                        if ($(this).val() == '1') {
                            $('.enderecoConjuge').hide('slow');
                        } else {
                            $('.enderecoConjuge').show('slow');
                        }
                    });

                    //Tras as informações do conjuge de acordo com o CPF digitado
                    $("[name='nrCpfConjuge']").on('keyup blur', function () {

                        if ($('[name="estrangeiroConjuge"]').val() != 'E') {

                            //Retira os pontos e traco
                            var nrCpfConjuge = $(this).removeNot({pattern: /[^0-9]+/g});

                            if (nrCpfConjuge.length == 11) {

                                $.ajax({
                                    type: "POST",
                                    url: "index.php?m=dashboard&c=dashboard&a=verificaCpf",
                                    data: {nrCpf: $(this).val()},
                                    success: function (json) {
                                        var json = JSON.parse(json),

                                            sexo = $('[id="SexoConjugeFeminino"]');


                                        if (json.valid == false) {

                                            $('[name="nmPessoaConjuge"]').val(json.nm_pessoa);
                                            $('[name="nrRgConjuge"]').val(json.nr_rg);
                                            $('[name="dtNascimentoConjuge"]').val(json.dt_nascimento);
                                            $('[name="nmProfissaoConjuge"]').val(json.nm_profissao);
                                            $('[name="nmNacionalidadeConjuge"]').val(json.nm_nacionalidade);
                                            $('[name="nrTelefoneConjuge"]').val(json.nr_telefone);
                                            $('[name="idConjuge"]').val(json.id);

                                            if (json.sexo == 'M') {
                                                sexo = $('[id="SexoConjugeMasculino"]');
                                            }

                                            sexo.prop('checked', true);

                                            $('#nrCpfConjuge').prop('readonly', true);
                                            $('#divNmConjuge').removeClass('col-sm-6').addClass('col-sm-5');
                                            $('.divBtnLimparConjuge').show();

                                            if (json.enderecoConjuge) {

                                                //Marca a opção não
                                                $('[id="utilizarEnderecoConjugeN"]').iCheck('check');

                                                //Exibe todos campos de endereço
                                                $('.enderecoConjuge').show('fast');

                                                //Coloca as informaçoes nos campos
                                                $('[name="nrCepConjuge"]').val(json.nr_cep);
                                                $('[name="enderecoConjuge"]').val(json.endereco);
                                                $('[name="nmBairroConjuge"]').val(json.nm_bairro);
                                                $('[name="cdCidadeConjuge"]').val(json.cd_cidade).trigger("change");

                                            } else {

                                                //Marca a opção não
                                                $('[id="utilizarEnderecoConjugeS"]').iCheck('check');

                                                //Marca a opção sim
                                                $('.enderecoConjuge').hide('fast');

                                                //Limpa os campos caso haja informações
                                                $(
                                                    '[name="nrCepConjuge"], ' +
                                                    '[name="enderecoConjuge"], ' +
                                                    '[name="nmBairroConjuge"], ' +
                                                    '[name="cidadeConjuge"]'
                                                ).val('');
                                            }
                                        }
                                    }
                                });
                            }
                        }
                    });

                }
                else {
                    select2RemoteData(
                        '[name="cdRepresentantes[]"]',
                        true,
                        false,
                        'Digite o nome do representante e selecione',
                        '?m=dashboard&c=dashboard&a=findPessoasFisicasByNmPessoa'
                    );

                    //Revalidando o campo após cada escolha
                    $('[name="cdRepresentantes[]"]').change(function (e) {
                        $('#registerPessoa').bootstrapValidator('validateField', 'cdRepresentantes[]');
                    });
                }

                //Faz a validação e inserção da pessoa física
                $('#btnSalvar').on('click', function (e) {

                    var $form = $('#registerPessoa'),
                        bv = $form.data('bootstrapValidator'),
                        params = $form.serializeArray(),
                        formData = new FormData();

                    $.each(params, function (i, val) {
                        formData.append(val.name, val.value);
                    });

                    //Faz a validação do formulário inteiro
                    bv.validate();

                    //Verifica se o formulário inteiro está validado
                    if (bv.isValid()) {
                        $.ajax({
                            url: "index.php?m=dashboard&c=dashboard&a=cadastrarPessoa",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            type: 'POST',
                            beforeSend: function () {
                                $('#btnSalvar').disable = true;
                            },
                            success: function (json) {
                                var json = JSON.parse(json);

                                if (json.sucess == 1) {
                                    $('.mAdicionaPessoa').modal('hide');

                                    //Renderiza a view no modal
                                    $('#cadastroPessoa').html('');
                                    $('#buttonPessoaDiv').show();

                                    $('[name="idCliente"]').select2({
                                        data: [{id: json.idPessoa, text: json.nmPessoa}],
                                        placeholder: "Escolha uma opção...",
                                        allowClear: true,
                                        ajax: {
                                            url: '?m=dashboard&c=dashboard&a=findPessoasByNome',
                                            dataType: "json",
                                            type: "GET",
                                            data: function (params) {

                                                var queryParameters = {
                                                    term: params.term
                                                }
                                                return queryParameters;
                                            },
                                            processResults: function (data) {
                                                return {
                                                    results: $.map(data, function (item) {

                                                        return {
                                                            text: item.text,
                                                            id: item.value,
                                                            info: item.info
                                                        }
                                                    })
                                                };
                                            }
                                        }
                                    });

                                    $('[name="idCliente"]').val(json.idPessoa).trigger("change");

                                    $('[name="idCliente"]').focus();
                                    $('[name="idCliente"]').prop('disabled', false);

                                } else {
                                    alert(json.erro);
                                }
                                $('#btnSalvar').disable = false;

                            }
                        });
                    }

                });

                //Esconde o formulário e exibe os botões
                $('#buttonBack').on('click', function () {
                    $('#cadastroPessoa').html('');
                    $('#buttonPessoaDiv').show();
                    $('#cadastroPessoaJuridica').hide();
                    $('#cadastroPessoaFisica').hide();
                });

            }
        });

    });

    //Formulario Venda de Lote
    var camposForm = {
        estCivil: {
            validators: {
                notEmpty: {
                    message: 'O estCivil é obrigatória'
                }
            }
        },
        idCliente: {
            validators: {
                notEmpty: {
                    message: 'O Comprador Responsável Pagamento é obrigatória'
                },
                callback: {
                    message: 'Essa mesma pessoa foi escolhida em Outros Compradores',
                    callback: function (value, validator, $field) {
                        var outrosCompradores = $('[name="outrosCompradores[]"]').val(),
                            compradorPagamento = value,
                            valid = true;

                        if (outrosCompradores) {
                            $.each(outrosCompradores, function (index, value) {
                                if (value == compradorPagamento) {
                                    valid = false;
                                }
                            });
                        }

                        return valid;
                    }
                }
            }
        },
        'outrosCompradores[]': {
            validators: {
                callback: {
                    message: 'O Comprador Responsável Pagamento não pode ser escolhido em Outros Compradores',
                    callback: function (value, validator, $field) {
                        var compradorPagamento = $('[name="idCliente"]').val(),
                            outrosCompradores = value,
                            valid = true;

                        if (outrosCompradores) {
                            $.each(outrosCompradores, function (index, value) {
                                if (value == compradorPagamento) {
                                    valid = false;
                                }
                            });
                        }

                        return valid;
                    }
                }
            }
        },
        'vlNormalAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parcela é obrigatório'
                }
            }
        },
        'qtNormalAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'Quantidade da Parc. Normal é obrigatório'
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
        'vlIntercaladaAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parcela é obrigatório'
                }
            }
        },
        'nrIntercaladaAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parcela é obrigatório'
                }
            }
        },
        'dtIntercaladaAdicional[]': {
            enabled: false,
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
        'qtIntercaladaAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'Quantidade da Parc. Normal é obrigatório'
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
        'vlChaveAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parcela é obrigatório'
                }
            }
        },
        'qtChaveAdicional[]': {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'Quantidade da Parc. Normal é obrigatório'
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
        cdBanco: {
            validators: {
                notEmpty: {
                    message: 'O Banco é obrigatório'
                }
            }
        },
        idCorretor: {
            validators: {
                notEmpty: {
                    message: 'O Corretor é obrigatório'
                }
            }
        },
        nrProposta: {
            validators: {
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
        vlParcelaNormal: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parc. Normal é obrigatório'
                }
            }
        },
        qtdParcelaNormal: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'A Quantidade da Parc. Normal é obrigatório'
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
        dtParcelaNormal: {
            enabled: false,
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
        vlSinal: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parc. Sinal é obrigatório'
                }
            }
        },
        qtdSinal: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'A Quantidade da Parc. Sinal é obrigatório'
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
        vlIntercalada: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O Valor da Parc. Intercalada é obrigatório'
                }
            }
        },
        qtdIntercalada: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'Quantidade da Parc. Intercalada é obrigatório'
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
        nrFrequencia: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'A Frequência da Parc. Intercalada é obrigatório'
                },
                regexp: {
                    regexp: /^[0-9]+$/i,
                    message: 'Digite apenas números no campo'
                }
            }
        },
        dtIntercalada: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'A Data da Parc. Intercalada é obrigatório'
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
        flCoincidirIntercaladas: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'Uma opção deve ser selecionada'
                }
            }
        },
        vlChave: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'O valor da Parc. Chave é obrigatório'
                }
            }
        },
        qtdParcChave: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'Quantidade da Parc. Chave é obrigatório'
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
        dtParcChave: {
            enabled: false,
            validators: {
                notEmpty: {
                    message: 'A Data da Parc. Chave é obrigatório'
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

    formValidator(camposForm);

    $(document)

        // Cria option automaticamente de acordo com a quantidade de parcelas sinal escolhidas
        .on('change', '[name="qtdSinal"]', function () {

            var qtdSinal = $(this).val(),
                conteudo = '',
                isEmpty = qtdSinal > 0,
                valorTotalParcela = moedaToFloat($('#vlSinal').val());

            //Ativando os outros campos como obrigatorios
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlSinal', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdSinal', isEmpty);

            if (isEmpty) {
                //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlSinal')
                    .bootstrapValidator('validateField', 'qtdSinal');
            } else {
                $('#vlSinal').val('');
                //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlSinal')
                    .bootstrapValidator('validateField', 'qtdSinal');
                $('#TabelaParcSinalMsg2').html('');
                $('#TabelaParcSinalMsg').html('');
                $('#flIncluirContratoSinalNao').iCheck('check');
            }

            if (qtdSinal > 0) {

                $('#tabelaParcSinais').show();

                //Cria os campos e insere na pagina html
                for (var i = 1; i <= qtdSinal; i++) {
                    conteudo += '<tr>';
                    conteudo += '<td align="center">' + i + '</td>';
                    conteudo += '<td>';
                    conteudo += '<div class="input-group">';
                    conteudo += '<span class="input-group-addon">R$</span>';
                    conteudo += '<input type="text" class="form-control maskDinheiro camposValorParcSinal" name="valor_' + i + '" id="valor_' + i + ' onBlur="verificaValor();" value="' + floatToMoeda(valorTotalParcela / qtdSinal) + '" />';
                    conteudo += '</div>';
                    conteudo += '</td>';
                    conteudo += '<td><input type="text" class="form-control dtpicker camposDataParcSinal" style="width:200px;" name="data_' + i + '" id="dt_' + i + '" /></td>';
                    conteudo += '</tr>';

                    $('#tabelaParcSinais > tbody').html(conteudo);
                }

                // Reativa a máscara nos campos adicionados dinamicamente
                $(".maskDinheiro").maskMoney({
                    allowZero: false,
                    thousands: '.',
                    decimal: ','
                });

                // Reativa o datepicker nos campos adicionados dinamicamente
                $('.dtpicker').datetimepicker({
                    format: "DD/MM/YYYY",
                    pickTime: false
                });

                // Soma os valores de todas parcelas sinais e compara com o valor total da parcela sinal
                $('.camposDataParcSinal, .camposValorParcSinal').blur(function () {

                    var soma = 0,
                        valorParcelas = 0,
                        temCampoValorVazio = false,
                        temCampoDataVazio = false,
                        valorTotalParcela = $('#vlSinal').val(),
                        valorTotalParcela = replaceAll(valorTotalParcela, ".", ""),
                        valorTotalParcela = parseFloat(replaceAll(valorTotalParcela, ",", "."));

                    // Percorre todos campos que possuem a classe camposValorParc e soma os valores dos mesmos
                    $('.camposValorParcSinal').each(function () {
                        if ($(this).val() != '') {
                            valorParcelas = replaceAll($(this).val(), ".", "");
                            valorParcelas = replaceAll(valorParcelas, ",", ".");
                            if (valorParcelas > 0) soma = soma + parseFloat(valorParcelas);
                        } else {
                            temCampoValorVazio = true;
                        }
                    });

                    //Percorre todos campos de datas das parcelas e verifica se algum está vazio
                    $('.camposDataParcSinal').each(function () {
                        if ($(this).val() == '') {
                            temCampoDataVazio = true;
                        }
                    });

                    // Ativa os avisos de erros ou sucesso
                    if (temCampoValorVazio) {
                        $('#TabelaParcSinalDiv').attr('class', 'form-group col-sm-12 has-feedback has-error');
                        $('small[id="TabelaParcSinalMsg3"]').attr('style', 'display:block;');
                        $('small[id="TabelaParcSinalMsg"]').attr('style', 'display:none;');
                        $('small[id="TabelaParcSinalMsg2"]').attr('style', 'display:none;');
                        $('#btnSalvar').prop('disabled', true);
                    } else if (temCampoDataVazio) {
                        $('#TabelaParcSinalDiv').attr('class', 'form-group col-sm-12 has-feedback has-error');
                        $('small[id="TabelaParcSinalMsg"]').attr('style', 'display:none;');
                        $('small[id="TabelaParcSinalMsg2"]').attr('style', 'display:block;');
                        $('small[id="TabelaParcSinalMsg3"]').attr('style', 'display:none;');
                        $('#btnSalvar').prop('disabled', true);
                    } else if (soma != valorTotalParcela) {
                        $('#TabelaParcSinalDiv').attr('class', 'form-group col-sm-12 has-feedback has-error');
                        $('small[id="TabelaParcSinalMsg"]').attr('style', 'display:block;');
                        $('small[id="TabelaParcSinalMsg2"]').attr('style', 'display:none;');
                        $('small[id="TabelaParcSinalMsg3"]').attr('style', 'display:none;');
                        $('#btnSalvar').prop('disabled', true);
                    } else {
                        $('#TabelaParcSinalDiv').attr('class', 'form-group col-sm-12 has-feedback has-success');
                        $('small[id="TabelaParcSinalMsg"]').attr('style', 'display:none;');
                        $('small[id="TabelaParcSinalMsg2"]').attr('style', 'display:none;');
                        $('#btnSalvar').prop('disabled', false);
                    }

                });

            } else {
                $('#tabelaParcSinais').hide();
            }
        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Normais caso algum campo seja preeenchido
        .on('keyup', '[name="vlParcelaNormal"], [name="qtdParcelaNormal"],[name="dtParcelaNormal"]', function () {

            var vlParcela = replaceAll($('#vlParcelaNormal').val(), ".", ""),
                vlParcela = replaceAll(vlParcela, ",", "."),
                isEmpty = vlParcela > 0 || $('[name="qtdParcelaNormal"]').val().length > 0 || $('[name="dtParcelaNormal"]').val().length > 0,
                dtParcelaNormal;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlParcelaNormal', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdParcelaNormal', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'dtParcelaNormal', isEmpty);

            if (isEmpty)
            //Revalida os campos
                $('#registerForm').bootstrapValidator('validateField', 'vlParcelaNormal')
                    .bootstrapValidator('validateField', 'qtdParcelaNormal')
                    .bootstrapValidator('validateField', 'dtParcelaNormal');

            verificaUltimaData(
                $('[name="dtParcelaNormal"]').val(),
                $('[name="qtdParcelaNormal"]').val(),
                $('[name="dtIntercalada"]').val(),
                $('[name="qtdIntercalada"]').val(),
                $('[name="flCoincidirIntercaladas"]:checked').val(),
                $('[name="nrFrequencia"]').val()
            );

        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Adicionais caso algum campo seja preeenchido
        .on('keyup', '[name="vlNormalAdicional[]"], [name="qtNormalAdicional[]"]', function () {
            var rowNum = $(this).attr('id').split('_')[1],
                id = $(this).attr('id').split('_')[0],
                message = $(this).parents('.form-group').find('.help-block'),
                otherId,
                otherMessage,
                erro = false;

            //Verifica o id para setar os elementos
            if (id == 'qtNormalAdicional') {
                otherId = '#vlNormalAdicional_' + rowNum;
            }
            else {
                otherId = '#qtNormalAdicional_' + rowNum;
            }
            otherMessage = $(otherId).parents('.form-group').find('.help-block');

            //Valida os campos de acordo (valido se tem valores nos 2 e não necessario se nenhum preenchido
            if ($(this).val() == 0) {
                $(this).parents('.form-group').removeClass('has-success').addClass('has-error');
                message.html('O valor deve ser maior que zero.').show();
                erro = true;
            }
            if ($(this).val() === '') {
                $(this).parents('.form-group').removeClass('has-success').addClass('has-error');
                message.html('O Campo é Obrigatório.').show();
                erro = true;
            }
            if ($(otherId).val() === '') {
                $(otherId).parents('.form-group').removeClass('has-success').addClass('has-error');
                otherMessage.html('O Campo é Obrigatório.').show();
            }
            if (!erro) {
                $(this).parents('.form-group').removeClass('has-error').addClass('has-success');
                message.hide();
            }
            if ($(this).val() == '' && $(otherId).val() == '') {
                $(this).parents('.form-group').removeClass('has-error').removeClass('has-success');
                $(otherId).parents('.form-group').removeClass('has-error').removeClass('has-success');
                message.hide();
                otherMessage.hide();
            }
        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Adicionais caso algum campo seja preeenchido
        .on('keyup', '[name="vlIntercaladaAdicional[]"], [name="qtIntercaladaAdicional[]"], [name="nrIntercaladaAdicional[]"], [name="dtIntercaladaAdicional[]"]', function () {
            var rowNum = $(this).attr('id').split('_')[1],
                id = $(this).attr('id').split('_')[0],
                valores = false,
                erro = false;

            //Valida os campos de acordo (valido se tem valores nos 2 e não necessario se nenhum preenchido
            $.each($('.intercaladaAdicional_' + rowNum), function () {
                if ($(this).attr('id').split('_')[0] == 'dtIntercaladaAdicional') {
                    if (!validaData($(this).val())) {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('A data inserida é inválida.').show();
                        erro = true;
                        valores = true;
                    }
                    else if ($(this).val() === '') {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('O Campo é Obrigatório.').show();
                        erro = true;
                    } else {
                        $(this).parents('.form-group').removeClass('has-error').addClass('has-success')
                            .find('.help-block').hide();
                        valores = true;
                    }
                }
                else {
                    if ($(this).val() == 0) {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('O valor deve ser maior que zero.').show();
                        erro = true;
                    }
                    else if ($(this).val() === '') {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('O Campo é Obrigatório.').show();
                        erro = true;
                    } else {
                        $(this).parents('.form-group').removeClass('has-error').addClass('has-success')
                            .find('.help-block').hide();
                        valores = true;
                    }
                }
            });

            if (!erro) {
                $.each($('.intercaladaAdicional_' + rowNum), function () {
                    $(this).parents('.form-group').removeClass('has-error').addClass('has-success')
                        .find('.help-block').hide();
                });
            }

            if (!valores) {
                $.each($('.intercaladaAdicional_' + rowNum), function () {
                    $(this).parents('.form-group').removeClass('has-error').removeClass('has-success')
                        .find('.help-block').hide();
                });
            }
        })

        //Verifica manualmente o click dos elementos checkbox criados dinamicamente
        .on('click', '.iCheck-helper', function () {
            var input = $(this).parent().find('input');

            if (input.is(":checked")) {
                input.prop("checked", false);
                $(this).parent().removeClass('checked')
            } else {
                input.prop("checked", true);
                $(this).parent().addClass('checked')
            }
        })

        .on('changeDate', '[name="dtIntercaladaAdicional[]"]', function () {
            var rowNum = $(this).attr('id').split('_')[1],
                id = $(this).attr('id').split('_')[0],
                valores = false,
                erro = false;

            //Valida os campos de acordo (valido se tem valores nos 2 e não necessario se nenhum preenchido
            $.each($('.intercaladaAdicional_' + rowNum), function () {
                if ($(this).attr('id').split('_')[0] == 'dtIntercaladaAdicional') {
                    if (!validaData($(this).val())) {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('A data inserida é inválida.').show();
                        erro = true;
                        valores = true;
                    }
                    else if ($(this).val() === '') {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('O Campo é Obrigatório.').show();
                        erro = true;
                    } else {
                        $(this).parents('.form-group').removeClass('has-error').addClass('has-success')
                            .find('.help-block').hide();
                        valores = true;
                    }
                }
                else {
                    if ($(this).val() == 0) {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('O valor deve ser maior que zero.').show();
                        erro = true;
                    }
                    else if ($(this).val() === '') {
                        $(this).parents('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.help-block').html('O Campo é Obrigatório.').show();
                        erro = true;
                    } else {
                        $(this).parents('.form-group').removeClass('has-error').addClass('has-success')
                            .find('.help-block').hide();
                        valores = true;
                    }
                }
            });

            if (!erro) {
                $.each($('.intercaladaAdicional_' + rowNum), function () {
                    $(this).parents('.form-group').removeClass('has-error').addClass('has-success')
                        .find('.help-block').hide();
                });
            }

            if (!valores) {
                $.each($('.intercaladaAdicional_' + rowNum), function () {
                    $(this).parents('.form-group').removeClass('has-error').removeClass('has-success')
                        .find('.help-block').hide();
                });
            }
        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Adicionais caso algum campo seja preeenchido
        .on('keyup', '[name="vlChaveAdicional[]"], [name="qtChaveAdicional[]"]', function () {
            var rowNum = $(this).attr('id').split('_')[1],
                id = $(this).attr('id').split('_')[0],
                message = $(this).parents('.form-group').find('.help-block'),
                otherId,
                otherMessage,
                erro = false;

            //Verifica o id para setar os elementos
            if (id == 'qtChaveAdicional') {
                otherId = '#vlChaveAdicional_' + rowNum;
            }
            else {
                otherId = '#qtChaveAdicional_' + rowNum;
            }
            otherMessage = $(otherId).parents('.form-group').find('.help-block');

            //Valida os campos de acordo (valido se tem valores nos 2 e não necessario se nenhum preenchido
            if ($(this).val() == 0) {
                $(this).parents('.form-group').removeClass('has-success').addClass('has-error');
                message.html('O valor deve ser maior que zero.').show();
                erro = true;
            }
            if ($(this).val() === '') {
                $(this).parents('.form-group').removeClass('has-success').addClass('has-error');
                message.html('O Campo é Obrigatório.').show();
                erro = true;
            }
            if ($(otherId).val() === '') {
                $(otherId).parents('.form-group').removeClass('has-success').addClass('has-error');
                otherMessage.html('O Campo é Obrigatório.').show();
            }
            if (!erro) {
                $(this).parents('.form-group').removeClass('has-error').addClass('has-success');
                message.hide();
            }
            if ($(this).val() == '' && $(otherId).val() == '') {
                $(this).parents('.form-group').removeClass('has-error').removeClass('has-success');
                $(otherId).parents('.form-group').removeClass('has-error').removeClass('has-success');
                message.hide();
                otherMessage.hide();
            }
        })

        //Ativa validação de obrigatoriedade ema todos campos de Parcelas Normais caso o campo seja preeenchido
        .on('change', '[name="dtParcelaNormal"]', function () {

            var isEmpty = $(this).val().length > 0;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlParcelaNormal', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdParcelaNormal', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'dtParcelaNormal', isEmpty);
            if (isEmpty)
            //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlParcelaNormal')
                    .bootstrapValidator('validateField', 'qtdParcelaNormal')
                    .bootstrapValidator('validateField', 'dtParcelaNormal');
        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Sinais caso algum campo seja preeenchido
        .on('keyup', '[name="vlSinal"],[name="qtdSinal"]', function () {
            var vlParcela = replaceAll($('[name="vlSinal"]').val(), ".", ""),
                vlParcela = replaceAll(vlParcela, ",", "."),
                isEmpty = vlParcela > 0 || $('[name="qtdSinal"]').val > 0;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlSinal', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdSinal', isEmpty);

            if (isEmpty)
            //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlSinal')
                    .bootstrapValidator('validateField', 'qtdSinal');
        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Intercaladas caso algum campo seja preeenchido
        .on('keyup', '[name="vlIntercalada"],[name="qtdIntercalada"],[name="nrFrequencia"],[name="dtIntercalada"]', function () {
            var vlParcela = replaceAll($('[name="vlIntercalada"]').val(), ".", ""),
                vlParcela = replaceAll(vlParcela, ",", "."),
                isEmpty = vlParcela > 0 || $('[name="qtdIntercalada"]').val().length > 0 || $('[name="nrFrequencia"]').val().length > 0 || $('[name="dtIntercalada"]').val().length > 0;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlIntercalada', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdIntercalada', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'nrFrequencia', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'dtIntercalada', isEmpty);

            if (isEmpty)
            //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlIntercalada')
                    .bootstrapValidator('validateField', 'qtdIntercalada')
                    .bootstrapValidator('validateField', 'nrFrequencia')
                    .bootstrapValidator('validateField', 'dtIntercalada');

            verificaUltimaData(
                $('[name="dtParcelaNormal"]').val(),
                $('[name="qtdParcelaNormal"]').val(),
                $('[name="dtIntercalada"]').val(),
                $('[name="qtdIntercalada"]').val(),
                $('[name="flCoincidirIntercaladas"]:checked').val(),
                $('[name="nrFrequencia"]').val()
            );

        })
        // Ativa validação de obrigatoriedade em todos campos de Parcelas Intercaladas caso o campo seja preeenchido
        .on('change', '[name="dtIntercalada"]', function () {
            var isEmpty = $(this).val().length > 0;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlIntercalada', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdIntercalada', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'nrFrequencia', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'dtIntercalada', isEmpty);

            if (isEmpty)
            //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlIntercalada')
                    .bootstrapValidator('validateField', 'qtdIntercalada')
                    .bootstrapValidator('validateField', 'nrFrequencia')
                    .bootstrapValidator('validateField', 'dtIntercalada');

            verificaUltimaData(
                $('[name="dtParcelaNormal"]').val(),
                $('[name="qtdParcelaNormal"]').val(),
                $('[name="dtIntercalada"]').val(),
                $('[name="qtdIntercalada"]').val(),
                $('[name="flCoincidirIntercaladas"]:checked').val(),
                $('[name="nrFrequencia"]').val()
            );

        })

        // Ativa validação de obrigatoriedade em todos campos de Parcelas Chaves caso algum campo seja preeenchido
        .on('keyup', '[name="vlChave"],[name="qtdParcChave"],[name="dtParcChave"]', function () {
            var vlParcela = replaceAll($('[name="vlChave"]').val(), ".", ""),
                vlParcela = replaceAll(vlParcela, ",", "."),
                isEmpty = vlParcela > 0 || $('[name="qtdParcChave"]').val().length > 0;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlChave', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdParcChave', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'dtParcChave', isEmpty);

            if (isEmpty)
            //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlChave')
                    .bootstrapValidator('validateField', 'qtdParcChave')
                    .bootstrapValidator('validateField', 'dtParcChave');

            //Validação da quantidade de parcelas chaves que não pode ser menor ou igual à quantidade de parcelas normais
            if ($('[name="qtdParcChave"]').val() != '' && $('[name="qtdParcelaNormal"]').val() != '' && parseInt($('[name="qtdParcChave"]').val()) > parseInt($('[name="qtdParcelaNormal"]').val())) {
                $('#registerForm').bootstrapValidator('updateStatus', 'qtdParcChave', 'INVALID');
                $('small[id="qtdParcChaveMsg"]').attr('style', 'display:block;');
                $('small[data-bv-validator-for="qtdParcChave"]').attr('style', 'display:none;');
            } else {
                $('small[id="qtdParcChaveMsg"]').attr('style', 'display:none;');
            }
        })
        // Ativa validação de obrigatoriedade em todos campos de Parcelas Chaves caso o campo seja preeenchido
        .on('change', '[name="dtParcChave"]', function () {
            var isEmpty = $(this).val().length > 0;

            //Ativa a validação do bootstrapValidator nos campos
            $('#registerForm')
                .bootstrapValidator('enableFieldValidators', 'vlChave', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'qtdParcChave', isEmpty)
                .bootstrapValidator('enableFieldValidators', 'dtParcChave', isEmpty);

            if (isEmpty)
            //Revalida os campos
                $('#registerForm')
                    .bootstrapValidator('validateField', 'vlChave')
                    .bootstrapValidator('validateField', 'qtdParcChave')
                    .bootstrapValidator('validateField', 'dtParcChave');

            //Validação da quantidade de parcelas chaves que não pode ser menor ou igual à quantidade de parcelas normais
            if ($('[name="qtdParcChave"]').val() != '' && $('[name="qtdParcelaNormal"]').val() != '' && parseInt($('[name="qtdParcChave"]').val()) > parseInt($('[name="qtdParcelaNormal"]').val())) {
                $('#registerForm').bootstrapValidator('updateStatus', 'qtdParcChave', 'INVALID');
                $('small[id="qtdParcChaveMsg"]').attr('style', 'display:block;');
                $('small[data-bv-validator-for="qtdParcChave"]').attr('style', 'display:none;');
            } else {
                $('small[id="qtdParcChaveMsg"]').attr('style', 'display:none;');
            }
        })

        //Revalidando o campo após cada escolha
        .on('[name="outrosCompradores[]"]').change(function (e) {
            $('#registerForm').bootstrapValidator('validateField', 'outrosCompradores[]');
        });

    //funções realizadas quando já possui valores vindo da tabela de preço
    //Ativando validações de parcelas normais
    if ($('[name="vlParcelaNormal"]').val() > 0) {
        $('[name="vlParcelaNormal"]').trigger('keyup');
    }
    if ($('[name="qtdParcelaNormal"]').val() > 0) {
        $('[name="qtdParcelaNormal"]').trigger('keyup');
    }

    //Ativando validações de parcelas sinais
    if ($('[name="vlSinal"]').val() > 0) {
        $('[name="vlSinal"]').trigger('keyup');
    }
    if ($('[name="qtdSinal"]').val() > 0) {
        //Ativando função para exibir as parcelas sinais com valores e datas
        $('[name="qtdSinal"]').trigger('change');
        //Ativando validações dos campos na tabela
        $('.camposValorParcSinal').trigger('blur');
    }

    //Ativando validações de parcelas intercaladas
    if ($('[name="vlIntercalada"]').val() > 0) {
        $('[name="vlIntercalada"]').trigger('keyup');
    }
    if ($('[name="qtdIntercalada"]').val() > 0) {
        $('[name="qtdIntercalada"]').trigger('keyup');
    }
});

function verificaUltimaData(dtParcelaNormal, qtdParcelaNormal, dtIntercalada, qtdIntercalada, flCoincidirIntercaladas, nrFrequencia) {

    if (dtParcelaNormal && qtdParcelaNormal && dtIntercalada && qtdIntercalada && flCoincidirIntercaladas == 0 && nrFrequencia == 1) {

        $.ajax({
            type: 'POST',
            url: '?m=dashboard&c=dashboard&a=verificaUltimaData',
            data: {
                dtParcelaNormal: dtParcelaNormal,
                qtdParcelaNormal: qtdParcelaNormal,
                dtIntercalada: dtIntercalada,
                qtdIntercalada: qtdIntercalada,
                flCoincidirIntercaladas: flCoincidirIntercaladas
            },
            success: function (json) {

                json = JSON.parse(json);

                if (json.erro) {
                    $('#registerForm').bootstrapValidator('updateStatus', 'dtIntercalada', 'INVALID');
                    $('small[id="dtIntercaladaMsg"]').html(json.mensagem);
                    $('small[id="dtIntercaladaMsg"]').attr('style', 'display:block;');
                    $('small[data-bv-validator="notEmpty"],[data-bv-validator-for="dtIntercalada"]').attr('style', 'display:none;');
                    $('small[data-bv-validator="regexp"],[data-bv-validator-for="dtIntercalada"]').attr('style', 'display:none;');
                    $('small[data-bv-validator="date"],[data-bv-validator-for="dtIntercalada"]').attr('style', 'display:none;');
                } else {
                    $('#registerForm').bootstrapValidator('updateStatus', 'dtIntercalada', 'VALID');
                    $('small[id="dtIntercaladaMsg"]').attr('style', 'display:none;');
                }
            }
        });
    } else {
        $('small[id="dtIntercaladaMsg"]').attr('style', 'display:none;');
    }
}