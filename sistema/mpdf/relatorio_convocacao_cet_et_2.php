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
$agenda_especialidade = $_POST['agenda_especialidade'];

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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois</p>";

$mpdf->WriteHTML($html);

// CARREGAR TODOS OS CANDIDATOS E ESPECIALIDADES 
$lista_especialidades = $conexao->get_especialidade();

$contadorParagrafo = 1;

foreach ($lista_especialidades as $especialidade) {
    $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);

    // Verifica se existe pelo menos um candidato com etapa == 2
    $tem_candidato_etapa2 = false;
    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] == 2) {
            $tem_candidato_etapa2 = true;
            break;
        }
    }

    if (!$tem_candidato_etapa2) {
        continue; // Pula especialidades sem candidatos da etapa 2
    }

    $contador = 1;
    $agenda = $agenda_especialidade[$especialidade['id']] ?? '';

    $html = "
        <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>
            {$contadorParagrafo}. {$agenda}
        </p>

        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                    " . mb_strtoupper($especialidade['ott_stt'] . ' - ' . $especialidade['nome']) . "
                </th>
            </tr>
            <tr>
                <th style='text-align: center; width: 10%;'>Nº</th>
                <th style='text-align: center; width: 20%;'>CPF</th>
                <th style='text-align: center; width: 50%;'>NOME</th>
            </tr>
    ";

    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] < 2 || $candidato['etapa_candidato'] < 2) {
            continue;
        }

        // Máscara do CPF (mantém primeiros 6 dígitos e oculta o restante)
        $cpf = preg_replace('/\D/', '', $candidato['cpf']); // remove pontuação
        $cpf_mascarado = substr($cpf, 0, 6) . "*****";

        $html .= "
            <tr>
                <td style='text-align: center;'>{$contador}</td>
                <td style='text-align: center;'>{$cpf_mascarado}</td>
                <td style='text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            </tr>
        ";

        $contador++;
    }

    $contadorParagrafo++;
    $html .= "</table>";
    $mpdf->WriteHTML($html);
}

$mpdf->Output("Convocação Etapa II - Entrevista e Teste Prático.pdf", 'D');

$mpdf->Output("Convocação Etapa II - Entrevista e Teste Prático.pdf", 'D');
ob_end_flush();
exit();
