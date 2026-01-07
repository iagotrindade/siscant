<?php
ob_start();
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$titulo = htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8');
$subtitulo = htmlspecialchars($_POST['subtitulo'] ?? '', ENT_QUOTES, 'UTF-8');

$paragrafo_um  = htmlspecialchars($_POST['paragrafo_um'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_dois = htmlspecialchars($_POST['paragrafo_dois'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_tres = htmlspecialchars($_POST['paragrafo_tres'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_quatro = htmlspecialchars($_POST['paragrafo_quatro'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_cinco = htmlspecialchars($_POST['paragrafo_cinco'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_seis = htmlspecialchars($_POST['paragrafo_seis'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_sete = htmlspecialchars($_POST['paragrafo_sete'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_oito = htmlspecialchars($_POST['paragrafo_oito'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_nove = htmlspecialchars($_POST['paragrafo_nove'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_dez = htmlspecialchars($_POST['paragrafo_dez'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_onze = htmlspecialchars($_POST['paragrafo_onze'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_doze = htmlspecialchars($_POST['paragrafo_doze'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_treze = htmlspecialchars($_POST['paragrafo_treze'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_quatroze = htmlspecialchars($_POST['paragrafo_quatroze'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_quinze = htmlspecialchars($_POST['paragrafo_quinze'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_dezeseis = htmlspecialchars($_POST['paragrafo_dezeseis'] ?? '', ENT_QUOTES, 'UTF-8');
$paragrafo_dezesete = htmlspecialchars($_POST['paragrafo_dezesete'] ?? '', ENT_QUOTES, 'UTF-8');


$data = htmlspecialchars($_POST['data'] ?? '', ENT_QUOTES, 'UTF-8');

set_time_limit(300);

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

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    {$cabecalhos['' .$rm_usuario . '']}
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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_tres</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_quatro</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_cinco</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_seis</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_sete</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_oito</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_nove</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 6em;'>$paragrafo_dez</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 6em;'>$paragrafo_onze</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 6em;'>$paragrafo_doze</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_treze</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_quatroze</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_quinze</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 4em;'>$paragrafo_dezeseis</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dezesete</p>";

$mpdf->WriteHTML($html);

$lista_especialidades = $conexao->get_especialidade();
$candidatos = [];

foreach ($lista_especialidades as $especialidade) {
    $candidatos_especialidade = $conexao->get_candidatos_especialidade($especialidade['id']);

    foreach ($candidatos_especialidade as $candidato) {
        if (empty($candidato['cidade_escolheu_servir'])) {
            continue;
        }
        $candidato['especialidade'] = $especialidade['nome'];
        $candidato['especialidade_id'] = $especialidade['id'];
        $candidato['ott_stt'] = $especialidade['ott_stt'];
        $candidatos[] = $candidato;
    }
}

usort($candidatos, function ($a, $b) {
    return [
        $a['ott_stt'] === 'ott' ? 0 : 1, // ott vem primeiro (0 < 1)
        $a['cidade_escolheu_servir'],
        $a['especialidade'],
        $a['nome_completo']
    ] <=> [
        $b['ott_stt'] === 'ott' ? 0 : 1,
        $b['cidade_escolheu_servir'],
        $b['especialidade'],
        $b['nome_completo']
    ];
});

// Agrupar por cidade e especialidade
$candidatos_agrupados = [];
foreach ($candidatos as $candidato) {
    $cidade = $candidato['cidade_escolheu_servir'];
    $especialidade = $candidato['especialidade'];
    $ott_stt = $candidato['ott_stt'];

    $id_om = $candidato['om_1_fase'];
    $om = $conexao->get_om_id($id_om);

    // Armazenar informações da OM junto com o candidato
    $candidato['om_nome'] = $om[0]['nome'] ?? '';
    $candidato['om_endereco'] = ($om[0]['endereco'] ?? '');
    $candidato['ott_stt'] = $ott_stt; // Garantir que está disponível

    $candidatos_agrupados[$cidade][$especialidade][] = $candidato;
}

// Gerar HTML
$html_candidatos = "";
$letra_atual = 'a';
$indice_letra = 0; // Para controlar quando chegar no 'z'

foreach ($candidatos_agrupados as $cidade => $especialidades) {
    foreach ($especialidades as $especialidade => $candidatos_especialidade) {
        // Pegar informações do primeiro candidato (todos da mesma cidade/especialidade terão os mesmos dados)
        $primeiro_candidato = $candidatos_especialidade[0];
        $ott_stt = $primeiro_candidato['ott_stt'];
        $om_nome = $primeiro_candidato['om_nome'];
        $endereco_om = $primeiro_candidato['om_endereco'];

        // Adicionar a letra antes da tabela
        $html_candidatos .= "<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>
            {$letra_atual}. O(s) candidato(s) a " . mb_strtoupper($ott_stt) . " para servirem na Guarnição de 
            <strong>" . mb_strtoupper($cidade) . "</strong>, deverão apresentar-se no(a) " . $om_nome . ", 
            localizado(a) na(o) {$endereco_om}
        </p>";

        // Tabela
        $html_candidatos .= "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='3' style='text-align: center; background-color: #f0f0f0; font-weight: bold;'>
                    <div style='text-transform: uppercase;'>{$especialidade}</div>
                    <div>{$cidade}</div>
                </th>
            </tr>
            <tr>
                <th style='font-size: 12px; width: 50px;'>ORD</th>
                <th style='font-size: 12px;'>NOME</th>
                <th style='font-size: 12px; width: 100px;'>OBSERVAÇÃO</th>
            </tr>
        ";

        $contador = 1;
        foreach ($candidatos_especialidade as $candidato) {
            $html_candidatos .= "
            <tr>
                <td style='font-size: 12px; text-align: center;'>{$contador}</td>
                <td style='font-size: 12px;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
                <td style='font-size: 12px; text-align: center;'>-</td>
            </tr>
            ";
            $contador++;
        }

        $html_candidatos .= "</table>";

        // Avançar para próxima letra
        $letra_atual = ++$letra_atual;
        $indice_letra++;

        // Se passou do 'z', começar com 'aa', 'ab', etc.
        if ($letra_atual > 'z') {
            $letra_atual = 'a' . chr(96 + ($indice_letra - 25));
        }
    }
}

$mpdf->WriteHTML($html_candidatos);

$mpdf->Output("Convocação para Seleção Complementar.pdf", 'D');
ob_end_flush();
exit();
