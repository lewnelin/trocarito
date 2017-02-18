<?php

$clienteAcade = $this->get("clienteAcade")[0];
$clienteAcadeCidade = $this->get("clienteAcade")[1];
$infoLote = $this->get("infoLote");
$imageLogo = $this->get('imageLogo');
?>
<style>

    table, div {
        font-family: Arial;
    }

    table {
        border-collapse: collapse;
        margin-top: 12px;
        font-size: 13px;
    }

    table tr td {
        border: 1px solid black;
        padding: 5px;
    }

    .titulo {
        margin-top: 15px;
        font-weight: bold;
    }

    .paragrafo {
        margin-top: 5px;
        font-size: 10px;
    }

    .paragrafo2 {
        margin-top: 20px;
        font-weight: bold;
        font-size: 10px;
    }


</style>
<page backtop="10mm" backbottom="10mm">

<!--        Cabeçalho-->
<table width="700px">
    <tr>
        <td rowspan="2" align="center" style="width: 360px;">
            <?php

            $src = $imageLogo;

            if (file_exists($src)) {
                echo '<img src="' . $src . '" alt="' . $clienteAcade['nm_fantasia'] . '" align="center" height="80">';
            } else {
                echo '<img style="float: left; " src="' . LOGOMARCA_DEFAULT . '" alt="LogoPadrao" height="80">';
            }


            ?>
        </td>
        <td align="center"><b><?= $infoLote['nm_empreendimento'] ?></b></td>
    </tr>
    <tr>
        <td align="center">
            <b>Quadra:</b> <?= $infoLote['quadra'] ?><br>
            <b>Lote:</b> <?= $infoLote['lote'] ?>
        </td>
    </tr>
</table>

<!--        Titulo-->
<div class="titulo" align="center">PROPOSTA DE COMPRA</div>

