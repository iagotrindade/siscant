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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>No dia " . $data_inspecao . " reuniram-se os integrantes da Comissão de Heteroidentificação " . $tipo_inspecao . " com o intuito de inspecionar os candidatos abaixo relacionados.</p> 
";
$mpdf->AddPage('L');
$mpdf->WriteHTML($html);

$lista_candidatos = $conexao->get_inscritos_eipot_vagas_reservadas_tabelas($rm_usuario);

$ordem_arma = [
    'INFANTARIA',
    'CAVALARIA',
    'ARTILHARIA DE CAMPANHA',
    'ARTILHARIA ANTIAÉREA',
    'ENGENHARIA',
    'COMUNICAÇÕES',
    'MATERIAL BÉLICO',
    'INTENDÊNCIA'
];

$inscritos_por_arma = [];

foreach ($lista_candidatos as $inscrito) {
    if ((int)$inscrito['rm_inscricao'] == $rm_usuario) { // Filtra por rm_inscricao
        $arma = $inscrito['arma_especialidade'];
        $inscritos_por_arma[$arma][] = $inscrito;
    }
}

uksort($inscritos_por_arma, function ($a, $b) use ($ordem_arma) {
    // Verificando se a especialidade $a e $b estão na ordem específica
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);

    // Se não encontrar a especialidade, coloca no final e ordena alfabeticamente
    if ($pos_a === false) $pos_a = PHP_INT_MAX;
    if ($pos_b === false) $pos_b = PHP_INT_MAX;

    // Se ambos os valores estão fora da ordem (não definidos em $ordem_arma), ordena alfabeticamente
    if ($pos_a === PHP_INT_MAX && $pos_b === PHP_INT_MAX) {
        return strcasecmp($a, $b);
    }

    return $pos_a - $pos_b;
});

foreach ($inscritos_por_arma as $arma => $candidatos) {
    $linhasHtml = '';
    $contador = 1;

    foreach ($candidatos as $candidato) {
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $pareceres = $conexao->get_pareceres_heteroidentificacao($candidato['id']);

        // Converter $data_inspecao para Y-m-d para comparação
        $dataObj = DateTime::createFromFormat('d/m/Y', $data_inspecao);
        $data_inspecao_formatada = $dataObj ? $dataObj->format('Y-m-d') : null;
        // Verifica se há pareceres na data inspecionada
        $inspecaoRealizada = false;
        foreach ($pareceres as $parecer) {
            $dataParecer = substr($parecer['data_avaliacao'], 0, 10); // pega só Y-m-d
            if ($dataParecer === $data_inspecao_formatada) {
                $inspecaoRealizada = true;
                break;
            }
        }

        if (!$inspecaoRealizada) {
            continue;
        }

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
        <tr>
            <td style='text-align: center;'>{$contador}</td>
            <td style='text-align: center;'>{$cpf}</td>
            <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align: center;'>" . strtoupper($resultado) . "</td>
        </tr>";
        $contador++;
    }

    // Se houver pelo menos uma linha, monta e imprime a tabela
    if (!empty($linhasHtml)) {
        $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                    " . mb_strtoupper($arma, 'UTF-8') . "
                </th>
            </tr>
            <tr>
                <th style='text-align: center; width: 10%;'>Nº</th>
                <th style='text-align: center; width: 10%;'>CPF</th>
                <th style='text-align: center; width: 40%;'>NOME</th>
                <th style='text-align: center; width: 40%;'>RESULTADO</th>
            </tr>
            {$linhasHtml}
        </table>";

        $mpdf->WriteHTML($html);
    }
}

$numero_membros = ($fase == 1) ? 5 : 3;

$html = "<br><br><br>";

// Quando são 5 membros (fase 1), dividir em duas linhas
if ($numero_membros === 5) {
    $html .= "
    <table border='0' style='font-size: 12px; width:100%'>
        <tr>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
        </tr>
        <tr>
            <th>Membro</th>
            <th>Membro</th>
            <th>Membro</th>
            <th>Membro</th>
        </tr>
        <tr><td colspan='3'><br><br><br></td></tr>
    </table>";

    $html .= "
    <table border='0' style='font-size: 12px; width:100%'>
        <tr>
            <th>___________________________________________</th>
        </tr>
        <tr>
            <th width='50%'>Presidente da Comissão</th>
        </tr>
    </table>";
} else {
    // Para fase com 3 membros em uma única linha
    $html .= "
    <table border='0' style='font-size: 12px; width:100%'>
        <tr>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
            <th>___________________________________________</th>
        </tr>
        <tr>
            <th width='33%'>Membro</th>
            <th width='33%'>Membro</th>
            <th width='33%'>Presidente da Comissão</th>
        </tr>
    </table>";
}


$mpdf->WriteHTML($html);

// Gera o PDF
$mpdf->Output("Ata Heteroidentificação ". $tipo_inspecao . " " . $data_inspecao . ".pdf", 'D');
ob_end_flush();
exit;
