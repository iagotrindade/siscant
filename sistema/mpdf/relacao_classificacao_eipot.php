<?php

ob_start();
session_start();
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';
include("mpdf60/mpdf.php");

$mpdf = new mPDF();
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css, 1);

$tipo_publicacao = $_POST['tipo_publicacao'];

$titulo_resultado_eipot = $_POST['titulo_resultado_eipot'];
$subtitulo_resultado_eipot = $_POST['subtitulo_resultado_eipot'];

$paragrafo_um_resultado_eipot = $_POST['paragrafo_um_resultado_eipot'];
$paragrafo_dois_resultado_eipot = $_POST['paragrafo_dois_resultado_eipot'];

$texto_dia = $_POST['texto_dia'];

set_time_limit(300);

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

// Define o cabeçalho
$_SESSION['cabecalho_relatorio'] = $cabecalhos[$rm_usuario] ?? '';

$html = "
<p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'>
    <img src='../imagens/brasao.png' width='70px'><br>
    " . $_SESSION['cabecalho_relatorio'] . "
</p>
<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='center'><strong>" . $titulo_resultado_eipot . "</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='center'><strong>" . $subtitulo_resultado_eipot . "</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='right'><strong>" . $texto_dia . "</strong></th>
    </tr>
</table>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify; margin: 5px 0;'>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    " . $paragrafo_um_resultado_eipot . "
</p>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify; margin: 5px 0;'>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    " . $paragrafo_dois_resultado_eipot . "
</p>
";

$mpdf->WriteHTML($html);

$lista_candidatos = $conexao->get_inscritos_eipot_tabelas($rm_usuario);

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

foreach ($lista_candidatos as $inscrito) {

    // Ignora candidatos de outras regiões militares
    if ((int) $inscrito['rm_inscricao'] !== $rm_usuario || $inscrito['etapa'] < 6) {
        continue;
    }

    $vagaReservada = (int) ($inscrito['vaga_reservada'] ?? 0);

    // Filtro por tipo de publicação
    if (
        ($tipo_publicacao === 'eipot_cotas_negros' && $vagaReservada === 0)
    ) {
        continue;
    }

    $inscrito['nota_final'] = get_nota_final_eipot($inscrito['id']);
    $arma = $inscrito['arma_especialidade'] ?? 'Não Informada';

    $inscritos_por_arma[$arma][] = $inscrito;
}

// Ordena os grupos de armas conforme $ordem_arma
uksort($inscritos_por_arma, function ($a, $b) use ($ordem_arma) {
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);

    if ($pos_a === false) $pos_a = PHP_INT_MAX;
    if ($pos_b === false) $pos_b = PHP_INT_MAX;

    if ($pos_a === PHP_INT_MAX && $pos_b === PHP_INT_MAX) {
        return strcasecmp($a, $b);
    }

    return $pos_a - $pos_b;
});

// Ordena candidatos por nota e adiciona classificação 01, 02, 03...
foreach ($inscritos_por_arma as &$candidatos) {
    usort($candidatos, function ($a, $b) {
        return $b['nota_final'] <=> $a['nota_final'];
    });

    foreach ($candidatos as $i => &$inscrito) {
        $inscrito['classificacao'] = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
    }
    unset($inscrito);
}
unset($candidatos);