<!--        Corpo 1 -->
<table width="700px">
    <tr>
        <td style="width: 400px;" colspan="2"><b>Proponente:</b> <?= $infoLote['nmCliente'] ?></td>
        <td colspan="2" width="200px"><b>Nascionalidade:</b> <?= $infoLote['nmNacionalidadeC'] ?></td>
    </tr>
    <tr>
        <td style="width: 200px;"><b>Email:</b> <?= $infoLote['emailC'] ?></td>
        <td colspan="3"><b>RG/Passaporte:</b> <?= $infoLote['nrRgC'] ?></td>
    </tr>
    <tr>
        <td style="width: 200px;"><b>CPF:</b> <?= $infoLote['nrCpfC'] ?></td>
        <td><b>Data de Nascimento:</b> <?= Helper::getDate($infoLote['dtNascimentoC']) ?></td>
        <td colspan="2"><b>Profissão:</b> <?= $infoLote['nmProfissaoC'] ?></td>
    </tr>
    <tr>
        <td colspan="4"><b>End. Residencial:</b> <?= $infoLote['enderecoC'] ?></td>
    </tr>
    <tr>
        <td><b>Bairro:</b> <?= $infoLote['bairroC'] ?></td>
        <td><b>Cidade:</b> <?= $infoLote['nomeCidC'] . '/' . $infoLote['ufCidC'] ?></td>
        <td colspan="2"><b>CEP:</b> <?= $infoLote['cepC'] ?></td>
    </tr>
    <tr>
        <td><b>Tel. Residencial:</b> <?= $infoLote['nrTelefoneC'] ?></td>
        <td><b>Celular:</b> <?= $infoLote['nrCelularC'] ?></td>
        <td colspan="2"><b>Est. Civil:</b> <?= Helper::getEstCivil($infoLote['estCivilC']) ?></td>
    </tr>
    <tr>
        <td><b>Pai:</b> <?= $infoLote['nmPaiC'] ?></td>
        <td colspan="3"><b>Mãe:</b> <?= $infoLote['nmMaeC'] ?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="4"><b>Cônjuge/Comp.:</b> <?= $infoLote['nmPessoaConj'] ?></td>
    </tr>
    <tr>
        <td style="width: 200px;"><b>Email:</b> <?= $infoLote['emailConj'] ?></td>
        <td colspan="3"><b>RG/Passaporte:</b> <?= $infoLote['nrRgConj'] ?></td>
    </tr>
    <tr>
        <td style="width: 200px;"><b>CPF:</b> <?= $infoLote['nrCpfConj'] ?></td>
        <td><b>Data de Nascimento:</b> <?= Helper::getDate($infoLote['dtNascimentoConj']) ?></td>
        <td colspan="2"><b>Profissão:</b> <?= $infoLote['nmProfissaoConj'] ?></td>
    </tr>
    <tr>
        <td colspan="4"></td>
    <tr>
        <td width="400px" colspan="2"><b>Contrutora/Proprietário:</b> <?= $infoLote['nmPessoaSocio'] ?></td>
        <td colspan="2">
            <b>CPF/CNPJ:</b> <?= isset($infoLote['nrCnpjSocio']) ? $infoLote['nrCnpjSocio'] : $infoLote['nrCpfSocio'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="4"><b>End.
                Empr.:</b> <?= $infoLote['enderecoSocio'] . ', ' . $infoLote['nmBairroSocio'] . ', CEP ' . $infoLote['nrCepSocio'] ?>
        </td>
    </tr>
</table>

<!--        Corpo 2-->
<table width="700px">
    <tr>
        <td align="center">Condição de Pagamento</td>
        <td align="center">Quant. Ref.</td>
        <td align="center">Valor Ref. (R$)</td>
        <td align="center">Valor Total por Item(R$)</td>
        <td align="center" width="100px">Data Parc.</td>
    </tr>
    <tr>
        <td>Sinal</td>
        <td align="center"><?= $infoLote['qtdParcSinal'] ?></td>
        <td align="right" style="font-size: 7pt">
            <?php foreach ($infoLote['sinal'] as $sinal) : ?>
                <?= Helper::getMoney($sinal['valor']) ?><br>
            <?php endforeach; ?>
        </td>
        <td align="right"><?= Helper::getMoney($infoLote['vlTotalParcSinal']) ?></td>
        <td align="center" style="font-size: 7pt">
            <?php foreach ($infoLote['sinal'] as $sinal) : ?>
                <?= Helper::getDate($sinal['dtVencimento']) ?><br>
            <?php endforeach; ?>
        </td>
    </tr>
    <tr>
        <td>Chaves</td>
        <td align="center" <?= (isset($infoLote['adicionais']['C'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= $infoLote['qtdParcEntrega'] ?>
            <?php if (isset($infoLote['adicionais']['C'])) : ?>
                <?php foreach ($infoLote['adicionais']['C'] as $adicional) : ?>
                    <br><?= $adicional['qt_parcelas'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td align="right" <?= (isset($infoLote['adicionais']['C'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= Helper::getMoney($infoLote['vlParcEntrega']) ?>
            <?php if (isset($infoLote['adicionais']['C'])) : ?>
                <?php foreach ($infoLote['adicionais']['C'] as $adicional) : ?>
                    <br><?= $adicional['vl_parcela'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td align="right"><?= Helper::getMoney($infoLote['vlTotalParcEntrega']) ?></td>
        <td align="center"><?= $infoLote['dtParcEntrega'] ?></td>
    </tr>
    <tr>
        <td>Intercaladas</td>
        <td align="center" <?= (isset($infoLote['adicionais']['I'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= $infoLote['qtdParcIntercalada'] ?>
            <?php if (isset($infoLote['adicionais']['I'])) : ?>
                <?php foreach ($infoLote['adicionais']['I'] as $adicional) : ?>
                    <br><?= $adicional['qt_parcelas'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td align="right" <?= (isset($infoLote['adicionais']['I'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= Helper::getMoney($infoLote['vlParcIntercalada']) ?>
            <?php if (isset($infoLote['adicionais']['I'])) : ?>
                <?php foreach ($infoLote['adicionais']['I'] as $adicional) : ?>
                    <br><?= $adicional['vl_parcela'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td align="right"><?= Helper::getMoney($infoLote['vlTotalParcIntercalada']) ?></td>
        <td align="center" <?= (isset($infoLote['adicionais']['I'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= $infoLote['dtParcIntercalada'] ?>
            <?php if (isset($infoLote['adicionais']['I'])) : ?>
                <?php foreach ($infoLote['adicionais']['I'] as $adicional) : ?>
                    <br><?= Helper::getDate($adicional['dt_parcela']) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>Parcelas Normais</td>
        <td align="center" <?= (isset($infoLote['adicionais']['N'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= $infoLote['qtdParcNormal'] ?>
            <?php if (isset($infoLote['adicionais']['N'])) : ?>
                <?php foreach ($infoLote['adicionais']['N'] as $adicional) : ?>
                    <br><?= $adicional['qt_parcelas'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td align="right" <?= (isset($infoLote['adicionais']['N'])) ? 'style="font-size: 8pt"' : '' ?>>
            <?= Helper::getMoney($infoLote['vlParcNormal']) ?>
            <?php if (isset($infoLote['adicionais']['N'])) : ?>
                <?php foreach ($infoLote['adicionais']['N'] as $adicional) : ?>
                    <br><?= $adicional['vl_parcela'] ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td align="right"><?= Helper::getMoney($infoLote['vlTotalParcNormal']) ?></td>
        <td align="center"><?= $infoLote['dtParcNormal'] ?></td>
    </tr>
    <tr>
        <td colspan="5"></td>
    </tr>
    <tr>
        <td colspan="3"><b>TOTAL GERAL DA PROPOSTA</b>: R$ <?= Helper::getMoney($infoLote['vlTotalProposta']) ?>
        </td>
        <td colspan="2"><b>Data da Proposta</b>: <?= date('d/m/Y') ?></td>
    </tr>
    <tr rowspan="2" style="">
        <td colspan="3"><b>Correção Monetária</b>: <?= $infoLote['descricaoIndice'] ?></td>
        <td colspan="2" align="center">
            ____/____/____ ______________________________<br>
            <span style="font-size: 10px;">Aprovação Proposta</span>

        </td>
    </tr>
    <tr>
        <td colspan="5"><b>Obs.</b>: <?= $infoLote['obs'] ?></td>
    </tr>
    <tr>
        <td colspan="5" align="center">
            <?php foreach ($infoLote['parcerias'] as $parceria) : ?>
                <b style="font-size: 8pt;"><?= $parceria['nm_pessoa'] ?> -
                    <?php echo ($parceria['tipoPessoa'] == 'J') ? 'SPE/CNPJ: ' . Helper::getMaskCNPJ($parceria['nr_cnpj']) : 'CPF: ' . Helper::getMaskCPF($parceria['nr_cpf']) ?>
                    <br><?= $parceria['endereco'] ?> - <?= $parceria['bairro'] ?> / CEP <?= $parceria['nr_cep'] ?>
                    - <?= $parceria['cidade'] ?></b><br>
            <?php endforeach; ?>
        </td>
    </tr>
</table>

<!--    Assinaturas-->
<table width="700px" style="margin-top: 20px;">
    <tr>
        <td style="border:0px;" align="center">
            ____________________________________<br>
            <span style="font-size: 10px"><?= $infoLote['nmCorretor'] ?>
                <?= (isset($infoLote['creciCorretor'])?'<br> Creci: '.$infoLote['creciCorretor']:'') ?>
            </span>
        </td>
        <td style="border:0px;" align="center">
            ____________________________________<br><span
                style="font-size: 10px"><?= $infoLote['nm_empreendimento'] ?></span>
        </td>
        <td style="border:0px;" align="center">
            ____________________________________<br><span
                style="font-size: 10px"><?= $infoLote['nmCliente'] ?></span>
        </td>
    </tr>
</table>

<!--        Texto 2-->
<div class="paragrafo2" align="justify">ESTA PROPOSTA SERÁ ANALISADA, CASO APROVADO SE DARÁ INÍCIO A ELABORAÇÃO DO
    CONTRATO.<br>Caso o lote já tenha sido vendido, o cliente poderá escolher um outro lote ou a devolução da taxa
    de corretagem.
</div>

</page>