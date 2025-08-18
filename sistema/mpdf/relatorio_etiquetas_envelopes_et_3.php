<?php
ob_start();
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF(
    '',    // mode - default ''
    '',    // format - A4, for example, default ''
    0,     // font size - default 0
    '',    // default font family
    5,    // margin_left
    5,    // margin right
    16,    // margin top
    16,    // margin bottom
    5,     // margin header
    5,     // margin footer
    'L'
);  // L - landscape, P - portrait 


$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$titulo = $_POST['titulo'];
$subtitulo = $_POST['subtitulo'];

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

if ($etapa < 3) {
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
</table>";

$mpdf->WriteHTML($html);

$lista_especialidades = $conexao->get_especialidade();

$especialidades = [];
$todos_candidatos = []; // [id_candidato => ['dados' => [], 'especialidades' => []]]
$hora_candidato = [];

$html = '';

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

$html = "<table style='border-collapse: separate; border-spacing: 2mm; width: 100%;'>";

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

    // Função para gerar etiqueta
    $gerarEtiqueta = function ($nome, $candidato, $cpf) {
        return "
        <td style='width:65mm; height:15mm; border:1px solid #000; text-align:center; vertical-align:middle; background-color:#fff;'>
            <div style='font-weight: bold; font-size: 11px;'>" . mb_strtoupper($nome, 'UTF-8') . "</div>
            <div style='font-size: 11px; font-weight: bold;'>" . strtoupper($candidato['nome_completo']) . "</div>
            <div style='font-size: 11px; font-weight: bold;'>" . mascara($cpf, '###.###.###-##') . "</div>
        </td>";
    };

    // 3.1. Tabela com os candidatos exclusivos
    foreach ($candidatos_exclusivos as $candidato) {
        if (!isset($contador)) $contador = 1;
        if ($contador % 3 == 1) { // nova linha
            $html .= "<tr>";
        }

        $html .= $gerarEtiqueta($nome, $candidato, $candidato['cpf']);

        if ($contador % 3 == 0) { // fecha linha
            $html .= "</tr>";
        }
        $contador++;
    }

    // 3.2. Candidatos com múltiplas especialidades
    foreach ($candidatos_multipla as $candidato) {
        if (!isset($contador)) $contador = 1;
        if ($contador % 3 == 1) {
            $html .= "<tr>";
        }

        $html .= $gerarEtiqueta($nome, $candidato, $candidato['cpf']);

        if ($contador % 3 == 0) {
            $html .= "</tr>";
        }
        $contador++;
    }
}

// Caso a última linha não tenha fechado
if (isset($contador) && $contador % 3 != 1) {
    // completa com células vazias
    while ($contador % 3 != 1) {
        $html .= "<td></td>";
        $contador++;
    }
    $html .= "</tr>";
}

$html .= "</table>";

$mpdf->WriteHTML($html);

$mpdf->Output("Etiquetas Etapa III.pdf", 'D');
ob_end_flush();
exit();
