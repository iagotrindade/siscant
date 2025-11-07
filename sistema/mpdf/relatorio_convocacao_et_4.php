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

$grupo_um_texto = $_POST['grupo_um_texto'];
$grupo_dois_texto = $_POST['grupo_dois_texto'];

$id_especialidade_um = $_POST['grupo_um_especialidades'];
$id_especialidade_dois = $_POST['grupo_dois_especialidades'];

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

// GRUPO UM
if (is_array($id_especialidade_um) && !empty($id_especialidade_um)) {
    $lista_especialidades_um = [];

    foreach ($id_especialidade_um as $id) {
        $result = $conexao->get_especialidade_selecionadas($id);
        $lista_especialidades_um = array_merge($lista_especialidades_um, $result);
    }

    $candidatos_grupo_um = [];

    foreach ($lista_especialidades_um as $especialidade) {
        $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);

        // Adiciona cada candidato, evitando duplicados
        foreach ($candidatos as $cand) {
            if ($cand['etapa_candidato'] == 4) {
                $candidatos_grupo_um[$cand['id']] = $cand;
            }
        }
    }

    // Reindexa o array (se quiser que as chaves fiquem 0, 1, 2, 3...)
    $candidatos_grupo_um = array_values($candidatos_grupo_um);
}

// GRUPO DOIS
$id_especialidade_dois = $_POST['grupo_dois_especialidades']; // ← Corrigido o nome do POST se for o caso
if (is_array($id_especialidade_dois) && !empty($id_especialidade_dois)) {
    $lista_especialidades_dois = [];

    foreach ($id_especialidade_dois as $id) {
        $result = $conexao->get_especialidade_selecionadas($id);
        $lista_especialidades_dois = array_merge($lista_especialidades_dois, $result);
    }

    $candidatos_grupo_dois = [];

    foreach ($lista_especialidades_dois as $especialidade) {
        $candidatos = $conexao->get_candidatos_especialidade($especialidade['id']);

        foreach ($candidatos as $cand) {
            // Evita duplicar candidatos que aparecem em mais de uma especialidade
            if ($cand['etapa_candidato'] == 4) {
                $candidatos_grupo_dois[$cand['id']] = $cand;
            }
        }
    }

    // Reindexa o array se desejar um índice sequencial (0, 1, 2, ...)
    $candidatos_grupo_dois = array_values($candidatos_grupo_dois);
}

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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_cinco</p>";

$mpdf->WriteHTML($html);

// =============== GRUPO UM ===============
$contadorParagrafo = 1;

if (!empty($candidatos_grupo_um)) {
    $html = "";



    $html .= "
            <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>
                {$contadorParagrafo}. {$grupo_um_texto}
            </p>

            <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
                <tr style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                    <th style='text-align: center; width: 10%;'>Nº</th>
                    <th style='text-align: center; width: 20%;'>CPF</th>
                    <th style='text-align: center; width: 50%;'>NOME</th>
                </tr>
        ";

    $contador = 1;
    foreach ($candidatos_grupo_um as $candidato) {
        // Máscara do CPF
        $cpf = preg_replace('/\D/', '', $candidato['cpf']);
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

    $html .= "</table>";
    $mpdf->WriteHTML($html);
    $contadorParagrafo++;
}

// =============== GRUPO DOIS ===============
if (!empty($candidatos_grupo_dois)) {
    $html = "";

    $html .= "
            <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>
                {$contadorParagrafo}. {$grupo_dois_texto}
            </p>

            <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
                <tr style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                    <th style='text-align: center; width: 10%;'>Nº</th>
                    <th style='text-align: center; width: 20%;'>CPF</th>
                    <th style='text-align: center; width: 50%;'>NOME</th>
                </tr>
        ";

    $contador = 1;
    foreach ($candidatos_grupo_dois as $candidato) {
        // Máscara do CPF
        $cpf = preg_replace('/\D/', '', $candidato['cpf']);
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

    $html .= "</table>";
    $mpdf->WriteHTML($html);
    $contadorParagrafo++;
}

$mpdf->Output("Convocação Etapa IV - Teste de Aptidão Física (EAF).pdf", 'D');
ob_end_flush();
exit();
