function fecharAlert() {
    setTimeout(function () {
        $("div.alert").slideUp('show');
    }, 10000);
}

//Valida as datas inseridas no sistema
function validaData(data) {//dd/mm/aaaa

    var patternData = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
    if(!patternData.test(data)){
        return false;
    }

    var day = data.substring(0, 2),
        month = data.substring(3, 5),
        year = data.substring(6, 10);

    if ((month == '01') || (month == '03') || (month == '05') || (month == '07') || (month == '08') || (month == '10') || (month == '12')) {//mes com 31 dias
        if ((day < '01') || (day > '31')) {
            return false;
        }
    } else if ((month == '04') || (month == '06') || (month == '09') || (month == '11')) {//mes com 30 dias
        if ((day < '01') || (day > '30')) {
            return false;
        }
    } else if ((month == '02')) {//February and leap year
        if ((parseInt(year) % 4 == 0) && ( (parseInt(year) % 100 != 0) || (parseInt(year) % 400 == 0) )) {
            if ((day < '01') || (day > '29')) {
                return false;
            }
        } else {
            if ((day < '01') || (day > '28')) {
                return false;
            }
        }
    }
    return true;
}

$(document).ready(function () {

    //Ativando o plugin para todos os select's normais
    $('select').select2({
        language: {
            language: "pt-BR",
            noResults: function () {
                return "Registro não encontrado";
            }
        },
        allowClear: true,
        placeholder: "Escolha uma opção..."
    });

    //Ativando plugin para select's que possuem muitos dados na lista
    $('.select2BigData').select2({
        language: "pt-BR",
        allowClear: true,
        minimumInputLength: 2,
        language: {
            inputTooShort: function () {
                return "Digite no mínimo 2 letras para pesquisar.";
            }
        }
    });

    //Re valida quando a data é selecionada
    $('.dtpicker').on('changeDate', function () {
        $('#registerForm')
            .bootstrapValidator('enableFieldValidators', $(this).attr('name'), true)
            .bootstrapValidator('validateField', $(this).attr('name'));
    });

    //Ativando plugin para select's que podem selecionar mais de 1 opção
    $('.select2Multiplo').select2({
        language: "pt-BR",
        placeholder: "Escolha as opções...",
        multiple: true
    });

    //Função para o plugin select2 que não funciona corretamente em Modal
    $.fn.modal.Constructor.prototype.enforceFocus = function () {
    };

    //Cria o efeito para os alertas fecharem sozinhos após 10s
    if ($("div.alert") && $("div.alert").html()) {
        fecharAlert();
    }

    //Verifica se há checkbox da lista marcado, caso tenha, a função irá desmarcar
    $('#checkPrincipal').iCheck('destroy');
    $('#checkPrincipal').on('click', function () {
        if ($('#checkPrincipal').is(":checked")) {
            $("tbody :checkbox", $('#checkPrincipal').parents('table')).each(function () {

                if (!$(this).prop('checked')) {
                    $(this).prop('checked', true);
                    $(this).change();
                }
            });

        } else {
            $("tbody :checkbox").each(function () {

                if ($(this).prop('checked')) {
                    $(this).prop('checked', false);
                    $(this).change();
                }
            });
        }
    });

    /*
     * Deletar
     */

    //Função que busca os perfis selecionados e exibe no modal deletar
    $(document).on('click', '.btnDeletar', function () {

        var checks = document.querySelectorAll("input[name='linhas[]']:checked"),
            i = checks.length,
            conteudo = '';

        console.log(checks);
        console.log(i);

        //Caso nenhum checkbox esteja marcado, será exibido a mensagem.
        if (i == 0) {

            setTimeout(function () {
                $("div#alertAviso").slideDown('slow');
            }, 1);

            return false;

        } else {

            setTimeout(function () {
                $("div#alertAviso").slideUp('slow');
            }, 1);

            while (i--) {
                conteudo += '<tr>';
                conteudo += '<td>' + checks[i].value + '</td>';
                conteudo += '<td>' + checks[i].getAttribute('nomeExibidoNoDeletar') + '</td>';
                conteudo += '</tr>';
            }

            $("#tbodyDeletar").html(conteudo);
            $('#confirmDelet').modal('show');

        }

    });

    /*
     * VISUALIZAR
     */

    $(document).on('click', '.btn-visualizar', function () {

        var id = $(this).attr("value");

        $.ajax({
            type: 'POST',
            url: '?m=' + getUrl('m') + '&c=' + getUrl('c') + '&a=visualizar',
            dataType: 'html',
            data: {id: id},
            beforeSend: function () {
                $("#modalVisualizar .modal-body").html('<div align="center">Carregando. . . <a class="loading"></a></div>');
            },
            success: function (data) {

                $("#modalVisualizar .modal-body").html(data);
            }
        });
    });

    //Fecha a caixa de confirmação caso clicado em não da função nconfirm()
    $(document).on('click', '.notifyjs-metro-base .no', function () {
        $(this).trigger('notify-hide');
    });

    //Fecha a caixa de confirmação caso clicado em não da função nconfirm()
    $(document).on('click', '.notifyjs-metro-base .yes', function () {
        $(this).trigger('notify-hide');
    });

});


