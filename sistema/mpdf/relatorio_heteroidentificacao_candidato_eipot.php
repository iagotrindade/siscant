<?php
ob_start();
session_start();
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$id_candidato = (int)$_GET['id'];
$fase = (int)$_GET['fase'];

$tipo_inspecao = ($fase == 1) ? 'Complementar' : 'Revisora';

set_time_limit(300);

if (!isset($_SESSION['perfil'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if ($_SESSION['perfil'] != 'admin') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if ($_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

// Cabeçalhos por RM
$cabecalhos = [
    '3'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DO SUL<br>COMANDO DA 3ª REGIÃO MILITAR<br>(Gov das Armas Prov do RS/1821)<br>REGIÃO DOM DIOGO DE SOUZA<br>",
    '8'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 8ª REGIÃO MILITAR<br>(Gov das Armas Prov do PA/1821)<br>REGIÃO FORTE DO PRESÉPIO<br>",
    '6'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 6ª REGIÃO MILITAR<br>(Governo das Armas Província da Bahia/1821)<br>REGIÃO MARECHAL CANTUÁRIA<br>",
    '12' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DA 12ª REGIÃO MILITAR<br>(Comando de Elementos de Fronteira/1948)<br>(FORTE MENDONÇA FURTADO)<br>",
    '7'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 7ª REGIÃO MILITAR<br>(Gov das Armas Prov PE/1821)<br>REGIÃO MATIAS DE ALBUQUERQUE<br>",
    '5'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 5ª REGIÃO MILITAR<br>(Comando das Armas do Estado do Paraná/1990)<br>REGIÃO HERÓIS DA LAPA<br>",
    '11' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 11ª REGIÃO MILITAR<br>(Cmdo Mil Bsb/1960)<br>REGIÃO TENENTE-CORONEL LUIZ CRULS<br>",
    '2'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 2ª REGIÃO MILITAR<br>(Cmdo das Armas Prov Pr/1890)<br>REGIÃO DAS BANDEIRAS<br>",
    '4'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 4ª REGIÃO MILITAR<br>(4⁰ Distrito Militar/1891)<br>REGIÃO DAS MINAS DO OURO<br>",
];

// Define o cabeçalho
$_SESSION['cabecalho_relatorio'] = $cabecalhos[$rm_usuario] ?? '';

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    " . $_SESSION['cabecalho_relatorio'] . "
</p>
<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='center'><strong>Ata Heteroidentificação " . $tipo_inspecao . " - " . $rm_usuario . "ª Região Militar</strong></th>
    </tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>A Comissão de Heteroidentificação " . $tipo_inspecao . " inspecionou na presente sessão, o abaixo declarado, para fins de comprovação de sua autodeclaração, e sobre isto, proferiu o parecer abaixo:</p> 
";
$mpdf->AddPage('P');
$mpdf->WriteHTML($html);

$candidato = $conexao->get_usuario_id($id_candidato);

$cpf = substr($candidato[0]['cpf'], 0, -5) . "*****";
$pareceres = $conexao->get_pareceres_heteroidentificacao($candidato[0]['id']);

// Contagem dos pareceres válidos da fase
$quantidadePareceresConfirmados = 0;
$quantidadePareceresNaoConfirmados = 0;

foreach ($pareceres as $parecer) {
    if ($parecer['fase'] != $fase) continue;

    if ($parecer['parecer'] === 'confirmada') {
        $quantidadePareceresConfirmados++;
    } elseif ($parecer['parecer'] === 'nao_confirmada') {
        $quantidadePareceresNaoConfirmados++;
    }
}

$totalPareceres = $quantidadePareceresConfirmados + $quantidadePareceresNaoConfirmados;

// Regras por fase
$minConfirmados = $fase === 1 ? 3 : 2;
$minTotal = $fase === 1 ? 5 : 3;

if ($totalPareceres === $minTotal) {
    if ($quantidadePareceresConfirmados >= $minConfirmados) {
        $resultado = 'CONFIRMADA';
    } elseif ($quantidadePareceresNaoConfirmados >= $minConfirmados) {
        $resultado = 'NÃO CONFIRMADA';
    } else {
        $resultado = 'PENDENTE';
    }
} else {
    $resultado = 'PENDENTE';
}

// Monta a linha da tabela
$linhasHtml .= "
    <table border='0'  style='font-size: 10px; font-family: Times New Roman; width:100%' >

    <tr style='background-color: #D8D8D8'>
        <td colspan=\"2\"> <center><b>IDENTIFICAÇÃO</b></center></td>
    </tr>

    <tr>
        <td><b>Nome Completo: </b> " . $candidato[0]['nome_completo'] . " </td>
        <td><b>CPF: </b> " . $candidato[0]['cpf'] . "</td>
    </tr>
    <tr>
        <td><b>Naturalidade: </b> " . $candidato[0]['naturalidade'] . " </td>
        <td><b>Data de Nascimento: </b> " . $candidato[0]['data_nascimento'] . " </td>
    </tr>

    <tr style='background-color: #D8D8D8'>
        <td colspan=\"2\"> <center><b>CIVIL/MILTAR</b></center></td>
    </tr>

    <tr>
        <td><b>Ativa/Reserva: </b>" . $candidato[0]['ativa_reserva'] . "</td>
        <td><b>Certificado: </b>" . $candidato[0]['certificado'] . "</td>
    </tr>

    <tr>
        <td><b>Nº do Documento: </b>" . $candidato[0]['num_documento'] . "</td>
        <td><b>Data da Expedição: </b>" . $candidato[0]['data_expedicao'] . "</td>
    </tr>

    <tr>
        <td><b>Situação Militar: </b>" . $candidato[0]['situacao_militar'] . "</td>
        <td><b>Posto/Graduação: </b>" . $candidato[0]['posto_grad'] . "</td>
    </tr>

    <tr>
        <td><b>Força: </b>" . $candidato[0]['forca'] . "</td>
        <td><b>Ano de incorporação: </b>" . $candidato[0]['ano_incorporacao'] . "</td>
    </tr>

    <tr>
        <td><b>Arma/Quadro/Serviço: </b>" . $candidato[0]['arma_quadro_servico'] . "</td>
        <td><b>Licenciamento: </b>" . $candidato[0]['licensiamento'] . "</td>
    </tr>

    <tr style='background-color: #D8D8D8'>
        <td colspan=\"2\"> <center><b>RESULTADO</b></center></td>
    </tr>

    <tr>
        <td><b>Parecer da Comissão: </b>" . $resultado . "</td>
    </tr>

    </table> 
    
    <table border='0' style='width:100%'>
    <tr>
        <th align='left'><strong> <p style='font-size: 12px; font-family: Times New Roman;'>
            $cidade - " . $data_hoje . "   </p></strong>
        </th>
    </tr>
    </table> 

    <p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;
   Eu, ". mb_strtoupper($candidato[0]['nome_completo'], "UTF-8").", em _____/_____/20_____, tomei ciência do resultado deste parecer no momento da assinatura.   
</p>
";
$contador++;

$mpdf->WriteHTML($linhasHtml);

$mpdf->WriteHTML($html);

// Gera o PDF
$mpdf->Output("Ata Heteroidentificação " . $tipo_inspecao . " " . $data_inspecao . ".pdf", 'D');
ob_end_flush();
exit;
