<?php
include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

// ---------------------------
// Validações iniciais
// ---------------------------
if (!$_POST) {
    erro_mensagem("Erro 54437457457!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    erro("Erro 236234646!");
    exit();
}

if (($_SESSION['perfil'] != 'candidato')) {
    erro("Erro 243624747457! Permissão Negada!");
    exit();
}

// Carrega seleção atual
$selecao = $conexao->get_selecao_id();
if (count($selecao) == 0) {
    erro_mensagem("Erro 86484658!");
    exit();
}

// Verifica janelas de escolha
if ($selecao[0]['data_fim_cidade'] == null) {
    erro("Erro 347858548! Não está permitido fazer a escolha ainda!");
    exit();
}
if (strtotime(date("Y-m-d")) > strtotime($selecao[0]['data_fim_cidade'])) {
    erro("Erro 23463474357! O período de escolha já passou!");
    exit();
}
if (strtotime(date("Y-m-d")) < strtotime($selecao[0]['data_inicio_cidade'])) {
    erro("Erro 57357567! Ainda não está permitido para fazer a escolha!");
    exit();
}

if (!isset($_POST['declaracao'])) {
    erro("Erro 23573458387! Você deve declarar que leu o aviso de ORIENTAÇÕES PARA A ESCOLHA DE GUARNIÇÃO!");
    exit();
}

// Recebe inputs
$id_especialidade = (int)$_POST['id_especialidade'];
$cidade_escolheu_servir = (int)$_POST['cidade_escolheu_servir'];
$crip = htmlspecialchars($_POST['crip']);

if ($crip == "" || $crip == null || $id_especialidade == "" || $id_especialidade == null || $id_especialidade == 0) {
    erro("Erro 48948456444444!");
    exit();
}

if ($crip != hash('sha256', $id_especialidade . "escolhe_cidade")) {
    erro("Erro 2437358745865!");
    exit();
}

if ($cidade_escolheu_servir == '' || $cidade_escolheu_servir == null) {
    erro("Você deve selecionar a cidade em que deseja servir!");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$get_candidato = $conexao->get_usuario_id($id_usuario);
if (count($get_candidato) != 1) {
    erro("Erro 236536346!");
    exit();
}
if ($get_candidato[0]['concorrendo'] == '0') {
    erro("Erro 23476437457!");
    exit();
}

// Regras de etapa por seleção
if ($selecao[0]['codigo'] == 'ott_stt' && $get_candidato[0]['etapa'] < 6) {
    erro("Erro 984946515! Você deve estar pelo menos na Etapa 6 para escolher a cidade!");
    exit();
}
if ($selecao[0]['codigo'] == 'mfdv' && $get_candidato[0]['etapa'] < 5) {
    erro("Erro 32738568! Você deve estar pelo menos na Etapa 5 para escolher a cidade!");
    exit();
}

// ---------------------------
// Verifica se a cidade tem vaga (validação simples)
// ---------------------------
$get_vagas_especialidade = $conexao->get_vagas_especialidade($id_especialidade);
foreach ($get_vagas_especialidade as $vaga) {
    if ($vaga['id_cidade'] == $cidade_escolheu_servir && $vaga['vagas'] == 0) {
        erro("Erro 85673476547! Faça o cadastro da cidade novamente!");
        exit();
    }
}

// Verifica vinculo candidato-especialidade
$nome_especialidade = null;
$get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);
$id_candidato_x_especialidade = null;
foreach ($get_especialidade_candidato as $especialidade) {
    if ($id_especialidade == $especialidade['id_especialidade']) {
        $id_candidato_x_especialidade = $especialidade['id_candidato_x_especialidade'];
        $nome_especialidade = $especialidade['especialidade'];
        if ($especialidade['concorrendo'] == '0') {
            erro("Erro 237647457! Candidato desclassificado da especialidade");
            exit();
        }
        if ($especialidade['cidade_escolheu_servir'] != null && $especialidade['cidade_escolheu_servir'] != '') {
            erro("Erro 2473478458! A Cidade só pode ser escolhida uma vez!");
            exit();
        }
    }
}

// ---------------------------
// Monta lista de candidatos e calcula pontuações e desempates
// ---------------------------
$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade);
$vetor_ordenado_candidatos = array();

