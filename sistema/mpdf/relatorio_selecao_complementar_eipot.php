<?php

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$titulo = $_POST['titulo_convocacao_eipot'];
$subtitulo = $_POST['subtitulo_convocacao_eipot'];
$paragrafo_um_convocacao_eipot = $_POST['paragrafo_um_convocacao_eipot'];
$paragrafo_dois_convocacao_eipot = $_POST['paragrafo_dois_convocacao_eipot'];
$paragrafo_tres_convocacao_eipot = $_POST['paragrafo_tres_convocacao_eipot'];


$paragrafo_um_convocacao = $_POST['paragrafo_um_convocacao'];
$paragrafo_dois_convocacao = $_POST['paragrafo_dois_convocacao'];

$texto_dia = $_POST['texto_dia'];

$hora_arma = $_POST['hora_arma'];

session_start();

if ($_SESSION['perfil'] != 'admin') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}

if ($_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 3541621441 ao gerar relatório!");
    exit();
}

// Cabeçalhos por RM
$cabecalhos = [
    '1' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 1ª REGIÃO MILITAR<br>(4º Dist Mil/1891)<br>REGIÃO MARECHAL HERMES DA FONSECA",
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

$conexao = new Conexao();

$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

// Define o cabeçalho
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
    <tr><th align='right'><strong>$texto_dia</strong></th></tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um_convocacao_eipot</p>
<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois_convocacao_eipot</p>
<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_tres_convocacao_eipot</p>";

$mpdf->WriteHTML($html);

$lista_candidatos = $conexao->get_candidatos_eipot_reserva();

// Ordem específica de exibição das armas
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
    if ((int) $inscrito['rm_escolheu_servir'] !== $rm_usuario || (int) empty($inscrito['rm_escolheu_servir']) || !$inscrito['concorrendo']) {
        continue;
    }

    $arma = $inscrito['arma_especialidade'];
    $inscritos_por_arma[$arma][] = $inscrito;
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

// Ordena candidatos por ordem_escolha_guarnicao e adiciona classificação 01, 02, 03...
foreach ($inscritos_por_arma as &$candidatos) {
    usort($candidatos, function ($a, $b) {
        return $a['ordem_escolha_guarnicao'] <=> $b['ordem_escolha_guarnicao'];
    });

    foreach ($candidatos as $i => &$inscrito) {
        $inscrito['classificacao'] = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
    }
    unset($inscrito);
}
unset($candidatos);

foreach ($inscritos_por_arma as $arma => $candidatos) {
    if (count($candidatos) === 0) {
        continue; // Não cria tabela se não houver candidatos
    }

    // Buscar a hora com base na similaridade da chave
    $hora_local = '';
    $arma_normalizada = normaliza_texto($arma);

    foreach ($hora_arma as $chave => $valor) {
        $chave_normalizada = normaliza_texto($chave);

        if (
            strpos($arma_normalizada, $chave_normalizada) !== false ||
            strpos($chave_normalizada, $arma_normalizada) !== false
        ) {
            $hora_local = $valor;
            break;
        }
    }

    $html = "
    <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>". $hora_local ."</p>
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr>
        <th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>"
        . mb_strtoupper($arma, 'UTF-8') . " <br>" . " <br>
        </th>
    </tr>

    <tr>
        <th style='font-size: 12px; text-align: center; width: 5%;'>ORD</th>
        <th style='font-size: 12px; text-align: center; width: 30%;'>CPF</th>
        <th style='font-size: 12px; text-align: center; width: 65%;'>NOME</th>
    </tr>
    ";

    $contador = 1;

    foreach ($candidatos as $candidato) {
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $html .= "
        <tr>
            <td style='font-size: 12px; text-align: center;'>$contador</td>
            <td style='font-size: 12px; text-align: center;'>$cpf</td>
            <td style='font-size: 12px; text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
        </tr>";
        $contador++;
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}

$mpdf->Output("Convocação Seleção Complementar", 'D');

exit();
