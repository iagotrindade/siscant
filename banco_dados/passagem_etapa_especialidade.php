<?php

include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

// Verificação inicial de segurança
if (!$_POST) {
    erro_mensagem("Erro 2342344! Método inválido.");
    exit();
}

if (isset($_SESSION['eipot']) == 1) {
    erro("Erro 2342344! Método inválido.");
    exit();
}

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 5673453454! Você não tem permissão!");
    exit();
}

if (inscricao()) {
    erro("Erro 24574! Inscrição em andamento!");
    exit();
}

// Sanitização e validação dos inputs
$criptografia = filter_input(INPUT_POST, 'crip', FILTER_SANITIZE_STRING);
if ($criptografia != hash('sha256', $_SESSION['chave'] . "freitas")) {
    erro("Erro 8445324574! Não foi possível executar o arquivo!");
    exit();
}

$conexao = new Conexao();

// Buscando a etapa da seleção
$get_selecao = $conexao->get_selecao_id();
if (empty($get_selecao)) {
    erro("Erro! Não foi possível obter dados da seleção.");
    exit();
}

$etapaSelecao = (int)$get_selecao[0]['etapa'];

// Sanitização dos parâmetros numéricos
$id_especialidade = filter_input(INPUT_POST, 'id_especialidade', FILTER_SANITIZE_NUMBER_INT);
$etapa = filter_input(INPUT_POST, 'etapa', FILTER_SANITIZE_NUMBER_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);
$cotistas = $_POST['cotistas'];

// Validação adicional para números positivos
if ($id_especialidade <= 0 || $etapa <= 0 || $quantidade <= 0) {
    erro("Erro! Parâmetros inválidos!");
    exit();
}

// Verificar se a etapa é válida
if ($etapa > $etapaSelecao) {
    erro('Não é possível passar os candidatos para uma etapa maior que a do sistema.');
    exit();
}

$especialidade = $conexao->get_especialidade_selecionadas($id_especialidade);

if (empty($especialidade)) {
    erro("Erro! Especialidade não encontrada!");
    exit();
}

$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade);
$vetor_ordenado_candidatos = array();

// Pré-carregar todas as pontuações para otimização
$pontuacoes_avaliadas = [];
$pontuacoes_provas = [];
foreach ($lista_candidatos as $candidato) {
    $pontuacoes_avaliadas[$candidato['id']] = $conexao->get_pontuacao_avaliada($candidato['id'], $id_especialidade);
    $pontuacoes_provas[$candidato['id']] = $conexao->verifica_especialidade_candidato($candidato['id'], $id_especialidade);
}