foreach ($lista_candidatos as $linha) {
    if ($linha['medico_obrigatorio'] == '1') continue;

    $pontuacao_curriculo = 0;
    $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($linha['id'], $id_especialidade);
    if (count($get_pontuacao_avaliada) > 0) $pontuacao_curriculo = round($get_pontuacao_avaliada[0]['pontuacao_avaliada'], 2);

    $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($linha['id'], $id_especialidade);
    $nota_prova_teorico_pratico = 0;
    if (count($get_pontuacao_provas) > 0 && isset($get_pontuacao_provas['nota_av']) && $get_pontuacao_provas['nota_av'] == '1') {
        $nota_prova_teorico_pratico = (float)$get_pontuacao_provas[0]['nota_prova_teorico_pratico'];
        $pontuacao_curriculo = round($pontuacao_curriculo + $nota_prova_teorico_pratico, 2);
    }

    $especialidade_selecionada = $conexao->get_especialidade_id($id_especialidade);
    if (isset($especialidade_selecionada[0]['musica']) && $especialidade_selecionada[0]['musica'] == '1') {
        $prova_pratica_musica = $prova_escrita_musica = $prova_oral_musica = 0;
        if (count($get_pontuacao_provas) > 0) {
            $prova_pratica_musica = $get_pontuacao_provas[0]['prova_pratica_musica'];
            $prova_escrita_musica = $get_pontuacao_provas[0]['prova_teorica_musica'];
            $prova_oral_musica = $get_pontuacao_provas[0]['prova_oral_musica'];
        }
        $somatorio_total_pontos_musica = (((($prova_escrita_musica * 2) + ($prova_pratica_musica * 2) + $prova_oral_musica) / 5) + $pontuacao_curriculo) / 2;
        $pontuacao_curriculo = round($somatorio_total_pontos_musica, 2);
    }

    $militar = 7;
    if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten" || $linha['posto_grad'] == "asp")) $militar = 1;
    if ($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "2_ten" || $linha['posto_grad'] == "1_ten")) $militar = 2;
    if ($linha['civil_militar'] == 'civil' && ($linha['posto_grad'] == "asp")) $militar = 3;
    if ($linha['civil_militar'] == 'militar' && ($linha['posto_grad'] == "3_sgt" || $linha['posto_grad'] == "cb" || $linha['posto_grad'] == "sd")) $militar = 4;
    if ($linha['certificado'] == '1crm' || ($linha['posto_grad'] == "3_sgt" && $linha['civil_militar'] == 'civil')) $militar = 5;
    if ($linha['certificado'] == '2crm') $militar = 6;

    $anos_sv_publico = (int)$linha['tempo_sv_mil_anos'];
    $meses_sv_publico = (int)$linha['tempo_sv_mil_meses'];
    $dias_sv_publico = (int)$linha['tempo_sv_mil_dias'];
    $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + ($dias_sv_publico);

    $data_atual = new DateTime(date("Y-m-d"));
    $data_nasc = new DateTime($linha['data_nascimento']);
    $intervalo = $data_atual->diff($data_nasc);
    $anos_vida  = (int)$intervalo->format('%Y');
    $meses_vida = (int)$intervalo->format('%m');
    $dias_vida  = (int)$intervalo->format('%d');
    $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + ($dias_vida);

    if ($linha['vaga_reservada'] == 1) {
        $pareceres = $conexao->get_pareceres_heteroidentificacao($linha['id']);
        $fase1_confirmada = 0;
        $fase1_total = 0;
        $fase2_confirmada = 0;
        $fase2_nao_confirmada = 0;
        $fase2_nao_compareceu = 0;
        $fase2_total = 0;
        foreach ($pareceres as $parecer) {
            if ((int)$parecer['fase'] === 1) {
                $fase1_total++;
                if ($parecer['parecer'] === 'confirmada') $fase1_confirmada++;
            } elseif ((int)$parecer['fase'] === 2) {
                if ($parecer['parecer'] === 'confirmada') $fase2_confirmada++;
                elseif ($parecer['parecer'] === 'nao_confirmada') $fase2_nao_confirmada++;
                elseif ($parecer['parecer'] === 'nao_compareceu') $fase2_nao_compareceu++;
                $fase2_total++;
            }
        }
        if ($fase1_total === 5 && $fase1_confirmada >= 3) {
            $linha['vaga_reservada'] = 1;
        } elseif ($fase2_total === 3) {
            if ($fase2_confirmada >= 2) $linha['vaga_reservada'] = 1;
            elseif ($fase2_nao_confirmada >= 2 || $fase2_nao_compareceu >= 2) $linha['vaga_reservada'] = 0;
        } else {
            $linha['vaga_reservada'] = 0;
        }
    }

    $novo_vetor = [
        "id" => $linha['id'],
        "nome" => mb_strtoupper($linha['nome_completo'], "UTF-8"),
        "vaga_reservada" => $linha['vaga_reservada'],
        "cpf" => $linha['cpf'],
        "pontos" => $pontuacao_curriculo,
        "militar" => $militar,
        "tempo_sv_pub" => $tempo_total_sv_publico_dias,
        "tempo_idade" => $tempo_total_idade_dias,
        "mail" => $linha['mail'],
        "etapa" => $linha['etapa'],
        "cidade_escolheu_servir" => $linha['cidade_escolheu_servir']
    ];

    array_push($vetor_ordenado_candidatos, $novo_vetor);
}

