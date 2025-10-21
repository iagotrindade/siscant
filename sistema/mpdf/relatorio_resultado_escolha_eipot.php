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

$titulo_resultado_escolha = $_POST['titulo_resultado_escolha'];
$subtitulo_resultado_escolha = $_POST['subtitulo_resultado_escolha'];

$paragrafo_um_resultado_escolha = $_POST['paragrafo_um_resultado_escolha'];

$paragrafo_um_resultado_dois_escolha = $_POST['paragrafo_um_resultado_dois_escolha'];

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
    '1' => "MINISTÉRIO DA DEFESA<br>EXÉRCITO BRASILEIRO<br>COMANDO DA 1ª REGIÃO MILITAR<br>(4º Dist Mil/1891)<br>REGIÃO MARECHAL HERMES DA FONSECA",
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
        <th align='center'><strong>" . $titulo_resultado_escolha . "</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='center'><strong>" . $subtitulo_resultado_escolha . "</strong></th>
    </tr>
</table>

<table border='0' style='width:100%; margin-top: 5px; margin-bottom: 5px;'>
    <tr>
        <th align='right'><strong>" . $texto_dia . "</strong></th>
    </tr>
</table>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify; margin: 5px 0;'>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    " . $paragrafo_um_resultado_escolha . "
</p>

<table border='0' style='width:100%; margin-top: 5px;'>
    <tr><th align='left'><strong>1. RESULTADO DA ESCOLHA DE GUARNIÇÕES</strong></th></tr>
</table>";

$mpdf->WriteHTML($html);

$lista_candidatos = $conexao->get_candidatos_eipot_reserva();

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
    // Ignora candidatos de outras regiões militares que não escolheu rm ou que a rm escolhida não seja a do usuário logado
    if ((int) $inscrito['rm_escolheu_servir'] !== $rm_usuario || (int) empty($inscrito['rm_escolheu_servir'])) {
        continue;
    }

    $vagaReservada = (int) ($inscrito['vaga_reservada'] ?? 0);

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

// Ordena candidatos por ordem_escolha_guarnicao e adiciona classificação 01, 02, 03...
foreach ($inscritos_por_arma as &$candidatos) {
    usort($candidatos, function ($a, $b) {
        return $a['ordem_escolha_guarnicao'] <=> $b['ordem_escolha_guarnicao'];
    });

    foreach ($candidatos as $i => &$inscrito) {
        $inscrito['classificacao'] = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
    }
    unset($inscrito);
}
unset($candidatos);

