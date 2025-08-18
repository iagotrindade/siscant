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

$hora_especialidade = $_POST['hora_especialidade'];

$data = $_POST['data'];

$etapa = $_POST['etapa'];

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

if($etapa < 3)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 3252353 O SISCANT deve estar pelo menos na etapa III!");
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

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    {$cabecalhos[''.$rm_usuario.'']}
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
$todos_candidatos = []; // [id_candidato => ['dados' => [], 'especialidades' => []]]
$hora_candidato = [];

// 1. Carrega todos os candidatos por especialidade
foreach ($lista_especialidades as $especialidade) {
    $id_esp = $especialidade['id'];
    $candidatos = $conexao->get_candidatos_especialidade($id_esp);
    $especialidades[$id_esp] = [
        'nome_especialidade' => $especialidade['ott_stt'] . ' - ' . $especialidade['nome'],
        'candidatos' => []
    ];

    foreach ($candidatos as $candidato) {
        $id = $candidato['id'];
        $especialidades[$id_esp]['candidatos'][$id] = $candidato;

        // Armazena especialidades do candidato
        if (!isset($todos_candidatos[$id])) {
            $todos_candidatos[$id] = [
                'dados' => $candidato,
                'especialidades' => []
            ];
        }

        $todos_candidatos[$id]['especialidades'][] = $id_esp;
    }
}

// 2. Determina menor hora para candidatos com múltiplas especialidades
foreach ($todos_candidatos as $id => $info) {
    if (count($info['especialidades']) < 2) continue;

    $hora_menor_valor = PHP_INT_MAX;
    $hora_menor_texto = '';

    foreach ($info['especialidades'] as $esp_id) {
        if (isset($hora_especialidade[$esp_id])) {
            $hora_txt = $hora_especialidade[$esp_id];
            $hora_valor = extrair_hora_numerica($hora_txt);

            if ($hora_valor < $hora_menor_valor) {
                $hora_menor_valor = $hora_valor;
                $hora_menor_texto = $hora_txt;
            }
        }
    }

    if ($hora_menor_texto !== '') {
        $hora_candidato[$id] = $hora_menor_texto;
    }
}

// 3. Gera as tabelas

foreach ($especialidades as $esp_id => $dados) {
    $nome = $dados['nome_especialidade'];
    $candidatos = $dados['candidatos'];

    // Separa os candidatos
    $candidatos_multipla = [];
    $candidatos_exclusivos = [];

    foreach ($candidatos as $id => $candidato) {
        if ($candidato['etapa'] < 3) continue;

        if (isset($todos_candidatos[$id]) && count($todos_candidatos[$id]['especialidades']) > 1) {
            $candidatos_multipla[$id] = $candidato;
        } else {
            $candidatos_exclusivos[$id] = $candidato;
        }
    }

    // 3.1. Tabela com os candidatos exclusivos
    if (count($candidatos_exclusivos) > 0) {
        $hora = isset($hora_especialidade[$esp_id]) ? $hora_especialidade[$esp_id] : '';

        $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>"
            . mb_strtoupper($nome, 'UTF-8') . "<br>"
            . htmlspecialchars($hora, ENT_QUOTES, 'UTF-8') . "</th>
            </tr>
            <tr>
                <th style='text-align: center; width: 10%;'>Nº</th>
                <th style='text-align: center; width: 30%;'>CPF</th>
                <th style='text-align: center; width: 60%;'>NOME</th>
            </tr>";

        $contador = 1;
        foreach ($candidatos_exclusivos as $candidato) {
            $cpf = substr($candidato['cpf'], 0, -5) . "*****";
            $html .= "
            <tr>
                <td style='text-align: center;'>$contador</td>
                <td style='text-align: center;'>$cpf</td>
                <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
            </tr>";
            $contador++;
        }

        $html .= "</table>";
        $mpdf->WriteHTML($html);
    }

    // 3.2. Tabela separada para cada candidato com múltiplas especialidades
    foreach ($candidatos_multipla as $candidato) {
        $id = $candidato['id'];
        $hora = isset($hora_candidato[$id]) ? $hora_candidato[$id] : '';

        $cpf = substr($candidato['cpf'], 0, -5) . "*****";

        $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>"
            . mb_strtoupper($nome, 'UTF-8') . "<br>"
            . htmlspecialchars($hora, ENT_QUOTES, 'UTF-8') . "</th>
            </tr>
            <tr>
                <th style='text-align: center; width: 10%;'>Nº</th>
                <th style='text-align: center; width: 30%;'>CPF</th>
                <th style='text-align: center; width: 60%;'>NOME</th>
            </tr>
            <tr>
                <td style='text-align: center;'>1</td>
                <td style='text-align: center;'>$cpf</td>
                <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
            </tr>
        </table>";

        $mpdf->WriteHTML($html);
    }
}

