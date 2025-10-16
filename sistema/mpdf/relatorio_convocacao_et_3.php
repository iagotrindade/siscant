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
$etapa = $_POST['etapa'];
$capacidade_turno = isset($_POST['capacidade_turno']) ? (int)$_POST['capacidade_turno'] : 50;
$data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : date('Y-m-d', strtotime('next monday'));
$data_final = isset($_POST['data_final']) ? $_POST['data_final'] : date('Y-m-d', strtotime('+2 weeks'));

$qtd_especialidade = $_POST['qtd_especialidade'];

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

$html = " <p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'> <img src='../imagens/brasao.png' width='70px'><br> {$cabecalhos['' .$rm_usuario . '']} </p> <table border='0' style='width:100%; margin-top: 5px;'> <tr><th align='center'><strong>$titulo</strong></th></tr> </table> <table border='0' style='width:100%; margin-top: 5px;'> <tr><th align='center'><strong>$subtitulo</strong></th></tr> </table> <table border='0' style='width:100%; margin-top: 5px;'> <tr><th align='right'><strong>$data</strong></th></tr> </table> <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_um</p> <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_dois</p> <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_tres</p> <p style='font-size: 12px; text-align: justify; margin: 5px 0; text-indent: 2em;'>$paragrafo_quatro</p>";
$mpdf->WriteHTML($html);
// FUNÇÕES AUXILIARES PARA DISTRIBUIÇÃO POR TURNOS
function formatarDataPortugues($data)
{
    $meses = [
        '01' => 'JAN',
        '02' => 'FEV',
        '03' => 'MAR',
        '04' => 'ABR',
        '05' => 'MAI',
        '06' => 'JUN',
        '07' => 'JUL',
        '08' => 'AGO',
        '09' => 'SET',
        '10' => 'OUT',
        '11' => 'NOV',
        '12' => 'DEZ'
    ];
    $dia = date('d', strtotime($data));
    $mes = $meses[date('m', strtotime($data))];
    $ano = date('y', strtotime($data));
    return "{$dia} {$mes} {$ano}";
}
function gerarDiasUteis($data_inicio, $data_final)
{
    $dias_uteis = [];
    $data_atual = new DateTime($data_inicio);
    $data_fim = new DateTime($data_final);
    while ($data_atual <= $data_fim) {
        $dia_semana = $data_atual->format('N');
        // 1-5 = Segunda a Sexta 
        if ($dia_semana >= 1 && $dia_semana <= 5) {
            $dias_uteis[] = $data_atual->format('Y-m-d');
        }
        $data_atual->modify('+1 day');
    }
    return $dias_uteis;
}
function calcularTurnosPorDia($total_candidatos, $total_dias_uteis, $capacidade_turno)
{
    $turnos_necessarios = ceil($total_candidatos / $capacidade_turno);
    $dias_necessarios = ceil($turnos_necessarios / 2);
    // 2 turnos por dia (exceto sexta) 
    if ($dias_necessarios > $total_dias_uteis) {
        // Se precisar de mais dias do que disponível, redistribui igualmente 
        $candidatos_por_dia = ceil($total_candidatos / $total_dias_uteis);
        return ['sobrecarregado' => true, 'candidatos_por_dia' => $candidatos_por_dia];
    }
    return ['sobrecarregado' => false, 'candidatos_por_dia' => $capacidade_turno * 2];
}
// CARREGAR TODOS OS CANDIDATOS E ESPECIALIDADES 
$lista_especialidades = $conexao->get_especialidade();
$todos_candidatos = [];
$especialidades = [];
$total_candidatos = 0;
foreach ($lista_especialidades as $especialidade) {
    $id_esp = $especialidade['id'];
    $candidatos = $conexao->get_candidatos_especialidade($id_esp);
    $especialidades[$id_esp] = ['nome_especialidade' => $especialidade['ott_stt'] . ' - ' . $especialidade['nome'], 'candidatos' => []];
    foreach ($candidatos as $candidato) {
        if ($candidato['etapa'] < 3) continue;
        $id = $candidato['id'];
        $especialidades[$id_esp]['candidatos'][$id] = $candidato;
        $total_candidatos++;
        if (!isset($todos_candidatos[$id])) {
            $todos_candidatos[$id] = ['dados' => $candidato, 'especialidades' => []];
        }
        $todos_candidatos[$id]['especialidades'][] = $id_esp;
    }
}
// GERAR DIAS ÚTEIS NO PERÍODO 
$dias_uteis = gerarDiasUteis($data_inicio, $data_final);
$total_dias_uteis = count($dias_uteis);
// CALCULAR DISTRIBUIÇÃO 
$distribuicao = calcularTurnosPorDia($total_candidatos, $total_dias_uteis, $capacidade_turno);
// IDENTIFICAR CANDIDATOS COM MÚLTIPLAS ESPECIALIDADES 
$candidatos_multiplos = [];
foreach ($todos_candidatos as $id => $info) {
    if (count($info['especialidades']) > 1) {
        $candidatos_multiplos[$id] = $info;
    }
}
// DISTRIBUIR CANDIDATOS POR TURNOS 
$turnos_agendados = [];
$candidatos_agendados = [];
$dia_index = 0;
foreach ($especialidades as $esp_id => $dados_esp) {
    foreach ($dados_esp['candidatos'] as $candidato) {
        $id_candidato = $candidato['id'];
        // Se candidato já foi agendado (multiplas especialidades), pular 
        if (isset($candidatos_agendados[$id_candidato])) continue;
        // Verificar se é candidato com múltiplas especialidades 
        $eh_multiplo = isset($candidatos_multiplos[$id_candidato]);
        // Encontrar turno disponível nos dias úteis 
        $agendado = false;
        for ($i = $dia_index; $i < $total_dias_uteis; $i++) {
            $data = $dias_uteis[$i];
            $dia_semana = date('N', strtotime($data));
            if (!isset($turnos_agendados[$data])) {
                $turnos_agendados[$data] = ['manha' => ['candidatos' => [], 'especialidades' => []], 'tarde' => ['candidatos' => [], 'especialidades' => []]];
            }
            $turnos = ($dia_semana == 5) ? ['manha'] : ['manha', 'tarde'];
            foreach ($turnos as $turno) {
                if (count($turnos_agendados[$data][$turno]['candidatos']) < $capacidade_turno) {
                    // Adicionar candidato ao turno 
                    $turnos_agendados[$data][$turno]['candidatos'][$id_candidato] = $candidato;
                    $turnos_agendados[$data][$turno]['especialidades'][$esp_id][] = $id_candidato;
                    $candidatos_agendados[$id_candidato] = ['data' => $data, 'turno' => $turno];
                    $agendado = true;
                    $dia_index = $i;
                    // Avançar para o próximo dia 
                    // Se for múltiplo, adicionar às outras especialidades no mesmo turno 
                    if ($eh_multiplo) {
                        foreach ($candidatos_multiplos[$id_candidato]['especialidades'] as $outra_esp_id) {
                            if ($outra_esp_id != $esp_id) {
                                $turnos_agendados[$data][$turno]['especialidades'][$outra_esp_id][] = $id_candidato;
                            }
                        }
                    }
                    break 2;
                }
            }
        }
        // Se não encontrou vaga no período, usar último dia e sobrecarregar 
        if (!$agendado) {
            $ultima_data = end($dias_uteis);
            $dia_semana = date('N', strtotime($ultima_data));
            $turno = ($dia_semana == 5) ? 'manha' : 'tarde';
            $turnos_agendados[$ultima_data][$turno]['candidatos'][$id_candidato] = $candidato;
            $turnos_agendados[$ultima_data][$turno]['especialidades'][$esp_id][] = $id_candidato;
            $candidatos_agendados[$id_candidato] = ['data' => $ultima_data, 'turno' => $turno];
            if ($eh_multiplo) {
                foreach ($candidatos_multiplos[$id_candidato]['especialidades'] as $outra_esp_id) {
                    if ($outra_esp_id != $esp_id) {
                        $turnos_agendados[$ultima_data][$turno]['especialidades'][$outra_esp_id][] = $id_candidato;
                    }
                }
            }
        }
    }
}

