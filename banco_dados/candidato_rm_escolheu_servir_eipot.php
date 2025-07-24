<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

$conexao = new Conexao();

// ============= VERIFICAÇÕES INICIAIS =============
if (!$_POST) {
    erro_mensagem("Erro 54437457457! Requisição inválida.");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    erro("Erro 236234646! Sessão inválida.");
    exit();
}

if ($_SESSION['perfil'] != 'candidato') {
    erro("Erro 243624747457! Permissão negada.");
    exit();
}

// ============= VERIFICA PERÍODO DE SELEÇÃO =============
$selecao = $conexao->get_selecao_id();
if (count($selecao) == 0) {
    erro_mensagem("Erro 86484658! Processo seletivo não encontrado.");
    exit();
}

$dataAtual = strtotime(date("Y-m-d"));
if ($selecao[0]['data_inicio_cidade'] == null || $dataAtual < strtotime($selecao[0]['data_inicio_cidade'])) {
    erro("Erro 57357567! Período de escolha ainda não iniciado.");
    exit();
}

if ($selecao[0]['data_fim_cidade'] != null && $dataAtual > strtotime($selecao[0]['data_fim_cidade'])) {
    erro("Erro 23463474357! Período de escolha encerrado.");
    exit();
}

// ============= VALIDA DADOS DO FORMULÁRIO =============
if (!isset($_POST['declaracao'])) {
    erro("Erro 23573458387! Você deve declarar que leu as orientações.");
    exit();
}

if (!isset($_POST['vagas_por_regiao'])) {
    erro("Erro 987654321! Dados de vagas não recebidos.");
    exit();
}

$rms_interesse = json_decode(base64_decode($_POST['rms_interesse']), true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($rms_interesse)) {
    erro("Erro 123456789!");
    exit();
}

$id_especialidade = (int)($_POST['id_especialidade'] ?? 0);
$rm_escolheu_servir = (int)($_POST['rm_escolheu_servir'] ?? 0);
$crip = htmlspecialchars($_POST['crip'] ?? '');

$lista_cidades_epecialidades = $conexao->get_cidades_especialidade($id_especialidade);
$candidatos_escolheram_rm = $conexao->candidatos_por_rm_escolhida($id_especialidade, $rm_escolheu_servir);

if ($crip === "" || $id_especialidade === 0 || !hash_equals($crip, hash('sha256', $id_especialidade . "escolhe_rm"))) {
    erro("Erro 48948456444444! Dados inválidos.");
    exit();
}

if ($rm_escolheu_servir === 0) {
    erro("Você deve selecionar uma Região Militar válida.");
    exit();
}

// ============= VERIFICA CANDIDATO =============
$id_usuario = (int)$_SESSION['id_usuario'];
$get_candidato = $conexao->get_usuario_id($id_usuario);

if (count($get_candidato) != 1 || $get_candidato[0]['concorrendo'] == '0') {
    erro("Erro 236536346! Candidato não habilitado.");
    exit();
}

// ============= VERIFICA ORDEM DE ESCOLHA E VAGAS =============
include_once "../sistema/codigos/ordena_candidatos_escolha_cidade.php";

$candidato_logado_encontrado = false;
$candidato_ja_escolheu = false;
$candidato_bloqueado_por_anterior = false;
$candidatos_faltando_a_frente = 0;
$posicao_atual = 0;
$posicao_candidato = 0;
$eh_proximo_da_vez = true;
$tem_rm_disponivel = false;
$rm_disponiveis = [];

foreach ($vetor_ordenado_candidatos as $index => $linha) {
    $posicao_atual++;

    // Se ainda não é o usuário logado e ainda não escolheu RM, conta como faltando à frente
    if (empty($linha['rm_escolheu_servir']) && $linha['id'] != $id_usuario) {
        $candidatos_faltando_a_frente++;
        $eh_proximo_da_vez = false; // Define que ainda tem alguém na frente
    }

    // Quando encontrar o candidato logado
    if ($linha['id'] == $id_usuario) {
        $eh_cotista = $linha['vaga_reservada'] == 1;
        $candidato_logado_encontrado = true;

        $posicao_candidato = $posicao_atual;

        // Verifica se o candidato já escolheu uma RM
        $candidato_ja_escolheu = !empty($linha['rm_escolheu_servir']);

        // Se ninguém mais está faltando à frente e o candidato ainda não escolheu, é o próximo da vez
        if ($candidatos_faltando_a_frente === 0 && !$candidato_ja_escolheu) {
            $eh_proximo_da_vez = true;
        }
        break;
    }
}

if ($candidato_ja_escolheu) {
    erro("Erro 2473478458! Você já escolheu uma Região Militar.");
    exit();
}

