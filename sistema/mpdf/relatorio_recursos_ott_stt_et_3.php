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

/* ================= DADOS POST ================= */
$titulo = $_POST['titulo'];
$subtitulo = $_POST['subtitulo'];
$paragrafo_um = $_POST['paragrafo_um'];
$paragrafo_dois = $_POST['paragrafo_dois'];

$data = $_POST['data'];
$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];
$etapa = $_POST['etapa'];

$om_grupo_um = $_POST['om_grupo_um'];
$om_grupo_dois = $_POST['om_grupo_dois'];
$om_grupo_tres = $_POST['om_grupo_tres'];

$data_grupo_um_dia_um = $_POST['data_grupo_um_dia_um'];
$data_grupo_um_dia_dois = $_POST['data_grupo_um_dia_dois'];
$data_grupo_um_dia_tres = $_POST['data_grupo_um_dia_tres'];

$data_grupo_dois_dia_um = $_POST['data_grupo_dois_dia_um'];
$data_grupo_dois_dia_dois = $_POST['data_grupo_dois_dia_dois'];
$data_grupo_dois_dia_tres = $_POST['data_grupo_dois_dia_tres'];

$data_grupo_tres_dia_um = $_POST['data_grupo_tres_dia_um'];
$data_grupo_tres_dia_dois = $_POST['data_grupo_tres_dia_dois'];
$data_grupo_tres_dia_tres = $_POST['data_grupo_tres_dia_tres'];

$endereco_grupo_um = $_POST['endereco_grupo_um'];
$endereco_grupo_dois = $_POST['endereco_grupo_dois'];
$endereco_grupo_tres = $_POST['endereco_grupo_tres'];

$candidatos_grupo_um_dia_um = $_POST['candidatos_grupo_um_dia_um'];
$candidatos_grupo_um_dia_dois = $_POST['candidatos_grupo_um_dia_dois'];
$candidatos_grupo_um_dia_tres = $_POST['candidatos_grupo_um_dia_tres'];

$candidatos_grupo_dois_dia_um = $_POST['candidatos_grupo_dois_dia_um'];
$candidatos_grupo_dois_dia_dois = $_POST['candidatos_grupo_dois_dia_dois'];
$candidatos_grupo_dois_dia_tres = $_POST['candidatos_grupo_dois_dia_tres'];

$candidatos_grupo_tres_dia_um = $_POST['candidatos_grupo_tres_dia_um'];
$candidatos_grupo_tres_dia_dois = $_POST['candidatos_grupo_tres_dia_dois'];
$candidatos_grupo_tres_dia_tres = $_POST['candidatos_grupo_tres_dia_tres'];

/* ================= PERMISSÕES ================= */
if ($_SESSION['perfil'] != 'admin') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}

if ($_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 3541621441 ao gerar relatório!");
    exit();
}

/* ================= CABEÇALHO ================= */
$cabecalhos = [
    '3' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 3ª REGIÃO MILITAR<br>",
    '5' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 5ª REGIÃO MILITAR<br>",
];

$conexao = new Conexao();
$rm_usuario = $conexao->rm_usuario($_SESSION['id_usuario']);
$_SESSION['cabecalho_relatorio'] = $cabecalhos[$rm_usuario] ?? '';

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    {$_SESSION['cabecalho_relatorio']}
</p>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='center'><strong>$titulo</strong></th></tr>
</table>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='center'><strong>$subtitulo</strong></th></tr>
</table>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='right'><strong>$data</strong></th></tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um</p>
<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois</p>
";

$mpdf->WriteHTML($html);

/* ================= RECURSOS ================= */
$lista_candidatos_recurso = $conexao->get_recursos();

usort($lista_candidatos_recurso, function ($a, $b) {
    return strcmp(mb_strtolower($a['nome_completo']), mb_strtolower($b['nome_completo']));
});

