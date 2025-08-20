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
$paragrafo_um = $_POST['paragrafo_um'];
$paragrafo_dois = $_POST['paragrafo_dois'];

$data = $_POST['data'];

$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];

$etapa = $_POST['etapa'];

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
    <tr><th align='right'><strong>$data</strong></th></tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois</p>";

$mpdf->WriteHTML($html);

$lista_candidatos_recurso = $conexao->get_recursos();

$html = "
   <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>

    <tr style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
        <th style='font-size: 12px; text-align: center; width: 15%;'>CPF</th>
        <th style='font-size: 12px; text-align: center; width: 35%;'>NOME</th>
        <th style='font-size: 12px; text-align: center; width: 25%;'>PARECER</th>
        <th style='font-size: 12px; text-align: center; width: 25%;'>ESPECIALIDADE</th>
    </tr>
    ";

$mpdf->WriteHTML($html);

usort($lista_candidatos_recurso, function ($a, $b) {
    return strcmp(mb_strtolower($a['nome_completo']), mb_strtolower($b['nome_completo']));
});

foreach ($lista_candidatos_recurso as $candidato) {
    $contador = 1;

    // Filtro por etapa e seleção
    if ($candidato['etapa'] != $etapa || $candidato['id_selecao'] != $_SESSION['selecao']) {
        continue;
    }

    // Filtro por intervalo de datas
    if (!empty($data_inicial) && !empty($data_final)) {
        $data_abertura = $candidato['data_abertura'];

        // Normaliza formatos (caso venham diferentes)
        $data_abertura_ts = strtotime($data_abertura);
        $data_inicial_ts  = strtotime($data_inicial);
        $data_final_ts    = strtotime($data_final);

        if ($data_abertura_ts < $data_inicial_ts || $data_abertura_ts > $data_final_ts) {
            continue;
        }
    }

    $cpf = substr($candidato['cpf'], 0, -5) . "*****";
    $html = "
        <tr>
            <td style='font-size: 12px; text-align: center;'>$cpf</td>
            <td style='font-size: 12px; text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
            <td style='font-size: 12px; text-align: center;'>" . strtoupper($candidato['status_final']) . "</td>
            <td style='font-size: 12px; text-align: center;'>" . strtoupper($candidato['nome_especialidade']) . "</td>
        </tr>";

    $mpdf->WriteHTML($html);
    $contador++;
}

$html = "</table>";
$mpdf->WriteHTML($html);

$mpdf->Output("Resultado Análise Recursos Etapa I.pdf", 'D');
ob_end_flush();
exit();
