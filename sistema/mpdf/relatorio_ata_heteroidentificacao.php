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

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $_POST['rm_usuario'];

$titulo = $_POST['titulo'];
$subtitulo = $_POST['subtitulo'];
$paragrafo_um = $_POST['paragrafo_um'];

$fase = (int)$_POST['fase'];

$tipo_inspecao = ($fase == 1) ? 'Complementar' : 'Revisora';

$data_inspecao = $_POST['data_inspecao'];

set_time_limit(300);

if (!isset($_SESSION['perfil'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if ($_SESSION['perfil'] != 'admin') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
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
        <th align='center'><strong>$titulo</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='center'><strong>$subtitulo</strong></th>
    </tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um</p> 
";
$mpdf->AddPage('L');
$mpdf->WriteHTML($html);

$lista_candidatos = $conexao->get_candidatos_concorrendo();

// acumulador de linhas
$linhasHtml = '';
$contador = 1;

foreach ($lista_candidatos as $candidato) {

    $cpf = substr($candidato['cpf'], 0, -5) . "*****";
    $pareceres = $conexao->get_pareceres_heteroidentificacao($candidato['id']);

    // Converter data para comparação
    $dataObj = DateTime::createFromFormat('d/m/Y', $data_inspecao);
    $data_inspecao_formatada = $dataObj ? $dataObj->format('Y-m-d') : null;

    // Verifica se o candidato tem avaliação na data
    $inspecaoRealizada = false;
    foreach ($pareceres as $parecer) {
        $dataParecer = substr($parecer['data_avaliacao'], 0, 10);
        if ($dataParecer === $data_inspecao_formatada) {
            $inspecaoRealizada = true;
            break;
        }
    }

    if (!$inspecaoRealizada) {
        continue;
    }

    $qConfirmados = 0;
    $qNaoConfirmados = 0;
    $qNaoCompareceu = 0;

    foreach ($pareceres as $parecer) {
        if ($parecer['fase'] != $fase) continue;

        if ($parecer['parecer'] === 'confirmada') {
            $qConfirmados++;
        } elseif ($parecer['parecer'] === 'nao_confirmada') {
            $qNaoConfirmados++;
        } elseif ($parecer['parecer'] === 'nao_compareceu') {
            $qNaoCompareceu++;
        }
    }

    // Total de pareceres (inclui todos os 3 tipos)
    $totalPareceres = $qConfirmados + $qNaoConfirmados + $qNaoCompareceu;

    // Regras por fase
    $minConfirmados = $fase === 1 ? 3 : 2;
    $minTotal = $fase === 1 ? 5 : 3;

    // VERIFICAÇÃO PRINCIPAL: Atingiu o número total de pareceres necessários?
    if ($totalPareceres === $minTotal) {

        // Se sim, verifica qual o resultado majoritário:

        if ($qConfirmados >= $minConfirmados) {
            $resultado = 'CONFIRMADA';
        } elseif ($qNaoConfirmados >= $minConfirmados) {
            $resultado = 'NÃO CONFIRMADA';
        }
        // >>> NOVO: Se todos os pareceres foram 'nao_compareceu', define o resultado específico
        elseif ($qNaoCompareceu === $minTotal) {
            $resultado = 'NÃO COMPARECEU';
        } else {
            // Caso atinja o total, mas a distribuição não seja clara (ex: 1 sim, 1 nao, 1 nao compareceu, minTotal=3)
            $resultado = 'PENDENTE';
        }
    } else {
        // Se o total mínimo de pareceres ainda não foi atingido
        $resultado = 'PENDENTE';
    }

    // Monta a linha dentro do acumulador global
    $linhasHtml .= "
        <tr>
            <td style='text-align: center;'>$contador</td>
            <td style='text-align: center;'>$cpf</td>
            <td style='text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align: center;'>" . mb_strtoupper($resultado) . "</td>
        </tr>";

    $contador++;
}

// monta UMA ÚNICA tabela
if (!empty($linhasHtml)) {
    $html = "
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                RESULTADO
            </th>
        </tr>

        <tr>
            <th style='text-align: center; width: 10%;'>Nº</th>
            <th style='text-align: center; width: 15%;'>CPF</th>
            <th style='text-align: center; width: 45%;'>NOME</th>
            <th style='text-align: center; width: 30%;'>RESULTADO</th>
        </tr>

        $linhasHtml
    </table>";

    $mpdf->WriteHTML($html);
}

$numero_membros = ($fase == 1) ? 5 : 3;

// Quando são 5 membros (fase 1), dividir em duas linhas
if ($numero_membros === 5) {
    $html = "
    <table border='0' style='font-size: 12px; width:100%; margin-top: 30px;'>
        <tr>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
        </tr>
        <tr>
            <td style='text-align:center;'>1º Membro</td>
            <td style='text-align:center;'>2º Membro</td>
            <td style='text-align:center;'>3º Membro</td>
            <td style='text-align:center;'>4º Membro</td>
        </tr>
    </table>

    <table border='0' style='font-size: 12px; width:100%; margin-top: 40px;'>
        <tr>
            <th>___________________________________________</th>
        </tr>
        <tr>
            <td style='text-align:center;'>Presidente da Comissão</td>
        </tr>
    </table>";
} else {
    $html = "
    <table border='0' style='font-size: 12px; width:100%; margin-top: 30px;'>
        <tr>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
        </tr>
        <tr>
            <td style='text-align:center;'>1º Membro</td>
            <td style='text-align:center;'>2º Membro</td>
            <td style='text-align:center;'>Presidente da Comissão</td>
        </tr>
    </table>";
}


$mpdf->WriteHTML($html);

// Gera o PDF
$mpdf->Output("Ata Heteroidentificação " . $tipo_inspecao . " " . $data_inspecao . ".pdf", 'D');
ob_end_flush();
exit;
