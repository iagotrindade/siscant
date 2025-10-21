<?php

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$titulo = $_POST['titulo_recursos_etapa_iv'];
$subtitulo = $_POST['subtitulo_recursos_etapa_iv'];
$paragrafo_um_recursos_etapa_iv = $_POST['paragrafo_um_recursos_etapa_iv'];

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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um_recursos_etapa_iv</p>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='left'><strong>1. RESULTADO DA ANÁLISE DOS RECURSOS</strong></th></tr>
</table>";

$mpdf->WriteHTML($html);

$lista_candidatos_recurso = $conexao->get_candidatos_recurso($rm_usuario);

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

foreach ($lista_candidatos_recurso as $inscrito) {
    if ($inscrito['etapa'] < 4) {
        continue;
    }
    if ($inscrito['rm_inscricao'] == $rm_usuario) { // Filtra por rm_inscricao
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
    if (count($candidatos) === 0) {
        continue; // Não cria tabela se não houver candidatos
    }

    $html = "
   <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr>
        <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>" . mb_strtoupper($arma, "UTF-8") . "</th>
    </tr>
    <tr>
        <th style='font-size: 12px; text-align: center; width: 5%;'>Nº</th>
        <th style='font-size: 12px; text-align: center; width: 15%;'>CPF</th>
        <th style='font-size: 12px; text-align: center; width: 55%;'>NOME</th>
         <th style='font-size: 12px; text-align: center; width: 25%;'>PARECER</th>
    </tr>
    ";

    $contador = 1;

    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] != '4')
            continue;
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $html .= "
        <tr>
            <td style='font-size: 12px; text-align: center;'>$contador</td>
            <td style='font-size: 12px; text-align: center;'>$cpf</td>
           <td style='font-size: 12px; text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='font-size: 12px; text-align: center;'>" . mb_strtoupper($candidato['status_final']) . "</td>
        </tr>";
        $contador++;
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}

// Se necessário, adicione um AddPage() no final para uma nova página após todas as tabelas

//$mpdf->SetDisplayMode('fullwidth');

//$mpdf->WriteHTML($html);
$mpdf->Output("Resultado Análise Recursos Etapa IV.pdf", 'D');

exit();