// Ordenação FINAL - FUNCIONA PARA QUALQUER QUANTIDADE
if (count($vetor_ordenado_candidatos) > 0) {
    $pontos_array = $militar_array = $tempo_sv_pub = $tempo_idade = [];
    foreach ($vetor_ordenado_candidatos as $index => $linha2) {
        $pontos_array[$index]  = $linha2['pontos'];
        $militar_array[$index] = $linha2['militar'];
        $tempo_sv_pub[$index]  = $linha2['tempo_sv_pub'];
        $tempo_idade[$index]   = $linha2['tempo_idade'];
    }
    array_multisort(
        $pontos_array,
        SORT_DESC,
        $militar_array,
        SORT_ASC,
        $tempo_sv_pub,
        SORT_ASC,
        $tempo_idade,
        SORT_DESC,
        $vetor_ordenado_candidatos
    );
}

// ---------------------------
// Mapa de vagas (AC/CN) - FUNCIONA PARA QUALQUER QUANTIDADE
// ---------------------------
$total_vagas = 0;

$vagas = $conexao->get_cidades_especialidade($id_especialidade);
foreach ($vagas as $vaga) $total_vagas += (int)$vaga['numero_vagas'];

$mapa_vagas = gerar_mapa_vagas($selecao[0]['codigo'], $total_vagas);

// ---------------------------
// LÓGICA PRINCIPAL: FUNCIONA PARA QUALQUER QUANTIDADE DE VAGAS
// ---------------------------

// 1. Se não há vagas, erro
if ($total_vagas == 0) {
    erro("Erro 23462346! Não há vagas disponíveis para esta especialidade!");
    exit();
}

// 2. Conta quantos já escolheram CORRETAMENTE (em ordem)
$escolheram = 0;
for ($i = 0; $i < count($vetor_ordenado_candidatos); $i++) {
    if (isset($vetor_ordenado_candidatos[$i])) {
        $cand = $vetor_ordenado_candidatos[$i];
        if (!empty($cand['cidade_escolheu_servir']) && $cand['cidade_escolheu_servir'] != 754809) {
            $escolheram++;
        } else {
            // Encontrou alguém que não escolheu - para de contar
            break;
        }
    } else {
        break;
    }
}

// 3. Verifica se todas as vagas já foram preenchidas
if ($escolheram >= $total_vagas) {
    erro("Erro 2346346! Todas as vagas já foram preenchidas!");
    exit();
}

// 4. Qual é a PRÓXIMA vaga a ser preenchida?
$proxima_vaga_numero = $escolheram + 1;

// Segurança: verifica se existe essa vaga no mapa
if ($proxima_vaga_numero > count($mapa_vagas)) {
    erro("Erro 23462347! Erro no cálculo da próxima vaga!");
    exit();
}

$tipo_proxima_vaga = $mapa_vagas[$proxima_vaga_numero - 1];

// 5. Determina quem deve escolher agora - ALGORITMO PARA QUALQUER QUANTIDADE
$candidato_deve_escolher_id = null;

// Primeiro: encontra todos os candidatos que ainda não escolheram
$candidatos_nao_escolheram = [];
foreach ($vetor_ordenado_candidatos as $cand) {
    if (empty($cand['cidade_escolheu_servir']) || $cand['cidade_escolheu_servir'] == '') {
        $candidatos_nao_escolheram[] = $cand;
    }
}

// Se não há candidatos para escolher, erro
if (empty($candidatos_nao_escolheram)) {
    erro("Erro 34624373457! Não há candidatos elegíveis para escolher!");
    exit();
}