/**
 * Funcao para retornar o value de um parametro passado pela URL
 * @param parameter
 * @returns {boolean}
 */
function getUrl(parameter) {
    var loc = location.search.substring(1, location.search.length);
    var param_value = false;
    var params = loc.split("&");
    for (i = 0; i < params.length; i++) {
        param_name = params[i].substring(0, params[i].indexOf('='));
        if (param_name == parameter) {
            param_value = params[i].substring(params[i].indexOf('=') + 1)
        }
    }


    if (param_value) {
        return param_value;
    } else {
        return false;
    }
}

/**
 * Função responsável por realizar validações dos campos e já inclui as mascaras nos campos
 * @param fields array que recebe todos campos com tipos de validações
 */
function formValidator(fields, form, submitHandler) {

    var form = typeof form !== 'undefined' ? form : '#registerForm';
    var submitHandler = typeof submitHandler !== 'undefined' ? submitHandler : null;

    /**
     * Mascara para aceitar telefone com 4 ou 5 digitos iniciais
     * @param val
     * @returns {string}
     * @constructor
     */
    var SPMaskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
            onKeyPress: function (val, e, field, options) {
                field.mask(SPMaskBehavior.apply({}, arguments), options);
            }
        };

    //Validação dos inputs com bootstrap validation
    $(form)
        .bootstrapValidator({
            fields: fields,
            'submitHandler': submitHandler,
            message: "O campo é obrigatório"
        })
        .find('.maskTelefone').mask(SPMaskBehavior, spOptions).end()
        .find('.maskData').mask('00/00/0000').end()
        .find('.maskCpf').mask('000.000.000-00').end()
        .find('.maskCnpj').mask('00.000.000/0000-00').end()
        .find('.maskCep').mask('00.000-000').end()
        .find('.maskOrgExped').mask('AAA-AA').end()
        .find('.maskHora').mask('00:00:00').end()
        .find(".maskDinheiro").mask('000.000.000.000.000,00', {reverse: true}).end()
        .find('.dtpicker').datepicker({
            format: "dd/mm/yyyy"
        })
        .on('changeDate', function (e) {
            var bv = $('#registerForm').data('bootstrapValidator');
            bv.validateField($(this).attr('name'));
        });
}

/**
 * Substitui todas as ocorrências da string de procura com a string de substituição
 *
 * @param string - String completa que tem o token que irá ser substituido
 * @param token - string que vai ser subtituida pelo newtoken
 * @param newtoken - string que vai substituir o token
 * @returns - Retorna string com o token substituido pelo newtoken
 */
function replaceAll(string, token, newtoken) {
    while (string.indexOf(token) != -1) {
        string = string.replace(token, newtoken);
    }
    return string;
}

