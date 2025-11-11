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

$data_inicial = $_POST['data_inicial'] ?? '';
$data_final = $_POST['data_final'] ?? '';

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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_tres</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_quatro</p>";

$mpdf->WriteHTML($html);

$id_especialidade = $_POST['especialidades'];

if (is_array($id_especialidade) && !empty($id_especialidade)) {
    $lista_especialidades = [];

    foreach ($id_especialidade as $id) {
        $result = $conexao->get_especialidade_selecionadas($id);
        $lista_especialidades = array_merge($lista_especialidades, $result);
    }
} else {
    $lista_especialidades = $conexao->get_especialidade();
}

foreach ($lista_especialidades as $especialidade) {
    $lista_candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);
    if (count($lista_candidatos) === 0) {
        continue;
    }

    $linhasCandidatos = "";
    $contador = 1;

    foreach ($lista_candidatos as $candidato) {
        if ($candidato['etapa'] < 4) {
            continue;
        }

        $cpf = strlen($candidato['cpf']) > 5
            ? substr($candidato['cpf'], 0, -5) . "******"
            : "******";

        $resultado = (float)$candidato['nota_prova_teorico_pratico'] >= 5 ? "APTO" : "INAPTO";

        $linhasCandidatos .= "
            <tr>
                <td style='text-align: center;'>$contador</td>
                <td style='text-align: center;'>$cpf</td>
                <td style='text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
                <td style='text-align: center;'>$resultado</td>
            </tr>";
        $contador++;
    }

    // Só gera tabela se tiver pelo menos uma linha de candidato
    if (!empty($linhasCandidatos)) {
        $html = "
            <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
                <tr>
                    <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>"
            . mb_strtoupper($especialidade['ott_stt'] . ' - ' . $especialidade['nome'], 'UTF-8') . "<br>" . "</th>
                </tr>
                <tr>
                    <th style='text-align: center; width: 5%;'>Nº</th>
                    <th style='text-align: center; width: 15%;'>CPF</th>
                    <th style='text-align: center; width: 45%;'>NOME</th>
                    <th style='text-align: center; width: 35%;'>RESULTADO</th>
                </tr>
                $linhasCandidatos
            </table>";

        $mpdf->WriteHTML($html);
    }
}

$mpdf->Output("Resultado Etapa IV - Teste Teórico/Prático.pdf", 'D');
ob_end_flush();
exit();