// Se a próxima vaga é AC: primeiro candidato na fila
if ($tipo_proxima_vaga === 'AC') {
    $candidato_deve_escolher_id = $candidatos_nao_escolheram[0]['id'];
} else { // $tipo_proxima_vaga === 'CN'
    // PARA VAGA CN: lógica especial para OTT/STT

    // =============================================
    // CORREÇÃO CRÍTICA: Contar apenas cotistas que
    // realmente ocuparam vagas CN até agora
    // =============================================

    $cotistas_que_ocuparam_cn = 0;

    // Percorre todas as vagas já preenchidas
    for ($i = 0; $i < $escolheram; $i++) {
        if (isset($vetor_ordenado_candidatos[$i])) {
            $cand = $vetor_ordenado_candidatos[$i];

            // Se o candidato é cotista
            if ($cand['vaga_reservada'] == 1) {
                // QUAL VAGA ELE OCUPOU? Precisamos saber o tipo da vaga na posição $i
                $tipo_vaga_ocupada = $mapa_vagas[$i]; // AC ou CN

                if ($tipo_vaga_ocupada === 'CN') {
                    // Cotista ocupou vaga CN → conta
                    $cotistas_que_ocuparam_cn++;
                }
                // Se ocupou AC → NÃO CONTA para preenchimento de CN
            }
        }
    }

    // Conta quantas vagas CN existem até a próxima vaga
    $cns_ate_proxima = 0;
    for ($i = 0; $i < $proxima_vaga_numero; $i++) {
        if ($mapa_vagas[$i] === 'CN') $cns_ate_proxima++;
    }

    // DEBUG (remover depois)
    //echo "DEBUG: CNs até $proxima_vaga_numeroª: $cns_ate_proxima<br>";
    //echo "DEBUG: Cotistas que ocuparam CN: $cotistas_que_ocuparam_cn<br>";

    // Se faltam cotistas para preencher as vagas CN
    if ($cotistas_que_ocuparam_cn < $cns_ate_proxima) {
        // PRECISA DE COTISTA: busca o próximo cotista na fila
        foreach ($candidatos_nao_escolheram as $cand) {
            if ($cand['vaga_reservada'] == 1) {
                $candidato_deve_escolher_id = $cand['id'];
                break;
            }
        }

        // Se não encontrou cotista (todos já escolheram)
        if ($candidato_deve_escolher_id === null) {
            // Não há mais cotistas → pode ser qualquer candidato
            $candidato_deve_escolher_id = $candidatos_nao_escolheram[0]['id'];
        }
    } else {
        // NÃO PRECISA DE COTISTA: primeiro candidato na fila
        $candidato_deve_escolher_id = $candidatos_nao_escolheram[0]['id'];
    }
}

// 6. Verifica se o usuário atual é quem deve escolher
if ($candidato_deve_escolher_id != $id_usuario) {
    // Encontra o nome do candidato que deve escolher
    $nome_candidato_deve_escolher = '';
    foreach ($vetor_ordenado_candidatos as $cand) {
        if ($cand['id'] == $candidato_deve_escolher_id) {
            $nome_candidato_deve_escolher = $cand['nome'];
            break;
        }
    }

    $conexao = null;
    erro("Erro 4575384323523! Não é sua vez de escolher. O próximo candidato a escolher é: $nome_candidato_deve_escolher");
    exit();
}

// 7. Verifica se usuário é cotista (para logging)
$usuario_eh_cotista = false;
foreach ($vetor_ordenado_candidatos as $cand) {
    if ($cand['id'] == $id_usuario) {
        $usuario_eh_cotista = $cand['vaga_reservada'] == 1;
        break;
    }
}

// ---------------------------
// AGORA PODE GRAVAR A ESCOLHA
// ---------------------------