// Funcao para converter numero float em valor monetario
function floatToMoeda(num) {
    x = 0;
    if (num < 0) {
        num = Math.abs(num);
        x = 1;
    }
    if (isNaN(num))
        num = "0";
    cents = Math.floor((num * 100 + 0.5) % 100);
    num = Math.floor((num * 100 + 0.5) / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
    ret = num + ',' + cents;
    if (x == 1)
        ret = ' - ' + ret;
    return ret;
}

/**
 * Transforma valores que estao em forma de moeda para valores do tipo float
 *
 * @param value
 * @returns {*}
 */
function moedaToFloat(value) {

    var valueFloat = 0;

    if (value != '')
        valueFloat = parseFloat(replaceAll(replaceAll(value, ".", ""), ",", "."));

    return valueFloat;
}

/**
 * Adiciona os valores do post nos campos de volta apos algo não ter sido validado
 * Identifica e exibe os inputs obrigatórios e mostra o primeiro input que não passou na validacao
 * @param elements
 * @returns {boolean}
 */

function validaFormulario(elements) {

    var validacao = {"resultado": true, "msg": "Campo(s) obrigatório(s): \n\n", "focus": false};

    [].forEach.call(elements, function (el) {
        if (el.getAttribute('obrigatorio')) {

            if (el.type == "radio" && name != el.name) {
                var radio = $('input:[name="' + el.name + '"]').is(':checked');
            } else {
                radio = true;
            }

            if (el.value == "" || el.value == undefined || el.value == null || !radio) {

                validacao.resultado = false;
                validacao.msg = validacao.msg + '-   ' + el.getAttribute('nmFormatado') + '\n';

                if (!validacao.focus) {
                    validacao.focus = el;
                }
            }

            var name = el.name;
        }
    });

    if (!validacao.resultado) {
        alert(validacao.msg);
        validacao.focus.focus();
    }

    return validacao.resultado;

}

/*
 * Move lotes selecionados entre as tabelas de adicionar e salvar lotes
 */
function moveLotes(from, to, columns, direction) {

    var rows = from.find(".selected");
    var rowsToMove = [];

    for (var r = 0; r < rows.length; r++) {

        rowsToMove[r] = [];
        var col = 0;

        [].forEach.call(rows[r].children, function (cell) {

            if (cell == rows[r].firstChild) {

                id = cell.firstChild.firstChild.id;

                switch (direction) {
                    case "add":
                        addedLotes.push(id);

                        if (typeof removedLotes != 'undefined') {
                            if (removedLotes[id] != 'undefined') {
                                removedLotes.splice(removedLotes.indexOf(id), 1);
                            }
                        }
                        break;
                    case "remove":
                        addedLotes.splice(addedLotes.indexOf(id), 1);
                        break;
                    case "edit":
                        removedLotes[id] = [];
                        addedLotes.splice(addedLotes.indexOf(id), 1);
                        break;
                }
            }

            if (typeof removedLotes != 'undefined') {
                if (typeof removedLotes[id] != 'undefined') {
                    removedLotes[id][columns[col]] = cell.innerHTML;
                }
            }

            rowsToMove[r][columns[col]] = cell.innerHTML;
            col++;

        });
    }
    if (to.DataTable()) {
        to.DataTable().rows.add(rowsToMove).draw(false);
    }
    if (from.DataTable()) {
        from.DataTable().rows('.selected').remove().draw(false);
    }
}

/**
 * Função para verificar a diferenca entre datas em dias no formato Brasileiro
 * @param data1
 * @param data2
 * @returns {number}
 */
function diferencaDatasDias(data1, data2) {
    data1 = new Date(data1.substring(6, 10), data1.substring(3, 5), data1.substring(0, 2));
    data1.setMonth(data1.getMonth() - 1);
    data2 = new Date(data2.substring(6, 10), data2.substring(3, 5), data2.substring(0, 2));
    data2.setMonth(data2.getMonth() - 1);

    var dif = data1.getTime() - data2.getTime();
    return (dif / 1000 / 60 / 60 / 24);
}

/**
 * Função responsável por realizar requisição ajax e retornar os dados em uma lista utilizando select2
 *
 * @param seletor = seletor do campo (nome, id, classe, etc)
 * @param multiple = true se o select for multiplo e false se for normal
 * @param placeholder = mensagem para ser exibida no placeholder
 * @param url = url com caminho da ação que vai ser utilizada
 *
 */
function select2RemoteData(seletor, multiple, allowClear, placeholder, url) {

    $(seletor).select2({
        multiple: multiple,
        allowClear: allowClear,
        minimumInputLength: 3,
        minimumResultsForSearch: 10,
        placeholder: placeholder,
        language: {
            inputTooShort: function () {
                return "Digite no mínimo 3 letras para pesquisar.";
            }
        },
        ajax: {
            url: url,
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
}

/**
 *
 * @param settings
 * @returns {*}
 * exemplos:
 * $("#campo1").removeNot({ pattern: /[^0-9]+/g }); somente números
 * $("#campo2").removeNot({ pattern: /[^a-z]+/g }); somente letras minúsculas
 * $("#campo3").removeNot({ pattern: /[^a-z]+/gi }); somente letras maiúsculas e minúsculas
 * $("#campo4").removeNot({ pattern: /[^a-z0-9]+/gi }); somente letras maiúsculas e minúsculas e numeros
 * $("#campo5").removeNot({ pattern: /[^2468]+/g }); somente valores definidos (2,4,6,8)
 */
jQuery.fn.removeNot = function (settings) {
    var $this = jQuery(this);
    var defaults = {
        pattern: /[^0-9]/,
        replacement: ''
    }
    settings = jQuery.extend(defaults, settings);

    var new_value = $this.val().replace(settings.pattern, settings.replacement);

    return new_value;
}


/**
 *Exibe mensagem na tela
 * @param style
 * @param position
 * @param msg
 */
function autohidenotify(style, position, msg) {

    if (style == "error" || style == "white") {
        icon = "fa fa-exclamation";
    } else if (style == "warning") {
        icon = "fa fa-warning";
    } else if (style == "success") {
        icon = "fa fa-check";
    } else if (style == "info") {
        icon = "fa fa-question";
    } else {
        icon = "fa fa-circle-o";
    }

    $.notify({
        text: msg,
        image: '<i class="' + icon + '"></i>'
    }, {
        style: 'metro',
        className: style,
        globalPosition: position,
        showAnimation: "show",
        showDuration: 0,
        hideDuration: 0,
        autoHideDelay: 5000,
        autoHide: true,
        clickToHide: true
    });
}

/**
 * Função responśavel por exibir uma pré visualização da imagem antes de fazer upload
 *
 * @param input
 * @returns {boolean}
 */
function readURL(input) {

    if (input.files && input.files[0]) {
        var extensoes_permitidas = ['image/jpeg', 'image/png', 'image/jpg', 'image/bmp'],
            validImage = false;

        for (index = 0; index < extensoes_permitidas.length; index++) {
            if (extensoes_permitidas[index] == input.files[0].type)
                validImage = true;
        }

        if (!validImage) {
            $('#imgPreView').attr('src', 'public/images/imagemInvalida.jpg');
            return false;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imgPreView').attr('src', e.target.result)
        };

        reader.readAsDataURL(input.files[0]);

    }
}

/**
 * Cria um alert de confirmação
 * @param title
 */
function nconfirm(title) {

    $.notify({
        title: title,
        text: '<div class="row">' +
        '<div class="col-sm-12">' +
        '<div class="form-group col-sm-6">' +
        '<a class="btn btn-default yes col-sm-12">Sim</a> ' +
        '</div>' +
        '<div class="form-group col-sm-6">' +
        '<a class="btn btn-danger no col-sm-12">Não</a>' +
        '</div>' +
        '</div>' +
        '</div>',
        image: "<i class='fa fa-warning'></i>"
    }, {
        style: 'metro',
        className: "cool",
        showAnimation: "slideDown",
        globalPosition: 'top center',
        showDuration: 0,
        hideDuration: 0,
        autoHide: false,
        clickToHide: false
    });

}

/**
 * Retorna a data atual no formato brasileiro
 * ex:00/00/0000
 *
 * @returns {string}
 */
function dataAtual() {
    var r = new Date();

    var mes = r.getMonth() + 1;

    if (mes >= 1 && mes <= 9) {
        mes = '0' + mes;
    }

    return r.getDate() + '/' + mes + '/' + r.getFullYear();
}

/**
 * Retorna a hora atual no formato brasileiro
 * ex: 00:00:00
 *
 * @returns {string}
 */
function horaAtual() {
    var r = new Date();

    var minutos = r.getMinutes();
    var segundos = r.getSeconds();

    if (minutos >= 1 && minutos <= 9) {
        minutos = '0' + minutos;
    }

    if (segundos >= 1 && segundos <= 9) {
        segundos = '0' + segundos;
    }

    return r.getHours() + ':' + minutos + ':' + segundos;
}

