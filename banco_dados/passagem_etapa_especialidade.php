<?php

include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

// Verificação inicial de segurança
if (!$_POST) {
    erro_mensagem("Erro 2342344!");
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
$etapaSelecao = $get_selecao[0]['etapa'];

// Sanitização dos parâmetros numéricos
$id_especialidade = filter_input(INPUT_POST, 'id_especialidade', FILTER_SANITIZE_NUMBER_INT);
$etapa = filter_input(INPUT_POST, 'etapa', FILTER_SANITIZE_NUMBER_INT);
$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);

// Validação adicional para números positivos
if ($id_especialidade <= 0 || $etapa <= 0 || $quantidade <= 0) {
    erro("Erro! Parâmetros inválidos!");
    exit();
}

$especialidade = $conexao->get_especialidade_selecionadas($id_especialidade);

if (count($especialidade) == 0) {
    erro("Erro! Especialidade não encontrada!");
    exit();
}

$lista_candidatos = $conexao->get_candidatos_especialidade($id_especialidade);

$vetor_ordenado_candidatos = array();

foreach ($lista_candidatos as $candidato) {
    // Sanitização dos dados do candidato
    $candidato['id'] = filter_var($candidato['id'], FILTER_SANITIZE_NUMBER_INT);
    $candidato['data_nascimento'] = filter_var($candidato['data_nascimento'], FILTER_SANITIZE_STRING);
    $candidato['cidade_escolheu_servir'] = filter_var($candidato['cidade_escolheu_servir'], FILTER_SANITIZE_STRING);

    // Pontuação do currículo
    $pontuacao_curriculo = 0;
    $get_pontuacao_avaliada = $conexao->get_pontuacao_avaliada($candidato['id'], $id_especialidade);
    if (count($get_pontuacao_avaliada) > 0) {
        $pontuacao_curriculo = round(filter_var($get_pontuacao_avaliada[0]['pontuacao_avaliada'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), 2);
    }

    // Prova Teórico Prática
    $get_pontuacao_provas = $conexao->verifica_especialidade_candidato($candidato['id'], $id_especialidade);
    $nota_prova_teorico_pratico = 0;
    if (count($get_pontuacao_provas) > 0) {
        $nota_prova_teorico_pratico = filter_var($get_pontuacao_provas[0]['nota_prova_teorico_pratico'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $pontuacao_curriculo = round($pontuacao_curriculo + $nota_prova_teorico_pratico, 2);
    }

    // Categoria militar usando switch
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

    // Cálculo do tempo de serviço público (Linha ~70)
    $anos_sv_publico = isset($candidato['tempo_sv_mil_anos']) ? (int)$candidato['tempo_sv_mil_anos'] : 0;
    $meses_sv_publico = isset($candidato['tempo_sv_mil_meses']) ? (int)$candidato['tempo_sv_mil_meses'] : 0;
    $dias_sv_publico = isset($candidato['tempo_sv_mil_dias']) ? (int)$candidato['tempo_sv_mil_dias'] : 0;

    $tempo_total_sv_publico_dias = ($anos_sv_publico * 365) + ($meses_sv_publico * 30) + $dias_sv_publico;

    // Cálculo da idade (Linha ~101)
    $data_atual = new DateTime(date("Y-m-d"));
    $data_nasc = new DateTime($candidato['data_nascimento']);
    $intervalo = $data_atual->diff($data_nasc);

    $anos_vida = (int)$intervalo->y;  // Usando ->y em vez de format('%Y')
    $meses_vida = (int)$intervalo->m; // Usando ->m em vez de format('%m')
    $dias_vida = (int)$intervalo->d;  // Usando ->d em vez de format('%d')

    $tempo_total_idade_dias = ($anos_vida * 365) + ($meses_vida * 30) + $dias_vida;

    // Preparação do array ordenado
    $novo_vetor = [
        "id" => $candidato['id'],
        "cpf" => $candidato['cpf'],
        "pontos" => $pontuacao_curriculo,
        "militar" => $militar,
        "tempo_sv_pub" => $tempo_total_sv_publico_dias,
        "tempo_idade" => $tempo_total_idade_dias,
        "etapa" => filter_var($candidato['etapa'], FILTER_SANITIZE_NUMBER_INT),
    ];

    array_push($vetor_ordenado_candidatos, $novo_vetor);
}

// Ordenação
if (count($lista_candidatos) > 0) {
    foreach ($vetor_ordenado_candidatos as $index => $candidato2) {
        $pontos_array[$index] = $candidato2['pontos'];
        $militar_array[$index] = $candidato2['militar'];
        $tempo_sv_pub[$index] = $candidato2['tempo_sv_pub'];
        $tempo_idade[$index] = $candidato2['tempo_idade'];
    }

    if (count($vetor_ordenado_candidatos) > 0) {
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
for ($i = 0; $i < min($quantidade, count($vetor_ordenado_candidatos)); $i++) {

    $candidato = $vetor_ordenado_candidatos[$i];

    // Alterar a etapa do Candidato se ela for menos do que a enviada
    if ($etapa <= $etapaSelecao) {
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
                    "Observação adicionada, Candidato passou para ETAPA " . $etapa . "",
                    $alteracoes_detalhadas
                );
            }
        }

        $resultadoEtapaEspecialidade = $conexao->altera_etapa_especialidade($candidato['id'], $id_especialidade, $etapa);

        if ($resultadoEtapaEspecialidade) {
            $obs = "Cod: 95471. Candidato passou para etapa " . $etapa . " na especialidade " . $especialidade[0]['nome'] . "!";
            $resultadoObs = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
            $alteracoes_detalhadas = print_r($resultado, true);

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
        }

        // Retorno seguro (em produção, usar JSON)
        header("Location: ../sistema/etapa_passagem.php?sucesso=1");
        exit();
    } else {
        erro('Não é possível passar os candidatos para uma etapa maior que a do sistema.');
    }
}
