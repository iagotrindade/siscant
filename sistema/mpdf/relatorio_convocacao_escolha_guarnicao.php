<?php

ob_start();

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$titulo = $_POST['titulo'];
$subtitulo = $_POST['subtitulo'];
$data = $_POST['data'];
$paragrafo_um = $_POST['paragrafo_um'];
$paragrafo_dois = $_POST['paragrafo_dois'];
$paragrafo_tres = $_POST['paragrafo_tres'];
$paragrafo_quatro = $_POST['paragrafo_quatro'];
$paragrafo_cinco = $_POST['paragrafo_cinco'];
$paragrafo_seis = $_POST['paragrafo_seis'];
$paragrafo_sete = $_POST['paragrafo_sete'];
$paragrafo_oito = $_POST['paragrafo_oito'];

$data_dia_um = $_POST['data_dia_um'];
$data_dia_dois = $_POST['data_dia_dois'];
$data_dia_tres = $_POST['data_dia_tres'];
$data_dia_quatro = $_POST['data_dia_quatro'];

session_start();

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
    '3' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DO SUL<br>COMANDO DA 3ª REGIÃO MILITAR<br>(Gov das Armas Prov do RS/1821)<br>REGIÃO DOM DIOGO DE SOUZA<br>",
    '8' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 8ª REGIÃO MILITAR<br>(Gov das Armas Prov do PA/1821)<br>REGIÃO FORTE DO PRESÉPIO<br>",
    '6' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 6ª REGIÃO MILITAR<br>(Governo das Armas Província da Bahia/1821)<br>REGIÃO MARECHAL CANTUÁRIA<br>",
    '12' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DA 12ª REGIÃO MILITAR<br>(Comando de Elementos de Fronteia/1948)<br>(FORTE MENDONÇA FURTADO<br>",
    '7' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 7ª REGIÃO MILITAR<br>(Gov das Armas Prov PE/1821)<br>REGIÃO MATIAS DE ALBUQUERQUE<br>",
    '5' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 5ª REGIÃO MILITAR<br>(Comando das Armas do Estado do Paraná/1990)<br>REGIÃO HERÓIS DA LAPA<br>",
    '11' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 11ª REGIÃO MILITAR<br>(Cmdo Mil Bsb/1960)<br>REGIÃO TENENTE-CORONEL LUIZ CRULS<br>",
    '2' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 2ª REGIÃO MILITAR<br>(Cmdo das Armas Prov Pr/1890)<br>REGIÃO DAS BANDEIRAS<br>",
    '4' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 4ª REGIÃO MILITAR<br>(4⁰ Distrito Militar/1891)<br>REGIÃO DAS MINAS DO OURO<br>",
];

$html = " <p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'> <img src='../imagens/brasao.png' width='70px'><br> {$cabecalhos['' .$rm_usuario . '']} </p>
<table border='0' style='width:100%; margin-top: 5px;'>
    <tr>
        <th align='center'>
            <strong>$titulo</strong>
        </th>
    </tr>
</table>
<table border='0' style='width:100%; margin-top: 5px;'>
    <tr>
        <th align='center'>
            <strong>$subtitulo</strong>
        </th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr>
        <th align='right'>
            <strong>$data</strong>
        </th>
    </tr> 
</table> 

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_tres</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_quatro</p>

<table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr> 
        <th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
            TABELA EXEMPLIFICATIVA
        </th>
    </tr>

    <tr style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
        <th style='text-align: center; width: 10%;'>ORD</th>
        <th style='text-align: center; width: 20%;'>CLASSIFICAÇÃO</th>
        <th style='text-align: center; width: 50%;'>Horário que o SISCANT estará disponível para o candidato efetuar a ESCOLHA
DE GUARNIÇÃO.</th>
    </tr>
    <tr>
        <td style='text-align: center;'>01</td>
        <td style='text-align: center;'>1º</td>
        <td style='text-align: center;'>Das 0800 às 0805 h (não é necessário usar o tempo total)</td>
    </tr>

    <tr>
        <td style='text-align: center;'>02</td>
        <td style='text-align: center;'>2º</td>
        <td style='text-align: center;'>Das 0805 às 0810 h (quando o primeiro terminar já abre para a escolha)</td>
    </tr>

    <tr>
        <td style='text-align: center;'>03</td>
        <td style='text-align: center;'>3º</td>
        <td style='text-align: center;'>Das 0810 às 080 15 h (sucessivamente até terminar as vagas disponíveis)</td>
    </tr>

    <tr>
        <td style='text-align: center;'>.</td>
        <td style='text-align: center;'>.</td>
        <td style='text-align: center;'>.</td>
    </tr>

    <tr>
        <td style='text-align: center;'>.</td>
        <td style='text-align: center;'>.</td>
        <td style='text-align: center;'>.</td>
    </tr>

    <tr>
        <td style='text-align: center;'>Último</td>
        <td style='text-align: center;'>Último classificado</td>
        <td style='text-align: center;'>Escolha de 5 em 5 minutos até o último classificado</td>
    </tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_cinco</p>";

$mpdf->WriteHTML($html);

$especialidades_dia_um = [];
$especialidades_dia_dois = [];
$especialidades_dia_tres = [];
$especialidades_dia_quatro = [];

// Criar tabela com cronograma de escolha conforme especialidades e datas
foreach ($_POST['especialidades_dia_um'] as $especialidade) {
    $especialidades_dia_um[] = $conexao->get_especialidade_id($especialidade);
}