/* ===== RECURSOS 3 - IS ===== */
$existe_is = false;
foreach ($lista_candidatos_recurso as $r) {
    if ($r['etapa'] == 3 && $r['obs_etapa'] == '3 - IS' && $r['id_selecao'] == $_SESSION['selecao']) {
        $existe_is = true;
        break;
    }
}

if ($existe_is) {

    $html = "
    <p style='font-size: 12px; text-align: justify; margin: 20px 0 5px 0; text-indent: 2em;'>
        <strong>1. RESULTADO DA ANÁLISE DE RECURSO ETAPA III - JISE</strong>
    </p>

    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
            <th style='width:5%'>Nº</th>
            <th style='width:15%'>CPF</th>
            <th style='width:30%'>NOME</th>
            <th style='width:25%'>PARECER</th>
            <th style='width:25%'>ESPECIALIDADE</th>
        </tr>";

    $mpdf->WriteHTML($html);

    $contador = 1;
    foreach ($lista_candidatos_recurso as $candidato) {

        if ($candidato['etapa'] != 3 || $candidato['obs_etapa'] != '3 - IS' || $candidato['id_selecao'] != $_SESSION['selecao']) {
            continue;
        }

        if (!empty($data_inicial) && !empty($data_final)) {
            $ts = strtotime($candidato['data_abertura']);
            if ($ts < strtotime($data_inicial) || $ts > strtotime($data_final)) {
                continue;
            }
        }

        $cpf = substr($candidato['cpf'], 0, -5) . "*****";

        $html = "
        <tr>
            <td style='text-align:center;'>$contador</td>
            <td style='text-align:center;'>$cpf</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato['status_final']) . "</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato['nome_especialidade']) . "</td>
        </tr>";

        $mpdf->WriteHTML($html);
        $contador++;
    }

    $mpdf->WriteHTML("</table>");
}

/* ===== RECURSOS 3 - DOCUMENTAL (SÓ SE EXISTIR) ===== */
$existe_documental = false;
foreach ($lista_candidatos_recurso as $r) {
    if ($r['etapa'] == 3 && $r['obs_etapa'] == '3 - Documental' && $r['id_selecao'] == $_SESSION['selecao']) {
        $existe_documental = true;
        break;
    }
}

if ($existe_documental) {

    $html = "
    <p style='font-size: 12px; text-align: justify; margin: 20px 0 5px 0; text-indent: 2em;'>
        <strong>2. RESULTADO DA ANÁLISE DE RECURSO ETAPA III - DOCUMENTAL</strong>
    </p>

    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
            <th style='width:5%'>Nº</th>
            <th style='width:10%'>CPF</th>
            <th style='width:35%'>NOME</th>
            <th style='width:25%'>PARECER</th>
            <th style='width:25%'>ESPECIALIDADE</th>
        </tr>";

    $mpdf->WriteHTML($html);

    $contador = 1;
    foreach ($lista_candidatos_recurso as $candidato) {

        if ($candidato['etapa'] != 3 || $candidato['obs_etapa'] != '3 - Documental' || $candidato['id_selecao'] != $_SESSION['selecao']) {
            continue;
        }

        $cpf = substr($candidato['cpf'], 0, -5) . "*****";

        $html = "
        <tr>
            <td style='text-align:center;'>$contador</td>
            <td style='text-align:center;'>$cpf</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato['status_final']) . "</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato['nome_especialidade']) . "</td>
        </tr>";

        $mpdf->WriteHTML($html);
        $contador++;
    }

    $mpdf->WriteHTML("</table>");
}

/* ================= CONVOCAÇÃO ISGR ================= */
/* ================= CONVOCAÇÃO ISGR ================= */