foreach ($inscritos_por_arma as $arma => $candidatos) {
    if ($tipo_publicacao === 'eipot_cotas_negros') {
        $candidatos_com_parecer = [];

        foreach ($candidatos as $candidato) {
            $pareceres = $conexao->get_pareceres_heteroidentificacao($candidato['id']);

            // Contadores fase 1
            $fase1_total = 0;
            $fase1_confirmada = 0;

            // Contadores fase 2
            $fase2_total = 0;
            $fase2_confirmada = 0;
            $fase2_nao_confirmada = 0;
            $fase2_nao_compareceu = 0;

            foreach ($pareceres as $parecer) {
                if ((int)$parecer['fase'] === 1) {
                    $fase1_total++;
                    if ($parecer['parecer'] === 'confirmada') {
                        $fase1_confirmada++;
                    }
                } elseif ((int)$parecer['fase'] === 2) {
                    $fase2_total++;
                    if ($parecer['parecer'] === 'confirmada') {
                        $fase2_confirmada++;
                    } elseif ($parecer['parecer'] === 'nao_confirmada') {
                        $fase2_nao_confirmada++;
                    } elseif ($parecer['parecer'] === 'nao_compareceu') {
                        $fase2_nao_compareceu++;
                    }
                }
            }

            $aprovado = false;

            if ($fase1_total === 5 && $fase1_confirmada >= 3) {
                $aprovado = true;
            } elseif ($fase2_total === 3 && $fase2_confirmada >= 2) {
                $aprovado = true;
            }

            if ($aprovado) {
                $candidatos_com_parecer[] = $candidato;
            }
        }

        // Se nenhum aprovado, pula a arma
        if (count($candidatos_com_parecer) === 0) {
            continue;
        }

        // Redefine a lista de candidatos apenas com os aprovados
        $candidatos = $candidatos_com_parecer;
    }

    $html = "
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>" . mb_strtoupper($arma, "UTF-8") . "</th>
        </tr>
        <tr>
            <th style='font-size: 12px; text-align: center; width: 20%;'>ORD</th>
            <th style='font-size: 12px; text-align: center; width: 20%;'>CPF</th>
            <th style='font-size: 12px; text-align: center; width: 35%;'>NOME</th>
            <th style='font-size: 12px; text-align: center; width: 25%;'>RESULTADO</th>
        </tr>
    ";

    $total = count($candidatos);

    foreach ($candidatos as $index => $candidato) {
        if ($tipo_publicacao === 'eipot_cotas_negros') {
            $pareceres = $conexao->get_pareceres_heteroidentificacao($candidato['id']);

            $fase1_confirmada = 0;
            $fase1_total = 0;
            $fase2_confirmada = 0;
            $fase2_nao_confirmada = 0;
            $fase2_nao_compareceu = 0;
            $fase2_total = 0;

            foreach ($pareceres as $parecer) {
                if ((int)$parecer['fase'] === 1) {
                    $fase1_total++;
                    if ($parecer['parecer'] === 'confirmada') {
                        $fase1_confirmada++;
                    }
                } elseif ((int)$parecer['fase'] === 2) {
                    if ($parecer['parecer'] === 'confirmada') {
                        $fase2_confirmada++;
                    } elseif ($parecer['parecer'] === 'nao_confirmada') {
                        $fase2_nao_confirmada++;
                    } elseif ($parecer['parecer'] === 'nao_compareceu') {
                        $fase2_nao_compareceu++;
                    }
                    $fase2_total++;
                }
            }

            $resultado = 'PENDENTE';

            if ($fase1_total === 5 && $fase1_confirmada >= 3) {
                $resultado = 'CONFIRMADA';
            } elseif ($fase2_total === 3) {
                if ($fase2_confirmada >= 2) {
                    $resultado = 'CONFIRMADA';
                } elseif ($fase2_nao_confirmada >= 2 || $fase2_nao_compareceu >= 2) {
                    continue; // Elimina do relatório
                }
            } else {
                continue; // Ignora se não atendeu nenhuma das fases
            }
        }

        $cpf = substr($candidato['cpf'], 0, -5) . '*****';

        $html .= "
        <tr>
            <td style='text-align: center;'>{$candidato['classificacao']}</td>
            <td style='text-align: center;'>{$cpf}</td>
            <td style='text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='text-align: center;'>" . get_nota_final_eipot($candidato['id']) . "</td>
        </tr>";
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}
$mpdf->Output("Ranking EIPOT.pdf", 'D');
ob_end_flush();
exit();