// GRAVA ESCOLHA (inclui tratamento de desistência)
if ($cidade_escolheu_servir == 754809) {
    if ($id_candidato_x_especialidade == null) {
        erro("Erro 2473568469659! Não foi possível registrar a sua opção");
        exit();
    }

    $justificativa = 'Cod 754809 - NÃO OPTOU pelas guarnições oferecidas. Caso não sejam oferecidas novas vagas no futuro, você não será incorporado(a) como militar temporário. Obs: o Status de desclassificado é apenas temporário para liberar a escolha dos demais candidatos.';
    $resultado_concorrendo = $conexao->status_concorrendo_especialidade($id_candidato_x_especialidade, 0, $justificativa);
    $alteracoes_detalhadas = print_r($resultado_concorrendo, true);
    if ($resultado_concorrendo)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_candidato_x_especialidade", "16150", "candidato_x_especialidade", "Update", "Candidato escolheu NENHUMA DAS OPÇÕES ao selecionar a cidade de destino da especialidade $nome_especialidade", "$alteracoes_detalhadas");

    // verifica se concorre em outra especialidade
    $get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);
    $esta_concorrendo_em_outa_especialidade = false;
    foreach ($get_especialidade_candidato as $especialidade) {
        if ($especialidade['concorrendo'] === '1') {
            $esta_concorrendo_em_outa_especialidade = true;
            break;
        }
    }

    if ($esta_concorrendo_em_outa_especialidade == false) {
        $observacao = "Não está concorrendo em nenhuma especialidade! $justificativa";
        $resultado_concorrendo = $conexao->status_concorrendo($id_usuario, 0, $observacao);
        $alteracoes_detalhadas = print_r($resultado_concorrendo, true);
        if ($resultado_concorrendo) {
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "161501", "usuario", "Update", "Foi mudado o status para DESCLASSIFICADO pois não está participando de nenhuma especialidade", "$alteracoes_detalhadas");
            if ($_SESSION['selecao_regiao'] == '3') include_once '../sistema/codigos/candidato_escolhe_cidade_mail.php';
        } else {
            $conexao = null;
            erro("Erro 423345634 Não mudou o status!");
            exit();
        }
    }

    header("Location: ../sistema/candidato_escolha_cidade.php");
    exit();
}

// grava cidade escolhida
$get_cidade_id = $conexao->get_cidade_id($cidade_escolheu_servir);
$nome_cidade_escolheu = isset($get_cidade_id[0]['nome']) ? $get_cidade_id[0]['nome'] : '';

// Chamada à função de persistência existente
$cadastra_cidade_vai_servir = $conexao->cadastra_cidade_candidato_vai_servir($id_usuario, $id_especialidade, $cidade_escolheu_servir);
$alteracoes_detalhadas = print_r($cadastra_cidade_vai_servir, true);

if ($cadastra_cidade_vai_servir) {
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16149", "candidato_x_especialidade", "Update", "Candidato(a) escolheu a cidade $nome_cidade_escolheu ID: $cidade_escolheu_servir na especialidade $nome_especialidade ID: $id_especialidade " . $get_candidato[0]['cpf'], "$alteracoes_detalhadas");

    // Registra se cotista escolheu em CN (para logging apenas)
    if ($selecao[0]['codigo'] == 'ott_stt' && $usuario_eh_cotista && $tipo_proxima_vaga === 'CN') {
        $insere_log = $conexao->insere_log(
            $_SESSION['id_usuario'],
            $_SESSION['cpf'],
            "$id_usuario",
            "16201",
            "candidato_x_especialidade",
            "System",
            "Cotista escolheu em vaga CN. Vaga número: $proxima_vaga_numero",
            "Tipo vaga: $tipo_proxima_vaga"
        );
    }

    // desclassifica de outras especialidades se aplicável
    $get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);
    foreach ($get_especialidade_candidato as $especialidade) {

        $etapa = $selecao[0]['codigo'] == 'mfdv' ? 5 : 6;

        if ($especialidade['concorrendo'] === '1' && $especialidade['etapa'] == $etapa) {
            if ($especialidade['id_especialidade'] != $id_especialidade) {
                $justificativa = 'Cod 754809 - Candidato(a) optou por escolher Guarnição em outra Especialidade.';
                $resultado_concorrendo = $conexao->status_concorrendo_especialidade($especialidade['id_candidato_x_especialidade'], 0, $justificativa);
                $alteracoes_detalhadas = print_r($resultado_concorrendo, true);
                if ($resultado_concorrendo)
                    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$especialidade[id_candidato_x_especialidade]", "16150", "candidato_x_especialidade", "Update", "Candidato(a) optou por escolher Guarnição em outra Especialidade.", "$alteracoes_detalhadas");
                else {
                    $conexao = null;
                    erro("Erro 263475475! Não foi possível salvar a escolha!");
                    exit();
                }
            }
        }
    }

    if ($_SESSION['selecao_regiao'] == '3') include_once '../sistema/codigos/candidato_escolhe_cidade_mail.php';
} else {
    $conexao = null;
    erro("Erro 263475475! Não foi possível salvar a escolha!");
    exit();
}

header("Location: ../sistema/candidato_escolha_cidade.php");
exit();