function imprime_grupo_isgr($mpdf, $conexao, $titulo, $data, $om, $endereco, $lista_ids)
{
    if (empty($lista_ids)) {
        return;
    }

    $html = "
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                " . mb_strtoupper('INSPEÇÃO DE SAÚDE EM GRAU DE RECURSO <br>' . $data . ' - ' . $om . '<br>') . $endereco . "
            </th>
        </tr>
        <tr style='text-align: center; font-size: 12px;'>
            <th style='width:5%'>Nº</th>
            <th style='width:10%'>CPF</th>
            <th style='width:85%'>NOME</th>
        </tr>";

    $mpdf->WriteHTML($html);

    $candidatos = [];
    foreach ($lista_ids as $id) {
        $candidatos[] = $conexao->get_usuario_id($id);
    }

    usort($candidatos, function ($a, $b) {
        return strcmp(mb_strtolower($a[0]['nome_completo']), mb_strtolower($b[0]['nome_completo']));
    });

    $contador = 1;
    foreach ($candidatos as $candidato) {

        $cpf = substr($candidato[0]['cpf'], 0, -5) . "*****";

        $html = "
        <tr>
            <td style='text-align:center;'>$contador</td>
            <td style='text-align:center;'>$cpf</td>
            <td style='text-align:center;'>" . mb_strtoupper($candidato[0]['nome_completo']) . "</td>
        </tr>";

        $mpdf->WriteHTML($html);
        $contador++;
    }

    $mpdf->WriteHTML("</table>");
}

/* ===== TÍTULO GERAL ===== */
if (
    !empty($candidatos_grupo_um_dia_um) ||
    !empty($candidatos_grupo_um_dia_dois) ||
    !empty($candidatos_grupo_um_dia_tres) ||
    !empty($candidatos_grupo_dois_dia_um) ||
    !empty($candidatos_grupo_dois_dia_dois) ||
    !empty($candidatos_grupo_dois_dia_tres) ||
    !empty($candidatos_grupo_tres_dia_um) ||
    !empty($candidatos_grupo_tres_dia_dois) ||
    !empty($candidatos_grupo_tres_dia_tres)
) {
    $mpdf->WriteHTML("
        <p style='font-size: 12px; text-align: justify; margin: 20px 0 5px 0; text-indent: 2em;'>
            <strong>3. CONVOCAÇÃO PARA ISGR (INSPEÇÃO DE SAÚDE EM GRAU DE RECURSO)</strong>
        </p>
    ");
}

/* ===== GRUPO 1 ===== */
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 1', $data_grupo_um_dia_um, $om_grupo_um, $endereco_grupo_um, $candidatos_grupo_um_dia_um);
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 1', $data_grupo_um_dia_dois, $om_grupo_um, $endereco_grupo_um, $candidatos_grupo_um_dia_dois);
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 1', $data_grupo_um_dia_tres, $om_grupo_um, $endereco_grupo_um, $candidatos_grupo_um_dia_tres);

/* ===== GRUPO 2 ===== */
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 2', $data_grupo_dois_dia_um, $om_grupo_dois, $endereco_grupo_dois, $candidatos_grupo_dois_dia_um);
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 2', $data_grupo_dois_dia_dois, $om_grupo_dois, $endereco_grupo_dois, $candidatos_grupo_dois_dia_dois);
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 2', $data_grupo_dois_dia_tres, $om_grupo_dois, $endereco_grupo_dois, $candidatos_grupo_dois_dia_tres);

/* ===== GRUPO 3 ===== */
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 3', $data_grupo_tres_dia_um, $om_grupo_tres, $endereco_grupo_tres, $candidatos_grupo_tres_dia_um);
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 3', $data_grupo_tres_dia_dois, $om_grupo_tres, $endereco_grupo_tres, $candidatos_grupo_tres_dia_dois);
imprime_grupo_isgr($mpdf, $conexao, 'GRUPO 3', $data_grupo_tres_dia_tres, $om_grupo_tres, $endereco_grupo_tres, $candidatos_grupo_tres_dia_tres);


$mpdf->Output("Resultado Análise Recursos Etapa III e Convocação para ISGR.pdf", 'D');
ob_end_flush();
exit();