foreach ($_POST['especialidades_dia_dois'] as $especialidade) {
    $especialidades_dia_dois[] = $conexao->get_especialidade_id($especialidade);
}

foreach ($_POST['especialidades_dia_tres'] as $especialidade) {
    $especialidades_dia_tres[] = $conexao->get_especialidade_id($especialidade);
}

foreach ($_POST['especialidades_dia_quatro'] as $especialidade) {
    $especialidades_dia_quatro[] = $conexao->get_especialidade_id($especialidade);
}

$html = "
<table border='1' style='width:100%; border-collapse:collapse; font-size:12px;'>
    <tr style='background:#D8D8D8; text-align:center; font-weight:bold;'>
        <th style='width:5%;'>ORD</th>
        <th style='width:50%;'>ESPECIALIDADE</th>
        <th style='width:40%;'>DATA</th>
    </tr>
";

$ord = 1;

/* ================= DIA 1 ================= */
$total = count($especialidades_dia_um);
$i = 0;
foreach ($especialidades_dia_um as $esp) {
    if ($i == 0) {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
            <td align='center' rowspan='$total'><strong>$data_dia_um</strong></td>
        </tr>";
    } else {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
        </tr>";
    }
    $ord++;
    $i++;
}

/* ================= DIA 2 ================= */
$total = count($especialidades_dia_dois);
$i = 0;
foreach ($especialidades_dia_dois as $esp) {
    if ($i == 0) {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
            <td align='center' rowspan='$total'><strong>$data_dia_dois</strong></td>
        </tr>";
    } else {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
        </tr>";
    }
    $ord++;
    $i++;
}

/* ================= DIA 3 ================= */
$total = count($especialidades_dia_tres);
$i = 0;
foreach ($especialidades_dia_tres as $esp) {
    if ($i == 0) {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
            <td align='center' rowspan='$total'><strong>$data_dia_tres</strong></td>
        </tr>";
    } else {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
        </tr>";
    }
    $ord++;
    $i++;
}

/* ================= DIA 4 ================= */
$total = count($especialidades_dia_quatro);
$i = 0;
foreach ($especialidades_dia_quatro as $esp) {
    if ($i == 0) {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
                <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
            <td align='center' rowspan='$total'><strong>$data_dia_quatro</strong></td>
        </tr>";
    } else {
        $html .= "
        <tr>
            <td align='center'>$ord</td>
            <td>" . mb_strtoupper($esp[0]['ott_stt']) . " - " . mb_strtoupper($esp[0]['nome']) . "</td>
        </tr>";
    }
    $ord++;
    $i++;
}

$html .= "</table>";

$html .= "
    <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_seis</p>
    <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_sete</p>
    <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_oito</p>
";

// Tabela de vagas por especialidade e guarnição

$todas_especialidades = [];
$vagas = [];

$todas_especialidades = array_merge(
    $especialidades_dia_um,
    $especialidades_dia_dois,
    $especialidades_dia_tres,
    $especialidades_dia_quatro
);

foreach ($todas_especialidades as $especialidade) {
    $vagas[$especialidade[0]['id']] = $conexao->get_cidades_especialidade($especialidade[0]['id']);
}


$ord = 1;

$html .= "
<table border='1' style='width:100%; border-collapse: collapse; margin-top: 20px;'>
    <tr style='background-color:#D8D8D8; text-align:center;'>
        <th style='width:5%'>ORD</th>
        <th style='width:35%'>ESPECIALIDADE</th>
        <th style='width:10%'>VAGAS</th>
        <th style='width:50%'>GUARNIÇÕES</th>
    </tr>
";

usort($todas_especialidades, function ($a, $b) {

    $ottA = $a[0]['ott_stt'];
    $ottB = $b[0]['ott_stt'];

    // 1️⃣ Ordena por OTT / STT
    if ($ottA !== $ottB) {
        return strcmp($ottA, $ottB);
        // OTT vem antes de STT automaticamente
    }

    // 2️⃣ Se OTT/STT for igual, ordena pelo nome
    return strcasecmp($a[0]['nome'], $b[0]['nome']);
});

foreach ($todas_especialidades as $especialidade) {

    $id_especialidade = $especialidade[0]['id'];
    $nome_especialidade = mb_strtoupper($especialidade[0]['ott_stt'] . " - " . $especialidade[0]['nome']);

    $total_vagas = 0;
    $guarnicoes = [];

    foreach ($vagas[$id_especialidade] as $vaga) {

        if ((int)$vaga['numero_vagas'] <= 0) continue;

        $cidade = $vaga['nome'];
        $qtd = (int)$vaga['numero_vagas'];

        $total_vagas += $qtd;

        if (!isset($guarnicoes[$cidade])) {
            $guarnicoes[$cidade] = 0;
        }

        $guarnicoes[$cidade] += $qtd;
    }

    if ($total_vagas === 0) continue;

    // Monta string: "Porto Alegre - 3 / Santa Maria - 1"
    $texto_guarnicoes = [];
    foreach ($guarnicoes as $cidade => $qtd) {
        $texto_guarnicoes[] = "{$cidade} - {$qtd}";
    }

    $html .= "
    <tr>
        <td style='text-align:center'>{$ord}</td>
        <td>{$nome_especialidade}</td>
        <td style='text-align:center'>{$total_vagas}</td>
        <td style='text-align:center'>" . implode(' / ', $texto_guarnicoes) . "</td>
    </tr>
    ";

    $ord++;
}

$html .= "</table>";

$mpdf->WriteHTML($html);

$mpdf->Output("Convocação Escolha de Guarnição.pdf", 'D');
ob_end_flush();
exit();