foreach ($lista_candidatos as $candidato) {
    // Sanitização dos dados do candidato
    $candidato['id'] = (int)$candidato['id'];
    $candidato['data_nascimento'] = filter_var($candidato['data_nascimento'], FILTER_SANITIZE_STRING);
    $candidato['cidade_escolheu_servir'] = filter_var($candidato['cidade_escolheu_servir'], FILTER_SANITIZE_STRING);
    $candidato['civil_militar'] = filter_var($candidato['civil_militar'], FILTER_SANITIZE_STRING);
    $candidato['posto_grad'] = filter_var($candidato['posto_grad'], FILTER_SANITIZE_STRING);
    $candidato['certificado'] = isset($candidato['certificado']) ? filter_var($candidato['certificado'], FILTER_SANITIZE_STRING) : '';

    if ($especialidade[0]['musica'] == '1' || $especialidade[0]['musica'] == 1) {
        // PONTUAÇÃO PARA ESPECIALIDADES MUSICAIS

        // Currículo
        $pontuacao_curriculo = 0;
        if (!empty($pontuacoes_avaliadas[$candidato['id']])) {
            $pontuacao_curriculo = round($pontuacoes_avaliadas[$candidato['id']][0]['pontuacao_avaliada'], 2);
        }

        // Provas
        $prova_pratica_musica = 0;
        $prova_escrita_musica = 0;
        $prova_oral_musica = 0;

        if (!empty($pontuacoes_provas[$candidato['id']])) {
            $prova_pratica_musica = $pontuacoes_provas[$candidato['id']][0]['prova_pratica_musica'];
            $prova_escrita_musica = $pontuacoes_provas[$candidato['id']][0]['prova_teorica_musica'];
            $prova_oral_musica = $pontuacoes_provas[$candidato['id']][0]['prova_oral_musica'];
        }

        $somatorio_total_pontos_musica = (((($prova_escrita_musica * 2) + ($prova_pratica_musica * 2) + $prova_oral_musica) / 5) + $pontuacao_curriculo) / 2;
        $pontuacao_curriculo = round($somatorio_total_pontos_musica, 2);
    } else {
        // PONTUAÇÃO PARA ESPECIALIDADES NÃO MUSICAIS

        $pontuacao_curriculo = 0;
        if (!empty($pontuacoes_avaliadas[$candidato['id']])) {
            $pontuacao_curriculo = round($pontuacoes_avaliadas[$candidato['id']][0]['pontuacao_avaliada'], 2);
        }

        // Prova Teórico Prática
        $nota_prova_teorico_pratico = 0;
        if (!empty($pontuacoes_provas[$candidato['id']])) {
            $nota_prova_teorico_pratico = $pontuacoes_provas[$candidato['id']][0]['nota_prova_teorico_pratico'];
            $pontuacao_curriculo = round($pontuacao_curriculo + $nota_prova_teorico_pratico, 2);
        }
    }

    // Categoria militar
    $militar = 7; // Padrão (civil sem certificado)

    switch (true) {
        case ($candidato['civil_militar'] == 'militar' && in_array($candidato['posto_grad'], ["2_ten", "1_ten", "asp"])):
            $militar = 1; // Oficiais da Ativa
            break;
        case ($candidato['civil_militar'] == 'civil' && in_array($candidato['posto_grad'], ["2_ten", "1_ten"])):
            $militar = 2; // Oficial R2
            break;
        case ($candidato['civil_militar'] == 'civil' && $candidato['posto_grad'] == "asp"):
            $militar = 3; // Aspirante R2
            break;
        case ($candidato['civil_militar'] == 'militar' && in_array($candidato['posto_grad'], ["3_sgt", "cb", "cd", "sd"])):
            $militar = 4; // Praça Ativa
            break;
        case ($candidato['certificado'] == '1crm' || ($candidato['posto_grad'] == "3_sgt" && $candidato['civil_militar'] == 'civil')):
            $militar = 5; // Reservista de 1ª categoria
            break;
        case ($candidato['certificado'] == '2crm'):
            $militar = 6; // Reservista de 2ª categoria
            break;
    }

    // Cálculo do tempo de serviço público
    $anos_sv_publico = isset($candidato['tempo_sv_mil_anos']) ? (int)$candidato['tempo_sv_mil_anos'] : 0;
    $meses_sv_publico = isset($candidato['tempo_sv_mil_meses']) ? (int)$candidato['tempo_sv_mil_meses'] : 0;
    $dias_sv_publico = isset($candidato['tempo_sv_mil_dias']) ? (int)$candidato['tempo_sv_mil_dias'] : 0;

    $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + $dias_sv_publico;

    // Cálculo da idade
    $data_atual = new DateTime(date("Y-m-d"));
    $data_nasc = new DateTime($candidato['data_nascimento']);
    $intervalo = $data_atual->diff($data_nasc);

    $anos_vida = $intervalo->y;
    $meses_vida = $intervalo->m;
    $dias_vida = $intervalo->d;

    $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + $dias_vida;

    // Preparação do array ordenado
    $novo_vetor = [
        "id" => $candidato['id'],
        "nome" => mb_strtoupper($candidato['nome_completo'], "UTF-8"),
        "cpf" => $candidato['cpf'],
        "mail" => $candidato['mail'],
        "vaga_reservada" => $candidato['vaga_reservada'],
        "pontos" => $pontuacao_curriculo,
        "militar" => $militar,
        "tempo_sv_pub" => $tempo_total_sv_publico_dias,
        "tempo_idade" => $tempo_total_idade_dias,
        "etapa_candidato" => (int)$candidato['etapa_candidato'],
        "etapa" => (int)$candidato['etapa'],
        "cidade_escolheu_servir" => $candidato['cidade_escolheu_servir']
    ];

    if ($especialidade[0]['musica'] == '1' || $especialidade[0]['musica'] == 1) {
        $novo_vetor["pratica"] = $prova_pratica_musica;
        $novo_vetor["escrita"] = $prova_escrita_musica;
        $novo_vetor["oral"] = $prova_oral_musica;
    } else {
        $novo_vetor["nota_prova_teorico_pratico"] = $nota_prova_teorico_pratico;
    }

    array_push($vetor_ordenado_candidatos, $novo_vetor);
}

