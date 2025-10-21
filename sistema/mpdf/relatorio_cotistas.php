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
$texto_dia = $_POST['texto_dia'];

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

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um</p>";

$mpdf->WriteHTML($html);

$lista_especialidades = $conexao->get_especialidade();

$especialidades = [];

foreach ($lista_especialidades as $especialidade) {
    $id_esp = $especialidade['id'];
    $candidatos = $conexao->get_candidatos_especialidade($id_esp);
    $especialidades[$id_esp] = [
        'nome_especialidade' => $especialidade['ott_stt'] . ' - ' . $especialidade['nome'],
        'candidatos' => []
    ];

    foreach ($candidatos as $candidato) {
        if (!$candidato['vaga_reservada']) {
            continue;
        }

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

foreach ($especialidades as $esp_id => $dados) {
    $candidatos = $dados['candidatos'];
    $nome = $dados['nome_especialidade'];

    if (count($candidatos) === 0) {
        continue; // Não cria tabela se não houver candidatos
    }

    $html = "
   <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr>
        <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>" . mb_strtoupper($nome, "UTF-8") . "</th>
    </tr>
    <tr>
        <th style='text-align: center; width: 10%;'>Nº</th>
        <th style='text-align: center; width: 20%;'>CPF</th>
        <th style='text-align: center; width: 50%;'>NOME</th>
        <th style='text-align: center; width: 20%;'>AUTODECLARAÇÃO</th>
    </tr>
    ";

    $contador = 1;

    // Ordenando os arrays de candidatos e suas notas simultaneamente (decrescente)
    usort($dados, function ($a, $b) {
        return strcmp(mb_strtoupper($a['nome_completo']), strtoupper($b['nome_completo']));
    });

    // Gerando o HTML para a tabela após a ordenação
    foreach ($candidatos as $index => $candidato) {
        // O CPF já foi mascarado no primeiro loop
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";
        $html .= "
        <tr>
            <td style='text-align: center;'>$contador</td>
            <td style='text-align: center;'>$cpf</td>
            <td style='text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align: center;'>" . mb_strtoupper($candidato['autodeclaracao']) . "</td>
        </tr>";
        $contador++;
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}

$mpdf->Output("inscritos.pdf", 'D');
ob_end_flush();
exit();
