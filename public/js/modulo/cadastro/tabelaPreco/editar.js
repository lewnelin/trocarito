var selectEmpreendimento;

$(document).ready(function () {

    fields = {
        "lotes": {
            validators: {
                choice: {
                    min: 1,
                    max: 1,
                    message: 'É preciso no mínimo 1 lote para criar a tabela de preços'
                }
            }
        },
        "arquivoCadastro": {
            validators: {
                file: {
                    extension: "xls,xlsx,csv",
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
        $("#erroImportar").html('Processando . . .<i style="float: left" class="loading"></i>');
        fileData = event.target.files; // para apenas 1 arquivo
        fdata = new FormData();
        fdata.append(0, fileData[fileData.length - 1]);
        $.ajax({
            url: "?m=cadastro&c=tabelaPreco&a=contagemRegistros&idEmp=" + $("#Empreendimento").val(),
            type: 'POST',
            data: fdata,
            dataType: "json",
            processData: false,
            contentType: false,
            async: true,
            success: function (json) {
                $("#erroImportar").text('O arquivo selecionado possui '+json+' registros válidos');
                if(json == 0){
                    $("#arquivoCadastro").val('');
                }
            }
        });
    });

    $("#fl_padrao").on('change', function() {
        if($("div.fl_padrao").hasClass('off')){
            $("div.fl_padrao").removeClass('off');
            $("div.fl_padrao").addClass('on');
        }
    });

    $("select#quadra").on("change", buscarLote);

    function buscarLote() {

        $.ajax({
            type: "POST",
            url: "index.php?m=cadastro&c=tabelaPreco&a=findLoteByQuadra",
            data: {
                empreendimento: $("#Empreendimento").val(),
                quadra: $("select#quadra").val()
            },
            success: function (response) {

                var options = JSON.parse(response);

                $("select#lote").empty();
                selectLote = $("select#lote").select2({
                    'data': options['lotes'],
                    'placeholder': 'Escolha o lote',
                    'allowClear': true

                });

            }
        });
    }

    $('#lote').change(function() {
        $.ajax({
            type: "POST",
            url: "index.php?m=cadastro&c=tabelaPreco&a=findLoteById",
            data: {
                idLote: $(this).val()
            },
            dataType: "json",
            success: function (response) {
                if (response.dadosLotes != false)

                $("#dadosLotes").empty();
                $('#vl_total').val(response.vl_total);
                $('#vl_sinal').val(response.vl_sinal);
                $('#vl_parcela').val(response.vl_parcela);
                $('#vl_intercalada').val(response.vl_intercalada);
                $('#qt_intercalada').val(response.qt_intercalada);
                $('#dt_atualizacao').val(response.dt_atualizacao);
            }
        });

    });

});