foreach ($inscritos_por_arma as $arma => $candidatos) {
    $html = "
    <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
        <tr>
            <th colspan='5' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>" . mb_strtoupper($arma, "UTF-8") . "</th>
        </tr>
        <tr>
            <th style='font-size: 12px; text-align: center; width: 10%;'>NOTA</th>
            <th style='font-size: 12px; text-align: center; width: 15%;'>CPF</th>
            <th style='font-size: 12px; text-align: center; width: 35%;'>NOME</th>
            <th style='font-size: 12px; text-align: center; width: 20%;'>CRITÉRIO DE CLASSIFICAÇÃO</th>
            <th style='font-size: 12px; text-align: center; width: 20%;'>OBSERVAÇÃO</th>
        </tr>
    ";

    $total = count($candidatos);
    $obs_tabela = '-';

    foreach ($candidatos as $index => $candidato) {
        $especialidade_candidato = $conexao->get_especialidade_candidato($candidato['id']);
        $lista_cidades_epecialidades = $conexao->get_cidades_especialidade($especialidade_candidato[0]['id_especialidade']);
        $vagas_por_regiao = separa_vaga_rm($lista_cidades_epecialidades);

        if ($candidato['vaga_reservada'] == 1) {
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

            if ($fase1_total === 5 && $fase1_confirmada >= 3) {
                $candidato['vaga_reservada'] = 1; // Vaga reservada confirmada
            } elseif ($fase2_total === 3) {
                if ($fase2_confirmada >= 2) {
                    $candidato['vaga_reservada'] = 1; // Vaga reservada confirmada
                } elseif ($fase2_nao_confirmada >= 2 || $fase2_nao_compareceu >= 2) {
                    $candidato['vaga_reservada'] = 0; // Vaga reservada não confirmada
                }
            } else {
                $candidato['vaga_reservada'] = 0; // Define como Ampla se não atendeu nenhuma das fases
            }
        }

        $cpf = substr($candidato['cpf'], 0, -5) . '*****';

        $ordem = (int)$candidato['ordem_escolha_guarnicao'];


        $totalVagasPorRegiao = [];

        foreach ($vagas_por_regiao as $regiao => $cidades) {
            $totalV = 0;
            foreach ($cidades as $cidade) {
                $totalV += (int)$cidade['vagas'];
            }
            $totalVagasPorRegiao[$regiao] = $totalV;
        }

        $totalVagas = $totalVagasPorRegiao[$candidato['rm_escolheu_servir']];

        if ($candidato['vaga_reservada'] == 1) {
            if ($totalVagas <= 2) {
                $candidato['criterio_classificacao'] = "AMPLA CONCORRÊNCIA";
            } elseif ($totalVagas == 3 && $ordem == 3) {
                $candidato['criterio_classificacao'] = "COTAS";
            } elseif ($totalVagas == 4 && $ordem == 4) {
                $candidato['criterio_classificacao'] = "COTAS";
            } elseif ($totalVagas >= 5 && $ordem % 5 == 0) {
                $candidato['criterio_classificacao'] = "COTAS";
            } else {
                $candidato['criterio_classificacao'] = "AMPLA CONCORRÊNCIA";
            }
        } else {
            $candidato['criterio_classificacao'] = "AMPLA CONCORRÊNCIA";
        }

        $obs_tabela = false;

        $vagas_de_cota = [];

        if ($totalVagas == 3) {
            $vagas_de_cota[] = 3;
        } elseif ($totalVagas == 4) {
            $vagas_de_cota[] = 4;
        } elseif ($totalVagas >= 5) {
            for ($i = 5; $i <= $totalVagas; $i += 5) {
                $vagas_de_cota[] = $i;
            }
        }

        $obs_tabela = '-';

        foreach ($vagas_de_cota as $posicao_cota) {
            $encontrado = false;

            foreach ($candidatos as $cand_check) {
                if ((int)$cand_check['ordem_escolha_guarnicao'] === $posicao_cota) {
                    $encontrado = true;

                    if ((int)$cand_check['vaga_reservada'] == 0) {
                        $obs_tabela = 'SEM CANDIDATOS COTISTAS PARA OCUPAR VAGAS DE COTAS';
                        break 2;
                    }

                    break; // achou e é cotista, segue para próxima posição de cota
                }
            }

            if (!$encontrado) {
                // Nenhum candidato ocupou essa posição de cota
                $obs_tabela = 'SEM CANDIDATOS COTISTAS PARA OCUPAR VAGAS DE COTAS';
                break;
            }
        }
        $total = (int)count($candidatos);

        if ($total <= 2) {
            $obs_tabela = '-';
        }

        $html .= "<tr>
        <td style='text-align: center; font-size:12px;'>{$candidato['nota_final']}</td>
        <td style='text-align: center; font-size:12px;'>{$cpf}</td>
        <td style='text-align: center; font-size:12px;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
        <td style='text-align: center; font-size:12px;'>" . $candidato['criterio_classificacao'] . "</td>";

        // Apenas na primeira linha, adiciona a coluna OBS com rowspan
        if ($index === 0) {
            $html .= "<td rowspan='{$total}' style='text-align: center; font-size:12px;'>{$obs_tabela}</td>";
        }

        $html .= "</tr>";
    }

    $html .= "</table>";
    $mpdf->WriteHTML($html);
}

$inscritos_por_arma = [];

foreach ($lista_candidatos as $inscrito) {
    if ($inscrito['rm_inscricao'] == $rm_usuario) { // Filtra por rm_inscricao
        $arma = $inscrito['arma_especialidade'];
        $inscritos_por_arma[$arma][] = $inscrito;
    }
}

