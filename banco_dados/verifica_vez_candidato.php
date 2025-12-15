<?php
// Arquivo: verifica_vez_candidato.php
// Local: ../banco_dados/verifica_vez_candidato.php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';

if (!isset($_POST['verificar_vez'])) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Requisição inválida', 'proximoCandidato' => '']);
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Sessão inválida', 'proximoCandidato' => '']);
    exit();
}

if (($_SESSION['perfil'] != 'candidato')) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Permissão negada', 'proximoCandidato' => '']);
    exit();
}

$id_especialidade = (int)$_POST['id_especialidade'];
$user_id = (int)$_POST['user_id'];

if (!$id_especialidade || !$user_id) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Dados inválidos', 'proximoCandidato' => '']);
    exit();
}

$conexao = new Conexao();

// Carrega seleção atual
$selecao = $conexao->get_selecao_id();
if (count($selecao) == 0) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Erro ao carregar seleção', 'proximoCandidato' => '']);
    exit();
}

// Verifica janelas de escolha
if ($selecao[0]['data_fim_cidade'] == null) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Não está permitido fazer a escolha ainda!', 'proximoCandidato' => '']);
    exit();
}
if (strtotime(date("Y-m-d")) > strtotime($selecao[0]['data_fim_cidade'])) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'O período de escolha já passou!', 'proximoCandidato' => '']);
    exit();
}
if (strtotime(date("Y-m-d")) < strtotime($selecao[0]['data_inicio_cidade'])) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Ainda não está permitido para fazer a escolha!', 'proximoCandidato' => '']);
    exit();
}

$get_candidato = $conexao->get_usuario_id($user_id);
if (count($get_candidato) != 1) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Candidato não encontrado', 'proximoCandidato' => '']);
    exit();
}
if ($get_candidato[0]['concorrendo'] == '0') {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Candidato desclassificado', 'proximoCandidato' => '']);
    exit();
}

// Regras de etapa por seleção
if ($selecao[0]['codigo'] == 'ott_stt' && $get_candidato[0]['etapa'] < 6) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Você deve estar pelo menos na Etapa 6 para escolher a cidade!', 'proximoCandidato' => '']);
    exit();
}
if ($selecao[0]['codigo'] == 'mfdv' && $get_candidato[0]['etapa'] < 5) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Você deve estar pelo menos na Etapa 5 para escolher a cidade!', 'proximoCandidato' => '']);
    exit();
}

// Verifica se a cidade tem vaga (validação simples)
$get_vagas_especialidade = $conexao->get_vagas_especialidade($id_especialidade);
$tem_vaga = false;
foreach ($get_vagas_especialidade as $vaga) {
    if ($vaga['vagas'] > 0) {
        $tem_vaga = true;
        break;
    }
}
if (!$tem_vaga) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Não há vagas disponíveis para esta especialidade!', 'proximoCandidato' => '']);
    exit();
}