// Ordenação
if (!empty($vetor_ordenado_candidatos)) {
    // Preparar arrays para ordenação
    $pontos_array = $militar_array = $tempo_sv_pub = $tempo_idade = [];
    $prova_pratica_array = $prova_escrita_array = $prova_oral_array = [];

    foreach ($vetor_ordenado_candidatos as $index => $candidato) {
        $pontos_array[$index] = $candidato['pontos'];
        $militar_array[$index] = $candidato['militar'];
        $tempo_sv_pub[$index] = $candidato['tempo_sv_pub'];
        $tempo_idade[$index] = $candidato['tempo_idade'];

        if ($especialidade[0]['musica'] == '1' || $especialidade[0]['musica'] == 1) {
            $prova_pratica_array[$index] = $candidato['pratica'];
            $prova_escrita_array[$index] = $candidato['escrita'];
            $prova_oral_array[$index] = $candidato['oral'];
        }
    }

    // Ordenar de acordo com o tipo de especialidade
    if ($especialidade[0]['musica'] == '1' || $especialidade[0]['musica'] == 1) {
        array_multisort(
            $pontos_array,
            SORT_DESC,
            $prova_pratica_array,
            SORT_DESC,
            $prova_escrita_array,
            SORT_DESC,
            $prova_oral_array,
            SORT_DESC,
            $militar_array,
            SORT_ASC,
            $tempo_sv_pub,
            SORT_ASC,
            $tempo_idade,
            SORT_DESC,
            $vetor_ordenado_candidatos
        );
    } else {
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
}

// Atualização no banco de dados (apenas os primeiros $quantidade candidatos)
$sucesso = true;
$quantidade = min($quantidade, count($vetor_ordenado_candidatos));

for ($i = 0; $i < $quantidade; $i++) {
    $candidato = $vetor_ordenado_candidatos[$i];

    // Alterar a etapa do Candidato se já não estiver na etapa de destino
    if ($candidato['etapa_candidato'] < $etapa) {
        $resultadoEtapaCandidato = $conexao->altera_etapa_candidato($candidato['id'], $etapa);

        if ($resultadoEtapaCandidato) {
            $obs = "Cod: 95471. Candidato passou para etapa " . $etapa . "!";
            $resultadoObs = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
            $alteracoes_detalhadas = print_r($resultadoEtapaCandidato, true);

            if ($resultadoObs) {
                $insere_log = $conexao->insere_log(
                    $_SESSION['id_usuario'],
                    $candidato['cpf'],
                    $candidato['id'],
                    "14122",
                    "usuario",
                    "Insert",
                    "Observação adicionada, Candidato passou para ETAPA " . $etapa,
                    $alteracoes_detalhadas
                );
            }
        } else {
            $sucesso = false;
        }
    }

    $resultadoEtapaEspecialidade = $conexao->altera_etapa_especialidade($candidato['id'], $id_especialidade, $etapa);

    if ($resultadoEtapaEspecialidade) {
        $obs = "Cod: 95471. Candidato passou para etapa " . $etapa . " na especialidade " . $especialidade[0]['nome'] . "!";
        $resultadoObs = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
        $alteracoes_detalhadas = print_r($resultadoEtapaEspecialidade, true);

        if ($resultadoObs) {
            $insere_log = $conexao->insere_log(
                $_SESSION['id_usuario'],
                $candidato['cpf'],
                $candidato['id'],
                "14122",
                "usuario",
                "Insert",
                "Observação adicionada, Candidato passou para ETAPA " . $etapa . " na especialidade " . $especialidade[0]['nome'] . "!",
                $alteracoes_detalhadas
            );
        }
    } else {
        $sucesso = false;
    }
}

if ($cotistas == 1) {
    foreach ($vetor_ordenado_candidatos as $candidato) {
        if ($candidato['vaga_reservada']) {
            // Alterar a etapa do Candidato se já não estiver na etapa de destino
            if ($candidato['etapa_candidato'] < $etapa) {
                $resultadoEtapaCandidato = $conexao->altera_etapa_candidato($candidato['id'], $etapa);

                if ($resultadoEtapaCandidato) {
                    $obs = "Cod: 95471. Candidato passou para etapa " . $etapa . " pelo Script de Cotas!";
                    $resultadoObs = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
                    $alteracoes_detalhadas = print_r($resultadoEtapaCandidato, true);

                    if ($resultadoObs) {
                        $insere_log = $conexao->insere_log(
                            $_SESSION['id_usuario'],
                            $candidato['cpf'],
                            $candidato['id'],
                            "14122",
                            "usuario",
                            "Insert",
                            "Observação adicionada, Candidato passou para ETAPA " . $etapa . " pelo Script de Cotas",
                            $alteracoes_detalhadas
                        );
                    }
                } else {
                    $sucesso = false;
                }
            }

            $resultadoEtapaEspecialidade = $conexao->altera_etapa_especialidade($candidato['id'], $id_especialidade, $etapa);

            if ($resultadoEtapaEspecialidade) {
                $obs = "Cod: 95471. Candidato passou para etapa " . $etapa . " na especialidade " . $especialidade[0]['nome'] . " pelo Script de Cotas!";
                $resultadoObs = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
                $alteracoes_detalhadas = print_r($resultadoEtapaEspecialidade, true);

                if ($resultadoObs) {
                    $insere_log = $conexao->insere_log(
                        $_SESSION['id_usuario'],
                        $candidato['cpf'],
                        $candidato['id'],
                        "14122",
                        "usuario",
                        "Insert",
                        "Observação adicionada, Candidato passou para ETAPA " . $etapa . " na especialidade " . $especialidade[0]['nome'] . " pelo Script de Cotas!",
                        $alteracoes_detalhadas
                    );
                }
            } else {
                $sucesso = false;
            }
        }
    }
}

if ($sucesso) {
    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
} else {
    header("Location: ../sistema/etapa_passagem.php?sucesso=0&erro=Alguns candidatos não puderam ser atualizados");
}
exit();