// ===========================
// SEGUNDA PARTE - LISTA DE PRESENÇA
// ===========================
$mpdf->AddPage();

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

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    {$_SESSION['cabecalho_relatorio']}
</p>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='center'><strong>PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20__/20__</strong></th></tr>
</table>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='center'><strong>LISTA DE PRESENÇA ETAPA III – CONFERÊNCIA PRESENCIAL DE DOCUMENTAÇÃO, ENTREVISTA E INSPEÇÃO DE SAÚDE</strong></th></tr>
</table>";

$mpdf->WriteHTML($html);

// Repete a mesma lógica das tabelas anteriores, mas com coluna "ASSINATURA"
foreach ($especialidades as $esp_id => $dados) {
    $nome = $dados['nome_especialidade'];
    $candidatos = $dados['candidatos'];

    $candidatos_multipla = [];
    $candidatos_exclusivos = [];

    foreach ($candidatos as $id => $candidato) {
        if ($candidato['etapa'] < 3) continue;

        if (isset($todos_candidatos[$id]) && count($todos_candidatos[$id]['especialidades']) > 1) {
            $candidatos_multipla[$id] = $candidato;
        } else {
            $candidatos_exclusivos[$id] = $candidato;
        }
    }

    // Exclusivos
    if (count($candidatos_exclusivos) > 0) {
        $hora = isset($hora_especialidade[$esp_id]) ? $hora_especialidade[$esp_id] : '';

        $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>"
            . mb_strtoupper($nome, 'UTF-8') . "<br>"
            . htmlspecialchars($hora, ENT_QUOTES, 'UTF-8') . "</th>
            </tr>
            <tr>
                <th style='text-align: center; width: 10%;'>Nº</th>
                <th style='text-align: center; width: 25%;'>CPF</th>
                <th style='text-align: center; width: 45%;'>NOME</th>
                <th style='text-align: center; width: 20%;'>ASSINATURA</th>
            </tr>";

        $contador = 1;
        foreach ($candidatos_exclusivos as $candidato) {
            $cpf = substr($candidato['cpf'], 0, -5) . "*****";
            $html .= "
            <tr>
                <td style='text-align: center;'>$contador</td>
                <td style='text-align: center;'>$cpf</td>
                <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
                <td></td>
            </tr>";
            $contador++;
        }

        $html .= "</table>";
        $mpdf->WriteHTML($html);
    }

    // Múltiplas especialidades (tabelas individuais)
    foreach ($candidatos_multipla as $candidato) {
        $id = $candidato['id'];
        $hora = isset($hora_candidato[$id]) ? $hora_candidato[$id] : '';
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";

        $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>"
            . mb_strtoupper($nome, 'UTF-8') . "<br>"
            . htmlspecialchars($hora, ENT_QUOTES, 'UTF-8') . "</th>
            </tr>
            <tr>
                <th style='text-align: center; width: 10%;'>Nº</th>
                <th style='text-align: center; width: 25%;'>CPF</th>
                <th style='text-align: center; width: 45%;'>NOME</th>
                <th style='text-align: center; width: 20%;'>ASSINATURA</th>
            </tr>
            <tr>
                <td style='text-align: center;'>1</td>
                <td style='text-align: center;'>$cpf</td>
                <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td>
                <td></td>
            </tr>
        </table>";

        $mpdf->WriteHTML($html);
    }
}

$mpdf->Output("Cronograma de Convocação Etapa III.pdf", 'D');
ob_end_flush();
exit();
