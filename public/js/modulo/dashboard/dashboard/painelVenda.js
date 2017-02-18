$(document).ready(function () {

    //Verifica se possui idEmpreendimento, quadra ou status na url e seta nos seletct's
    if (getUrl('idEmpreedimento')) {

        $("#Empreendimento").val(getUrl('idEmpreedimento')).trigger("change.select2");

        if (getUrl('statusAtual')) {
            $('#statusLotes').prop('disabled', false);
            $("#statusLotes").val(getUrl('statusAtual')).trigger("change.select2");
        }

        if (getUrl('quadraAtual')) {
            $('#Quadra').prop('disabled', false);

            if (getUrl('tabelaPrecoAtual')) {
                $('#tabelaPreco').prop('disabled', false);
                $('#tabelaPreco').val(getUrl('tabelaPrecoAtual'));
            }
            $("#Quadra").val(getUrl('quadraAtual')).trigger("change.select2");
        }

        if (getUrl('tabelaPrecoAtual')) {
            $('#tabelaPreco').prop('disabled', false);

            if (getUrl('quadraAtual')) {
                $('#Quadra').prop('disabled', false);
                $("#Quadra").val(getUrl('quadraAtual'));
            }
            $('#tabelaPreco').val(getUrl('tabelaPrecoAtual')).trigger("change.select2");
        }

        listaQuadraEmpreendimento(getUrl('idEmpreedimento'));
    }

    $(window)
        .on('scroll', function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                geraLotes();
            }
        }
    );

    $('[id="Quadra"], [id="statusLotes"], [id="tabelaPreco"]').on('change', function () {
        //Seta os dados dos select's na URL
        var params =
            '&idEmpreedimento=' + $('[id="Empreendimento"]').val() +
            '&quadraAtual=' + $('[id="Quadra"]').val() +
            '&statusAtual=' + $('[id="statusLotes"]').val() +
            '&tabelaPrecoAtual=' + $('[id="tabelaPreco"]').val();

        window.history.pushState('Object', 'Painel de Venda', '?m=dashboard&c=dashboard&a=painelVenda' + params);

        $("#listaLotes").html('');
        $('#ultimoId').val('');
        geraLotes();
    });

    //Retorna a lista das quadras do empreendimento e recarrega os lotes no painel
    $('[id="Empreendimento"]').on('change', function () {
        listaQuadraEmpreendimento($(this).val());
    });
});

function listaQuadraEmpreendimento(idEmpreendimento) {
    if (idEmpreendimento) {
        $.ajax({
            type: 'POST',
            url: '?m=dashboard&c=dashboard&a=listaQuadraEmpreendimento',
            data: {idEmpreendimento: idEmpreendimento},
            success: function (json) {

                var json = JSON.parse(json);

                $('#Quadra').empty();
                $('#Quadra').select2({
                    data: json['quadra'],
                    placeholder: "Escolha uma opção...",
                    allowClear: true,
                    language: {
                        "noResults": function(){
                            return "Registro não encontrado";
                        }
                    }
                });

                $('#tabelaPreco').empty();
                $('#tabelaPreco').select2({
                    data: json['tabelaPreco'],
                    placeholder: "Escolha uma opção...",
                    allowClear: true,
                    language: {
                        "noResults": function(){
                            return "Tabela de preço não encontrada";
                        }
                    }
                });

                if (getUrl('quadraAtual') && getUrl('quadraAtual') != 'null') {
                    $('#Quadra').prop('disabled', false);

                    if (getUrl('tabelaPrecoAtual')) {
                        $('#tabelaPreco').prop('disabled', false);
                        $('#tabelaPreco').val(getUrl('tabelaPrecoAtual'));
                    }
                    $("#Quadra").val(getUrl('quadraAtual')).trigger("change.select2");
                } else {
                    $("#Quadra").val('*').trigger("change.select2");
                }

                if(json['tabelaPadrao']){
                    $('#tabelaPreco').val(json['tabelaPadrao']).trigger("change.select2");
                } else {
                    if (getUrl('tabelaPrecoAtual') && getUrl('tabelaPrecoAtual') != 'null') {
                        $('#tabelaPreco').prop('disabled', false);

                        if (getUrl('quadraAtual')) {
                            $('#Quadra').prop('disabled', false);
                            $("#Quadra").val(getUrl('quadraAtual'));
                        }
                        $('#tabelaPreco').val(getUrl('tabelaPrecoAtual')).trigger("change.select2");
                    } else {
                        $('#tabelaPreco').val(json['tabelaPadrao']).trigger("change.select2");
                    }
                }

                //Seta os dados dos select's na URL
                var params =
                    '&idEmpreedimento=' + $('[id="Empreendimento"]').val() +
                    '&quadraAtual=' + $('[id="Quadra"]').val() +
                    '&statusAtual=' + $('[id="statusLotes"]').val() +
                    '&tabelaPrecoAtual=' + $('[id="tabelaPreco"]').val();

                window.history.pushState('Object', 'Painel de Venda', '?m=dashboard&c=dashboard&a=painelVenda' + params);

                $("#listaLotes").html('');
                $('#ultimoId').val('');
                geraLotes();
            }
        });
    }
}