// Verifica vinculo candidato-especialidade
$nome_especialidade = null;
$id_candidato_x_especialidade = null;
$get_especialidade_candidato = $conexao->get_especialidade_candidato($user_id);
foreach ($get_especialidade_candidato as $especialidade) {
    if ($id_especialidade == $especialidade['id_especialidade']) {
        $id_candidato_x_especialidade = $especialidade['id_candidato_x_especialidade'];
        $nome_especialidade = $especialidade['especialidade'];
        if ($especialidade['concorrendo'] == '0') {
            echo json_encode(['podeEscolher' => false, 'mensagem' => 'Candidato desclassificado da especialidade', 'proximoCandidato' => '']);
            exit();
        }
        if ($especialidade['cidade_escolheu_servir'] != null && $especialidade['cidade_escolheu_servir'] != '') {
            echo json_encode(['podeEscolher' => false, 'mensagem' => 'A Cidade só pode ser escolhida uma vez!', 'proximoCandidato' => '']);
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
    if (count($get_pontuacao_provas) > 0 && isset($get_pontuacao_provas[0]['nota_av']) && $get_pontuacao_provas[0]['nota_av'] == '1') {
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
if (!$tem_vaga) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Não há vagas disponíveis para esta especialidade!', 'proximoCandidato' => '']);
    exit();
}

// 2. Conta quantos já escolheram CORRETAMENTE (em ordem)
$escolheram = 0;
for ($i = 0; $i < count($vetor_ordenado_candidatos); $i++) {
    if (isset($vetor_ordenado_candidatos[$i])) {
        $cand = $vetor_ordenado_candidatos[$i];
        if (!empty($cand['cidade_escolheu_servir']) && $cand['cidade_escolheu_servir'] != 754809) {
            $escolheram++;
        }
    } else {
        break;
    }
}

// 3. Verifica se todas as vagas já foram preenchidas
if ($escolheram >= $total_vagas) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Todas as vagas já foram preenchidas!', 'proximoCandidato' => '']);
    exit();
}

// 4. Qual é a PRÓXIMA vaga a ser preenchida?
$proxima_vaga_numero = $escolheram + 1;

// Segurança: verifica se existe essa vaga no mapa
if ($proxima_vaga_numero > count($mapa_vagas)) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Erro no cálculo da próxima vaga!', 'proximoCandidato' => '']);
    exit();
}

$tipo_proxima_vaga = $mapa_vagas[$proxima_vaga_numero - 1];

// 5. Determina quem deve escolher agora - ALGORITMO PARA QUALQUER QUANTIDADE
$candidato_deve_escolher_id = null;
$candidato_deve_escolher_nome = '';

// Primeiro: encontra todos os candidatos que ainda não escolheram
$candidatos_nao_escolheram = [];
foreach ($vetor_ordenado_candidatos as $cand) {
    if (empty($cand['cidade_escolheu_servir']) || $cand['cidade_escolheu_servir'] == '') {
        $candidatos_nao_escolheram[] = $cand;
    }
}

// Se não há candidatos para escolher, erro
if (empty($candidatos_nao_escolheram)) {
    echo json_encode(['podeEscolher' => false, 'mensagem' => 'Não há candidatos elegíveis para escolher!', 'proximoCandidato' => '']);
    exit();
}

$vaga_revertida = ''; // Inicializa como não é vaga revertida

// Se a próxima vaga é AC: primeiro candidato na fila
if ($tipo_proxima_vaga === 'AC') {
    $candidato_deve_escolher_id = $candidatos_nao_escolheram[0]['id'];
    $candidato_deve_escolher_nome = $candidatos_nao_escolheram[0]['nome'];
} else { // $tipo_proxima_vaga === 'CN'

    // Contar apenas cotistas que realmente ocuparam vagas CN até agora
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

    // Se faltam cotistas para preencher as vagas CN
    if ($cotistas_que_ocuparam_cn < $cns_ate_proxima) {
        // PRECISA DE COTISTA: busca o próximo cotista na fila
        foreach ($candidatos_nao_escolheram as $cand) {
            if ($cand['vaga_reservada'] == 1) {
                $candidato_deve_escolher_id = $cand['id'];
                $candidato_deve_escolher_nome = $cand['nome'];
                break;
            }
        }

        // Se não encontrou cotista (todos já escolheram)
        if ($candidato_deve_escolher_id === null) {
            $vaga_revertida = '(Vaga de COTA revertida para AMPLA CONCORRÊNCIA por não haver mais COTISTAS elegíveis.)';
            // PRECISA DE AMPLA CONCORRÊNCIA: busca o próximo candidato na fila
            // Não há mais cotistas → pode ser qualquer candidato
            $candidato_deve_escolher_id = $candidatos_nao_escolheram[0]['id'];
            $candidato_deve_escolher_nome = $candidatos_nao_escolheram[0]['nome'];
        }
    } else {
        // NÃO PRECISA DE COTISTA: primeiro candidato na fila
        $candidato_deve_escolher_id = $candidatos_nao_escolheram[0]['id'];
        $candidato_deve_escolher_nome = $candidatos_nao_escolheram[0]['nome'];
    }
}

// 6. Determinar a condição do candidato logado (Ampla ou Cota)
$condicao_candidato = 'AMPLA CONCORRÊNCIA';
foreach ($vetor_ordenado_candidatos as $cand) {
    if ($cand['id'] == $user_id) {
        if ($cand['vaga_reservada'] == 1) {
            $condicao_candidato = 'COTISTA';
        }
        break;
    }
}

// 7. Verifica se o usuário atual é quem deve escolher
if ($candidato_deve_escolher_id == $user_id) {
    echo json_encode([
        'podeEscolher' => true,
        'mensagem' => 'É sua vez de escolher! Você pode selecionar uma guarnição.',
        'proximoCandidato' => '',
        'informacoesAdicionais' => [
            'condicao_candidato' => $condicao_candidato,
            'total_vagas' => $total_vagas,
            'vagas_preenchidas' => $escolheram,
            'vagas_restantes' => $total_vagas - $escolheram,
            'proxima_vaga_numero' => $proxima_vaga_numero,
            'tipo_proxima_vaga' => $tipo_proxima_vaga == 'CN' ? 'COTA' : 'AMPLA CONCORRÊNCIA',
            'cota_revertida' => $vaga_revertida,
            'sua_posicao' => array_search($user_id, array_column($vetor_ordenado_candidatos, 'id')) + 1
        ]
    ]);
} else {
    // Encontra a posição do usuário na fila
    $posicao_usuario = null;
    foreach ($vetor_ordenado_candidatos as $index => $cand) {
        if ($cand['id'] == $user_id) {
            $posicao_usuario = $index + 1;
            break;
        }
    }

    // Encontra quantos estão na frente do usuário
    $candidatos_na_frente = 0;
    $posicao_do_proximo = null;
    foreach ($candidatos_nao_escolheram as $index => $cand) {
        if ($cand['id'] == $candidato_deve_escolher_id) {
            $posicao_do_proximo = $index + 1;
        }
        if ($cand['id'] == $user_id) {
            break;
        }
        $candidatos_na_frente++;
    }

    $mensagem = "Não é sua vez de escolher ainda. ";
    if ($posicao_usuario) {
        $mensagem .= "Sua posição na fila: " . $posicao_usuario . "º. ";
    }
    if ($candidatos_na_frente > 0) {
        $mensagem .= "Há $candidatos_na_frente candidato(s) na sua frente. ";
    }

    echo json_encode([
        'podeEscolher' => false,
        'mensagem' => $mensagem,
        'proximoCandidato' => $candidato_deve_escolher_nome,
        'id_candidato' => $user_id,
        'informacoesAdicionais' => [
            'condicao_candidato' => $condicao_candidato,
            'total_vagas' => $total_vagas,
            'vagas_preenchidas' => $escolheram,
            'vagas_restantes' => $total_vagas - $escolheram,
            'proxima_vaga_numero' => $proxima_vaga_numero,
            'tipo_proxima_vaga' => $tipo_proxima_vaga == 'CN' ? 'COTA' : 'AMPLA CONCORRÊNCIA',
            'cota_revertida' => '',
            'posicao_usuario' => $posicao_usuario,
            'candidatos_na_frente' => $candidatos_na_frente,
            'posicao_do_proximo' => $posicao_do_proximo
        ]
    ]);
}


$conexao = null;
exit();