if (!$eh_proximo_da_vez) {
    erro("Erro 4575384323523! Existem $candidatos_faltando_a_frente candidato(s) na sua frente que devem escolher primeiro.");
    exit();
}

// ============= VERIFICA ESPECIALIDADE DO CANDIDATO =============
$get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);
$id_candidato_x_especialidade = null;
$nome_especialidade = null;

foreach ($get_especialidade_candidato as $especialidade) {
    if ($especialidade['id_especialidade'] == $id_especialidade) {
        $id_candidato_x_especialidade = $especialidade['id_candidato_x_especialidade'];
        $nome_especialidade = $especialidade['especialidade'];
        break;
    }
}

if ($id_candidato_x_especialidade === null) {
    erro("Erro 3462437345745! Especialidade não encontrada.");
    exit();
}


// ============= PROCESSAMENTO DA ESCOLHA =============
if ($rm_escolheu_servir == 754809) { // Desistência
    $justificativa = 'Cod 754809 - Não optou pelas Regiões Militares oferecidas';
    $resultado = $conexao->status_concorrendo_especialidade($id_candidato_x_especialidade, 0, $justificativa);

    if ($resultado) {
        $conexao->insere_log(
            $id_usuario,
            $_SESSION['cpf'],
            $id_candidato_x_especialidade,
            "16150",
            "candidato_x_especialidade",
            "Update",
            "Desistência da escolha de RM",
            print_r($resultado, true)
        );

        // Verifica se ainda está concorrendo em outras especialidades
        $esta_concorrendo = false;
        foreach ($get_especialidade_candidato as $esp) {
            if ($esp['concorrendo'] == '1' && $esp['id_especialidade'] != $id_especialidade) {
                $esta_concorrendo = true;
                break;
            }
        }

        if (!$esta_concorrendo) {
            $conexao->status_concorrendo($id_usuario, 0, $justificativa);
        }
    }
} else {
    $total_vagas_rm = $totalVagasPorRegiao[$rm_escolheu_servir] ?? 0;
    $posicoes_cotistas = definirPosicoesCotistas($total_vagas_rm);

    // ============= VERIFICA CANDIDATOS JÁ ALOCADOS ============
    $candidatos_na_rm = $conexao->candidatos_por_rm_escolhida($id_especialidade, $rm_escolheu_servir);

    $posicoes_ocupadas = [];
    foreach ($candidatos_na_rm as $candidato) {
        $posicoes_ocupadas[] = $candidato['ordemEscolhaGuarnicao'];
    }

    // ============= DEFINE A ORDEM DE ESCOLHA =============

    $ordemEscolhaGuarnicao = 1;
    while (true) {
        // Se ultrapassar o total de vagas da RM, não há mais vagas disponíveis
        if ($ordemEscolhaGuarnicao > $total_vagas_rm) {
            erro("Erro 123456789! Não há mais vagas disponíveis nesta RM.");
            exit();
        }

        // Se a posição já está ocupada, pula
        if (in_array($ordemEscolhaGuarnicao, $posicoes_ocupadas)) {
            $ordemEscolhaGuarnicao++;
            continue;
        }

        // Se o candidato NÃO é cotista e a posição é reservada para cotas, pula
        if (!$eh_cotista && in_array($ordemEscolhaGuarnicao, $posicoes_cotistas)) {
            $ordemEscolhaGuarnicao++;
            continue;
        }

        // Caso contrário, pode usar essa posição
        break;
    }

    // Define tipo da vaga
    if (in_array($ordemEscolhaGuarnicao, $posicoes_cotistas)) {
        $tipo_vaga = $eh_cotista ? 'Cota' : 'Ampla pulando cota anterior';
    } else {
        $tipo_vaga = 'Ampla Concorrência';
    }

    // Verifica se ainda há vagas disponíveis
    if ($ordemEscolhaGuarnicao > $total_vagas_rm) {
        erro("Erro 123456789! Não há mais vagas disponíveis nesta RM.");
        exit();
    }

    // Salva a escolha
    $resultado = $conexao->cadastra_rm_candidato_vai_servir(
        $id_usuario,
        $id_especialidade,
        $rm_escolheu_servir,
        $ordemEscolhaGuarnicao
    );

    if ($resultado) {
        $conexao->insere_log(
            $id_usuario,
            $_SESSION['cpf'],
            $id_candidato_x_especialidade,
            "16149",
            "candidato_x_especialidade",
            "Update",
            "Escolheu a RM $rm_escolheu_servir (Ordem: $ordemEscolhaGuarnicao - $tipo_vaga)",
            print_r($resultado, true)
        );
    } else {
        erro("Erro 263475475! Falha ao salvar sua escolha.");
        exit();
    }
}

header("Location: ../sistema/candidato_eipot_escolha_cidade.php");
exit();
