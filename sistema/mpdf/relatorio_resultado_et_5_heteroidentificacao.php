<?php
ob_start();

include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include_once 'mpdf60/mpdf.php';

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');

// Estilos CSS
$css = file_get_contents('css/estilo.css');
$mpdf->WriteHTML($css, 1);

// Sanitização básica das entradas
$titulo         = trim($_POST['titulo'] ?? '');
$subtitulo      = trim($_POST['subtitulo'] ?? '');
$data           = trim($_POST['data'] ?? '');
$paragrafo_um   = trim($_POST['paragrafo_um'] ?? '');
$paragrafo_dois = trim($_POST['paragrafo_dois'] ?? '');
$paragrafo_tres = trim($_POST['paragrafo_tres'] ?? '');
$id_especialidades = $_POST['especialidades'] ?? [];
$fase = $_POST['fase'];

session_start();

// Validação de sessão
if (!isset($_SESSION['perfil'])) {
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}
if ($_SESSION['perfil'] !== 'admin') {
    erro_relatorio("Erro 824! Somente o administrador pode gerar este relatório");
    exit();
}
if (!empty($_SESSION['candidato']) && $_SESSION['candidato'] == '1') {
    erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
    exit();
}

// Instância da conexão
$conexao = new Conexao();

// Recupera especialidades
if (is_array($id_especialidades) && !empty($id_especialidades)) {
    $lista_especialidades = [];
    foreach ($id_especialidades as $id) {
        $result = $conexao->get_especialidade_selecionadas($id);
        if (is_array($result)) {
            $lista_especialidades = array_merge($lista_especialidades, $result);
        }
    }
} else {
    $lista_especialidades = $conexao->get_especialidade();
}

// Candidatos únicos filtrados
$candidatos = [];
foreach ($lista_especialidades as $especialidade) {
    $cands = $conexao->get_candidatos_especialidade($especialidade['id']);
    foreach ($cands as $cand) {
        $etapa = $_SESSION['selecao_codigo'] == 'mfdv' ? 4 : 5;

        if (!empty($cand['etapa_candidato']) && $cand['etapa_candidato'] == $etapa && $cand['vaga_reservada'] == 1) {
            $candidatos[$cand['id']] = $cand; // evita duplicados
        }
    }
}
$candidatos = array_values($candidatos);

// Cabeçalhos por RM
$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario);

$cabecalhos = [
    '3'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DO SUL<br>COMANDO DA 3ª REGIÃO MILITAR<br>(Gov das Armas Prov do RS/1821)<br>REGIÃO DOM DIOGO DE SOUZA<br>",
    '8'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 8ª REGIÃO MILITAR<br>(Gov das Armas Prov do PA/1821)<br>REGIÃO FORTE DO PRESÉPIO<br>",
    '6'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 6ª REGIÃO MILITAR<br>(Governo das Armas Província da Bahia/1821)<br>REGIÃO MARECHAL CANTUÁRIA<br>",
    '12' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO MILITAR DA 12ª REGIÃO MILITAR<br>(Comando de Elementos de Fronteira/1948)<br>FORTE MENDONÇA FURTADO<br>",
    '7'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 7ª REGIÃO MILITAR<br>(Gov das Armas Prov PE/1821)<br>REGIÃO MATIAS DE ALBUQUERQUE<br>",
    '5'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 5ª REGIÃO MILITAR<br>(Comando das Armas do Estado do Paraná/1990)<br>REGIÃO HERÓIS DA LAPA<br>",
    '11' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 11ª REGIÃO MILITAR<br>(Cmdo Mil Bsb/1960)<br>REGIÃO TENENTE-CORONEL LUIZ CRULS<br>",
    '2'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 2ª REGIÃO MILITAR<br>(Cmdo das Armas Prov Pr/1890)<br>REGIÃO DAS BANDEIRAS<br>",
    '4'  => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 4ª REGIÃO MILITAR<br>(4º Distrito Militar/1891)<br>REGIÃO DAS MINAS DO OURO<br>",
];

// Evita erro caso o RM não exista
$cabecalho = $cabecalhos[$rm_usuario] ?? 'MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>';

// Montagem do HTML inicial
$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br> {$cabecalho}
</p>
<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='center'><strong>{$titulo}</strong></th></tr>
</table>
<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='center'><strong>{$subtitulo}</strong></th></tr>
</table>
<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='right'><strong>{$data}</strong></th></tr>
</table>

<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>{$paragrafo_um}</p>
<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>{$paragrafo_dois}</p>
<p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>{$paragrafo_tres}</p>
";

$mpdf->WriteHTML($html);

// Tabela de candidatos
if (!empty($candidatos)) {
    $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>
                <th style='width: 5%;'>Nº</th>
                <th style='width: 15%;'>CPF</th>
                <th style='width: 55%;'>NOME</th>
                <th style='width: 25%;'>RESULTADO</th>
            </tr>
    ";

    $contador = 1;
    foreach ($candidatos as $candidato) {
        $cpf = preg_replace('/\D/', '', $candidato['cpf'] ?? '');
        $cpf_mascarado = substr($cpf, 0, 6) . str_repeat('*', 5);

        $pareceres = $conexao->get_pareceres_heteroidentificacao($candidato['id']);

        $quantidadePareceresConfirmados = 0;
        $quantidadePareceresNaoConfirmados = 0;
        $quantidadeParecerNaoCompareceu = 0;
        $totalPareceres = 0;

        foreach ($pareceres as $parecer) {
            if ($parecer['fase'] != $fase) {
                continue;
            }

            if ($parecer['parecer'] === 'confirmada') {
                $quantidadePareceresConfirmados++;
            } elseif ($parecer['parecer'] === 'nao_confirmada') {
                $quantidadePareceresNaoConfirmados++;
            } elseif ($parecer['parecer'] === 'nao_compareceu') {
                $quantidadeParecerNaoCompareceu++;
            }
        }

        $totalPareceres = $quantidadePareceresConfirmados + $quantidadePareceresNaoConfirmados + $quantidadeParecerNaoCompareceu;

        // Evita entrar no foreach se estiver na fase 2 e sem pareceres
        if ($fase == 2 && $totalPareceres == 0) {
            continue;
        }

        // Lógica de aprovação por fase
        $minConfirmados = $fase == 1 ? 3 : 2;
        $minTotal = $fase == 1 ? 5 : 3;

        if ($totalPareceres === $minTotal) {
            if ($quantidadePareceresConfirmados >= $minConfirmados) {
                $resultado = 'CONFIRMADA';
            } elseif ($quantidadePareceresNaoConfirmados >= $minConfirmados) {
                $resultado = 'NÃO CONFIRMADA';
            } elseif ($quantidadeParecerNaoCompareceu >= $minConfirmados) {
                $resultado = 'NÃO COMPARECEU';
            }
        } else {
            $resultado = 'PENDENTE';
        }

        $nome = mb_strtoupper($candidato['nome_completo'] ?? '');

        $html .= "
            <tr>
                <td style='text-align: center;'>{$contador}</td>
                <td style='text-align: center;'>{$cpf_mascarado}</td>
                <td style='text-align: center;'>{$nome}</td>
                <td style='text-align: center;'>{$resultado}</td>
            </tr>
        ";
        $contador++;
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}

// Saída do PDF
$mpdf->Output("Resultado Etapa V - Heteroidentificação.pdf", 'D');
ob_end_flush();
exit;