function geraLotes() {
    var ultimoId = $('#ultimoId').val(),
        status = $("#statusLotes").val(),
        tabelaPreco = $("#tabelaPreco").val(),
        empreendimento = $("#Empreendimento").val(),
        quadra = $("#Quadra").val();

    if (empreendimento != '') {

        if (!quadra && getUrl('quadraAtual')) {
            quadra = getUrl('quadraAtual');
        }
        if (!tabelaPreco && getUrl('tabelaPrecoAtual')) {
            tabelaPreco = getUrl('tabelaPrecoAtual');
        }
        $("#Quadra").val(quadra).trigger('change.select2');
        $("#tabelaPreco").val(tabelaPreco).trigger('change.select2');

        $.ajax({
            type: 'GET',
            url: '?m=dashboard&c=dashboard&a=painelVenda',
            data: {
                ultimoId: ultimoId,
                empreendimento: empreendimento,
                quadra: quadra,
                status: status,
                tabelaPreco: tabelaPreco
            },
            success: function (data) {

                var data = JSON.parse(data),
                    listaLote = data[0],
                    ultimoIdJson = data[1],
                    nmStatus,
                    icons;

                if (listaLote != '') {
                    if ($('#ultimoId').val() == '') {
                        $("#listaLotes").html('');
                    }

                    $('#ultimoId').val(ultimoIdJson.idLote);

                    for (var i in listaLote) {

                        var linkReserva = "'?m=dashboard&c=dashboard&a=reservarLote&idLote=" + listaLote[i].idLote + "&idEmpreedimento=" + listaLote[i].idEmpreendimento + "&quadraAtual=" + quadra + "&statusAtual=" + status + "&tabelaPrecoAtual=" + tabelaPreco + "'",
                            iconReserva = '<a class="limpaUltimoId" onclick="location.href = ' + linkReserva + ';" data-toggle="tooltip" title="RESERVA"><i class="icon-clock-circled"></i></a>',

                            linkVenda = "'?m=dashboard&c=dashboard&a=venderLote&idLote=" + listaLote[i].idLote + "&idEmpreedimento=" + listaLote[i].idEmpreendimento + "&quadraAtual=" + quadra + "&statusAtual=" + status + "&tabelaPrecoAtual=" + tabelaPreco + "&quadra=" + listaLote[i].quadra + "'",
                            iconVenda = '<a class="limpaUltimoId" onclick="location.href = ' + linkVenda + ';" data-toggle="tooltip" title="VENDA"><i class="icon-basket-circled"></i></a>',

                            linkCancelar = "?m=dashboard&c=dashboard&a=cancelarReserva&idLote=" + listaLote[i].idLote + "&idReserva=" + listaLote[i].idReserva + "&idEmpreedimento=" + listaLote[i].idEmpreendimento + "&quadraAtual=" + quadra + "&statusAtual=" + status + "&tabelaPrecoAtual=" + tabelaPreco + "&quadra=" + listaLote[i].quadra + "'",
                            iconCancelar = '<a class="limpaUltimoId verificaCorretor"  data-redirect="' + linkCancelar + '" data-toggle="tooltip" title="CANCELAR" data-corretor="' + listaLote[i].corretorReserva + '"><i class="icon-cancel-circled"></i></a>',

                            linkProposta = "'?m=dashboard&c=dashboard&a=gerarProposta&idLote=" + listaLote[i].idLote + "'",
                            iconProposta = '<a onclick="window.open(' + linkProposta + ');" data-toggle="tooltip" title="GERAR PROPOSTA"><i class="icon-doc-circled"></i></a>',

                            iconInformacao = '<span class="modalInfoScroll" data-toggle="modal" data-target="#informacoes" data-lote="' + listaLote[i].idLote + '" data-codigo="' + listaLote[i].codigo + '">' +
                                '<a id="infoAtual_' + listaLote[i].idLote + '" data-toggle="tooltip" title="INFORMAÇÕES"><i class="icon-info-circled"></i></a>' +
                                '</span>',

                            valorLote = '';

                        switch (listaLote[i].codigo) {

                            case 'D':
                                nmStatus = 'Disponivel';
                                icons = iconInformacao + ' ' + iconReserva + ' ' + iconVenda;
                                break;

                            case 'R':
                                var linkVenda = linkVenda + "+'&reserva=1'";
                                if (linkVenda.substring(0, 1) == "'") {
                                    linkVenda = linkVenda.substring(1);
                                }
                                var iconVenda = '<a class="limpaUltimoId verificaCorretor" data-redirect="' + linkVenda + '" data-toggle="tooltip" title="VENDA" data-corretor="' + listaLote[i].corretorReserva + '"><i class="icon-basket-circled"></i></a>';

                                nmStatus = 'Reservado';

                                if (listaLote[i].reservadoLote == 1) {
                                    icons = iconInformacao;
                                } else {
                                    icons = iconInformacao + ' ' + iconCancelar + ' ' + iconVenda;
                                }

                                break;
                            case 'V':
                                nmStatus = 'Vendido';
                                icons = iconInformacao;
                                break;
                            case 'N':
                                nmStatus = 'Negociaçao';
                                icons = iconInformacao + ' ' + iconProposta;
                                break;
                        }

                        if (listaLote[i].codigo == 'D') {
                            var valor = (listaLote[i].vl_total > 0) ? floatToMoeda(listaLote[i].vl_total) : floatToMoeda(listaLote[i].valor);
                            valorLote = 'R$ <span>' + valor + '</span>';
                        }

                        var conteudo = "";

                        conteudo += '<div class="col-lg-2">';
                        conteudo += '<div class="widget ' + listaLote[i].class + ' animated fadeInDown" style="height:134px;">';
                        conteudo += '<div class="widget-footer">';
                        conteudo += '<div class="row">';
                        conteudo += '<div align="center" class="col-sm-12">';
                        conteudo += '<b>' + listaLote[i].status + '</b>';
                        conteudo += '</div>';
                        conteudo += '</div>';
                        conteudo += '</div>';
                        conteudo += '<div align="center" class="widget-content">';
                        conteudo += '<div class="text-box">';
                        conteudo += '<p class="mainicon" style="margin: 0;">' + icons + '</p>';
                        conteudo += '<span class="maindata" style="font-size: 13px;">Quadra <b style="font-size: 15px;">' + listaLote[i].quadra + '</b></span><br>';
                        conteudo += '<span style="font-size: 13px;">Lote <b style="font-size: 15px;">' + listaLote[i].lote + '</b></span><br>';
                        conteudo += '<span style="font-size: 13px;">' + listaLote[i].area + '</span>m²<br>';
                        conteudo += '<span><strong>' + valorLote + '</strong></span>';
                        conteudo += '<div class="clearfix"></div>';
                        conteudo += '</div>';
                        conteudo += '</div>';
                        conteudo += '</div>';
                        conteudo += '</div>';

                        $("#listaLotes").append(conteudo);

                    }

                    $('#Quadra').prop('disabled', false);
                    $('#tabelaPreco').prop('disabled', false);
                    $('#statusLotes').prop('disabled', false);

                } else {
                    if (ultimoId == '')
                        $("#listaLotes").html('<div class="alert alert-warning alert-dismissable" align="center">Não há lotes com esses filtros.</div>');
                }

                //Recria o evento que busca informações do lote
                $(".modalInfoScroll").click(function () {

                    $(".carregando").show();
                    $(".camposInfo").hide();

                    var lote = $(this).data('lote'),
                        codigo = $(this).data('codigo');

                    $.ajax({
                        type: 'GET',
                        url: '?m=dashboard&c=dashboard&a=listarInformacoes',
                        dataType: 'json',
                        data: "&lote=" + lote + "&tabelaPrecoAtual=" + tabelaPreco,
                        success: function (data) {

                            $(".carregando").hide();
                            $(".camposInfo").show();

                            switch (codigo) {
                                case 'D':
                                    $('.nome, .corretor, .cpf, .reservaTecnica, .dadosPessoa').hide();
                                    break;
                                case 'R':
                                    if (data.reservaTecnica == 1) {
                                        $('.nome, .valor, .corretor, .cpf').hide();
                                    } else {
                                        $('.reservaTecnica').hide();
                                    }
                                    break;
                                case 'V':
                                case 'N':
                                    $('.valor, .reservaTecnica').hide();
                                    break;
                            }

                            var valor = (data.vlTotalTabelaPreco > 0) ? floatToMoeda(data.vlTotalTabelaPreco) : floatToMoeda(data.valor);
                            var parcela = floatToMoeda(data.vlParcelaTabelaPreco);
                            var sinal = floatToMoeda(data.vlSinalTabelaPreco);
                            var intercalada = floatToMoeda(data.vlIntercaladaTabelaPreco);
                            var qtintercalada = (data.qtIntercaladaTabelaPreco);

                            $("#loteFrente").html(data.loteFrente + 'm²');
                            $("#loteDireito").html(data.loteDireito + 'm²');
                            $("#loteEsquerdo").html(data.loteEsquerdo + 'm²');
                            $("#loteFundo").html(data.loteFundo + 'm²');
                            $("#valor").html('R$ ' + valor);
                            $("#parcela").html('R$ ' + parcela);
                            $("#sinal").html('R$ ' + sinal);
                            $("#vlintercalada").html('R$ ' + intercalada);
                            $("#qtintercalada").html(qtintercalada);
                            $("#dataAtualizacao").html(data.dataAtualizacao);
                            $("#obsReservaTecnica").html(data.obsReservaTecnica);
                            $("#nome").html(data.nome);
                            $("#rgi").html(data.rgiLote);
                            $("#cpf").html(data.cpf);
                            $("#empreendimento").html(data.empreendimento);
                            $("#quadraLote").html(data.quadra + ' - ' + data.lote);
                            $("#tamanho").html(data.tamanho + 'm²');
                            $("#corretor").html(data.corretor);
                            $("#descricaoLote").html(data.descricaoLote);

                        }
                    });
                });

                //Limpa o input do ultimo ID caso mude para outra tela(venda, reserva), e o usuario voltar a
                //página pelo 'voltar' do browser, o programa refaz a consulta listando todos lotes e não a
                //partir do ultimoID
                $('.limpaUltimoId').on('mousedown', function (e) {
                    if (e.which == 1) {
                        $('#ultimoId').val('');
                    }
                });

                $('.verificaCorretor').on('click', function (e) {

                    var dados = $('[name="idCorretorAtual"]').val().split('_'),
                        idCorretor = dados[0],
                        superUser = dados[1];

                    if (idCorretor != $(this).data('corretor') && superUser == 0) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        autohidenotify('white', 'top center', 'Apenas o próprio corretor pode cancelar a reserva ou vender o lote.');
                    } else {
                        location.href = $(this).data('redirect');
                    }
                });
            }
        });
    } else {
        $("#listaLotes").html('<div class="alert alert-info alert-dismissable" align="center">Escolha o Empreendimento para poder listar os lotes.</div>');
        $('#Quadra').prop('disabled', true);
        $('#statusLotes').prop('disabled', true);
        $('#tabelaPreco').prop('disabled', true);
    }

}