$html = "
   <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'>
    <tr>
        <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>Quantidade de Candidatos Ampla Concorrência Convocados</th>
    </tr>
    <tr>
        <th style='text-align: center; width: 10%;'>Nº</th>
        <th style='text-align: center; width: 20%;'>Especialidade</th>
        <th style='text-align: center; width: 50%;'>Quantidade</th>
    </tr>
    ";

foreach ($especialidades as $esp_id) {
    $contador = 1;

    if (isset($qtd_especialidade[$esp_id])) {
        $qtd_txt = $qtd_especialidade[$esp_id];

        foreach ($candidatos as $index => $candidato) {
            // O CPF já foi mascarado no primeiro loop
            $cpf = substr($candidato['cpf'], 0, -5) . "*****";
            $html .= "
        <tr>
            <td style='text-align: center;'>$contador</td>
            <td style='text-align: center;'>$cpf</td>
            <td style='text-align: center;'>" . $qtd_txt . "</td>
        </tr>";
            $contador++;
        }

        $html .= "</table>";
        $mpdf->WriteHTML($html);
    }
}

// GERAR TABELAS POR ESPECIALIDADE E TURNO 
foreach ($turnos_agendados as $data => $turnos_dia) {
    $data_formatada = formatarDataPortugues($data);
    foreach (['manha', 'tarde'] as $turno) {
        if (empty($turnos_dia[$turno]['especialidades'])) continue;
        $hora_turno = ($turno === 'manha') ? '0800h' : '1300h';
        foreach ($turnos_dia[$turno]['especialidades'] as $esp_id => $candidatos_ids) {
            if (empty($candidatos_ids)) continue;
            $nome_especialidade = $especialidades[$esp_id]['nome_especialidade'];
            $html = "<table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'> <tr><th colspan='3' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'> " . mb_strtoupper($nome_especialidade, 'UTF-8') . "<br> {$data_formatada} ÀS {$hora_turno} </th></tr> <tr> <th style='text-align: center; width: 10%;'>Nº</th> <th style='text-align: center; width: 30%;'>CPF</th> <th style='text-align: center; width: 60%;'>NOME</th> </tr>";
            $contador = 1;
            foreach ($candidatos_ids as $candidato_id) {
                $candidato = $todos_candidatos[$candidato_id]['dados'];
                $cpf = substr($candidato['cpf'], 0, -5) . "*****";
                $html .= "<tr> <td style='text-align: center;'>{$contador}</td> <td style='text-align: center;'>{$cpf}</td> <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td> </tr>";
                $contador++;
            }
            $html .= "</table>";
            $mpdf->WriteHTML($html);
        }
    }
}
// ADICIONAR INFORMAÇÃO SOBRE A DISTRIBUIÇÃO 
if ($distribuicao['sobrecarregado']) {
    $html = "<p style='font-size: 10px; color: #666; text-align: center; margin-top: 20px;'> * Distribuição realizada entre {$data_inicio} e {$data_final} ({$total_dias_uteis} dias úteis) </p>";
    $mpdf->WriteHTML($html);
}
// =========================== // SEGUNDA PARTE - LISTA DE PRESENÇA POR ESPECIALIDADE // =========================== 
$mpdf->AddPage();
$html = " <p class='center' style='font-size: 10px; text-align: center; margin-bottom: 5px;'> <img src='../imagens/brasao.png' width='70px'><br> {$_SESSION['cabecalho_relatorio']} </p> <table border='0' style='width:100%; margin-top: 5px;'> <tr><th align='center'><strong>PROCESSO SELETIVO PARA O SERVIÇO TÉCNICO TEMPORÁRIO 20__/20__</strong></th></tr> </table> <table border='0' style='width:100%; margin-top: 5px;'> <tr><th align='center'><strong>LISTA DE PRESENÇA ETAPA III – CONFERÊNCIA PRESENCIAL DE DOCUMENTAÇÃO, ENTREVISTA E INSPEÇÃO DE SAÚDE</strong></th></tr> </table>";
$mpdf->WriteHTML($html);
// Lista de presença por especialidade 
foreach ($especialidades as $esp_id => $dados) {
    if (count($dados['candidatos']) > 0) {
        $html = " <table border='1' style='width:100%; border-collapse: collapse; margin-bottom: 20px;'> <tr> <th colspan='4' style='text-align: center; background-color: #D8D8D8; font-size: 14px;'>" . mb_strtoupper($dados['nome_especialidade'], 'UTF-8') . "</th> </tr> <tr> <th style='text-align: center; width: 10%;'>Nº</th> <th style='text-align: center; width: 25%;'>CPF</th> <th style='text-align: center; width: 45%;'>NOME</th> <th style='text-align: center; width: 20%;'>ASSINATURA</th> </tr>";
        $contador = 1;
        foreach ($dados['candidatos'] as $candidato) {
            $cpf = substr($candidato['cpf'], 0, -5) . "*****";
            $html .= " <tr> <td style='text-align: center;'>$contador</td> <td style='text-align: center;'>$cpf</td> <td style='text-align: center;'>" . strtoupper($candidato['nome_completo']) . "</td> <td></td> </tr>";
            $contador++;
        }
        $html .= "</table>";
        $mpdf->WriteHTML($html);
    }
}
$mpdf->Output("Cronograma de Convocação Etapa III.pdf", 'D');
ob_end_flush();
exit();
