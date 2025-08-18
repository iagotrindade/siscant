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
$paragrafo_tres = $_POST['paragrafo_tres'];
$paragrafo_quatro = $_POST['paragrafo_quatro'];

$data = $_POST['data'];

$especialidadesSelecionadas = $_POST['especialidades'];

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

if($etapa < 2)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 3252353 O SISCANT deve estar pelo menos na etapa II!");
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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_tres</p>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_quatro</p>";

$mpdf->WriteHTML($html);

$lista_especialidades = $conexao->get_especialidade();

$especialidades = [];
$especialidadesMusica = [];
$especialidadeInformatica = [];

foreach ($especialidadesSelecionadas as $index => $especialidade) {
    $especialidades[] = $conexao->get_especialidade_id($especialidade);
}

foreach ($especialidades as $especialidade) {

    if ($especialidade[0]['musica']) {
        $especialidadesMusica[$especialidade[0]['nome']] = $conexao->get_candidatos_especialidade($especialidade[0]['id']);
    }

    if ($especialidade[0]['teste_pratico'] && !$especialidade[0]['musica']) {
        $especialidadeInformatica[$especialidade[0]['nome']] = $conexao->get_candidatos_especialidade($especialidade[0]['id']);
    }
}

foreach ($especialidadeInformatica as $especialidade => $candidatos) {
    $contador = 1;

    $html = " 
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='5' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>" . mb_strtoupper($especialidade, "UTF-8") . "</th>
        </tr>
        <tr style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
            <th style='font-size: 12px; text-align: center; width: 5%;'>Nº</th>
            <th style='font-size: 12px; text-align: center; width: 15%;'>CPF</th>
            <th style='font-size: 12px; text-align: center; width: 40%;'>NOME</th>
            <th style='font-size: 12px; text-align: center; width: 20%;'>NOTA TC</th>
            <th style='font-size: 12px; text-align: center; width: 20%;'>OBS</th>
        </tr>
        ";

    $mpdf->WriteHTML($html);

    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] < 2) {
            continue;
        }

        usort($candidatos, function ($a, $b) {
            return strcmp($a['nota_prova_teorico_pratico'], $b['nota_prova_teorico_pratico']);
        });


        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $obs = '-';

        if (empty($candidato['nota_prova_teorico_pratico'])) {
            $nota = 'Faltou';
            $obs = 'Eliminado';
        } else {
            $nota = (int) $candidato['nota_prova_teorico_pratico'] / 2;
        }

        $html = "
            <tr>
            <td style='font-size: 12px; text-align: center;'>$contador</td>
                <td style='font-size: 12px; text-align: center;'>$cpf</td>
                <td style='font-size: 12px; text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
                <td style='font-size: 12px; text-align: center;'>$nota</td>
                <td style='font-size: 12px; text-align: center;'>$obs</td>
            </tr>";
        $contador++;

        $mpdf->WriteHTML($html);
    }

    $html = "</table>";
    $mpdf->WriteHTML($html);
}

foreach ($especialidadesMusica as $especialidade => $candidatos) {
    $contador = 1;

    $html = " 
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='7' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>" . mb_strtoupper($especialidade, "UTF-8") . "</th>
        </tr>
        <tr style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>
            <th style='font-size: 12px; text-align: center; width: 5%;'>Nº</th>
            <th style='font-size: 12px; text-align: center; width: 10%;'>CPF</th>
            <th style='font-size: 12px; text-align: center; width: 35%;'>NOME</th>
            <th style='font-size: 12px; text-align: center; width: 10%;'>PEM</th>
            <th style='font-size: 12px; text-align: center; width: 10%;'>POM</th>
            <th style='font-size: 12px; text-align: center; width: 10%;'>PPM</th>
            <th style='font-size: 12px; text-align: center; width: 10%;'>OBS</th>
        </tr>
        ";

    $mpdf->WriteHTML($html);

    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] < 2) {
            continue;
        }

        $notaPem = $candidato['prova_teorica_musica'];
        $notaPom = $candidato['prova_oral_musica'];
        $notaPpm = $candidato['prova_pratica_musica'];

        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $obs = '-';

        if (empty($notaPem) || empty($notaPom) || empty($notaPpm)) {
            $obs = 'Eliminado';
        }

        $html = "
            <tr>
            <td style='font-size: 12px; text-align: center;'>$contador</td>
                <td style='font-size: 12px; text-align: center;'>$cpf</td>
                <td style='font-size: 12px; text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
                <td style='font-size: 12px; text-align: center;'>$notaPem</td>
                <td style='font-size: 12px; text-align: center;'>$notaPom</td>
                <td style='font-size: 12px; text-align: center;'>$notaPpm</td>
                <td style='font-size: 12px; text-align: center;'>$obs</td>
            </tr>";
        $contador++;

        $mpdf->WriteHTML($html);
    }

    $html = "</table>";
    $mpdf->WriteHTML($html);
}

$mpdf->Output("Resultado Análise Recursos Etapa I.pdf", 'D');
ob_end_flush();
exit();