uksort($inscritos_por_arma, function ($a, $b) use ($ordem_arma) {
    // Verificando se a especialidade $a e $b estão na ordem específica
    $pos_a = array_search($a, $ordem_arma);
    $pos_b = array_search($b, $ordem_arma);

    // Se não encontrar a especialidade, coloca no final e ordena alfabeticamente
    if ($pos_a === false) $pos_a = PHP_INT_MAX;
    if ($pos_b === false) $pos_b = PHP_INT_MAX;

    // Se ambos os valores estão fora da ordem (não definidos em $ordem_arma), ordena alfabeticamente
    if ($pos_a === PHP_INT_MAX && $pos_b === PHP_INT_MAX) {
        return strcasecmp($a, $b);
    }

    return $pos_a - $pos_b;
});

// Verifica se existe pelo menos um cotista para exibir o parágrafo introdutório
$mostrar_paragrafo = false;

foreach ($inscritos_por_arma as $candidatos) {
    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] == 6 && $candidato['vaga_reservada']) {
            $mostrar_paragrafo = true;
            break 2; // sai dos dois loops
        }
    }
}

if ($mostrar_paragrafo) {
    $html = "
    <table border='0' style='width:100%; margin-top: 5px;'>
        <tr><th align='left'><strong>2. CANDIDATOS COTISTAS</strong></th></tr>
    </table>
    <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um_resultado_dois_escolha</p>";

    $mpdf->WriteHTML($html);
}

// Processa por arma
foreach ($inscritos_por_arma as $arma => $candidatos) {
    $tem_cotista_na_arma = false;
    $html_linhas = '';

    foreach ($candidatos as $candidato) {
        // Atualiza status da vaga reservada com base nos pareceres
        if ($candidato['vaga_reservada'] == 1) {
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

            if ($fase1_total === 5 && $fase1_confirmada >= 3) {
                $candidato['vaga_reservada'] = 1;
            } elseif ($fase2_total === 3) {
                if ($fase2_confirmada >= 2) {
                    $candidato['vaga_reservada'] = 1;
                } elseif ($fase2_nao_confirmada >= 2 || $fase2_nao_compareceu >= 2) {
                    $candidato['vaga_reservada'] = 0;
                }
            } else {
                $candidato['vaga_reservada'] = 0;
            }
        }

        // Filtra os cotistas válidos
        if ($candidato['etapa'] < 6 || !$candidato['vaga_reservada']) {
            continue;
        }

        $tem_cotista_na_arma = true;

        $candidato['nota_final'] = get_nota_final_eipot($candidato['id']);
        $cpf = substr($candidato['cpf'], 0, -5) . "*****";

        $obsCotista = 'NÃO CLASSIFICADO DENTRE AS VAGAS DISPONÍVEIS (AMPLA CONCORRÊNCIA E COTA)';
        if ($candidato['concorrendo'] == 0) {
            $obsCotista = 'CANDIDATO NÃO OPTOU PELAS VAGAS OFERTADAS';
        } elseif (!empty($candidato['cidade_escolheu_servir'])) {
            $obsCotista = 'CLASSIFICAÇÃO DO CANDIDATO CONTEMPLADA NA ' . $candidato['rm_escolheu_servir'] . 'ª RM';
        }

        $html_linhas .= "
        <tr>
            <td style='text-align: center; font-size:12px;'>{$candidato['nota_final']}</td>
            <td style='font-size: 12px; text-align: center;'>$cpf</td>
            <td style='font-size: 12px; text-align: center;'>" . mb_strtoupper($candidato['nome_completo']) . "</td>
            <td style='font-size: 12px; text-align: center;'>$obsCotista</td>
        </tr>";
    }

    // Monta a tabela somente se houver cotistas válidos
    if ($tem_cotista_na_arma) {
        $html = "
        <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
            <tr>
                <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 12px;'>"
            . mb_strtoupper($arma, 'UTF-8') . "
                </th>
            </tr>
            <tr>
                <th style='font-size: 12px; text-align: center; width: 10%;'>NOTA</th>
                <th style='font-size: 12px; text-align: center; width: 15%;'>CPF</th>
                <th style='font-size: 12px; text-align: center; width: 35%;'>NOME</th>
                <th style='font-size: 12px; text-align: center; width: 40%;'>OBSERVAÇÃO</th>
            </tr>
            $html_linhas
        </table>";

        $mpdf->WriteHTML($html);
    }
}

$mpdf->Output("Resultado EIPOT Escolha de Guarnição.pdf", 'D');
ob_end_flush();
exit();
