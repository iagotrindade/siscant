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
$vagas_preenchidas = $conexao->get_vagas_preenchidas($id_especialidade);

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

// CÓDIGO DO FRONT //
$candidato_bloqueado_por_anterior = false;
$candidatos_faltando_a_frente = 0;
$candidato_guarnicao_escolhida = null;
$candidato_ja_escolheu = false;
$candidato_logado_encontrado = false;
$eh_proximo_da_vez = false;
$posicao_candidato = 0;
$posicao_atual = 0;
$ordem_escolha_atual = 1;
$ordem_escolha_disponivel = 1;
$esperando_vaga_cota = [];

// Agrupa o total de escolhas feitas por RM (primeira escolha)
$escolhas_por_regiao = [];
foreach ($vagas_preenchidas as $item) {
    $regiao = (int) $item['regiao_militar'];
    $quantidade = (int) $item['preenchidas'];
    $escolhas_por_regiao[$regiao] = ($escolhas_por_regiao[$regiao] ?? 0) + $quantidade;
}

// Verifica se o candidato logado é o próximo da vez (PRIMEIRA FASE)
$eh_proximo_da_vez = true;
foreach ($vetor_ordenado_candidatos as $cand) {
    if ((int)$cand['id'] === (int)$_SESSION['id_usuario']) {
        break;
    }

    if (empty($cand['rm_escolheu_servir'])) {
        $eh_proximo_da_vez = false;
        break;
    }
}

// Função para saber se uma posição é de vaga cotista
function vagaEhCotista($posicao, $total)
{
    // Última vaga pode ser de cota se total < 5
    if ($total < 5 && $posicao === $total) return true;

    // Vaga reservada a cada 5 vagas (5ª, 10ª, 15ª...)
    return ($posicao % 5 === 0);
}

$ordemEscolhaGuarnicao = null;
$esperando_vaga_cota = [];

// PRIMEIRA FASE (ESCOLHA DE RM)
$pula_vaga_reservada = false;

$ordens_cota_pendentes = []; // ← Aqui guardamos as posições reservadas para cotas que ficaram sem cotista

foreach ($vetor_ordenado_candidatos as &$linha) {
    $eh_cotista = !empty($linha['vaga_reservada']);
    $ja_escolheu = !empty($linha['rm_escolheu_servir']);

    $eh_vaga_cota = vagaEhCotista($ordem_escolha_atual, $totalVagasPorRegiao[$rm_escolheu_servir]);

    // Se a vaga atual é de cota, mas o candidato não é cotista
    if ($eh_vaga_cota && !$eh_cotista) {
        $ordens_cota_pendentes[] = $ordem_escolha_atual; // guarda essa vaga para depois
        $ordem_escolha_atual++;
        continue;
    }

    // Se o candidato for cotista e houver alguma vaga de cota pendente
    if ($eh_cotista && !empty($ordens_cota_pendentes)) {
        $ordem_usada = array_shift($ordens_cota_pendentes); // pega a primeira vaga reservada não ocupada
        $linha['ordem_escolha_guarnicao'] = $ordem_usada;

        if ((int)$linha['id'] === $id_usuario) {
            $ordemEscolhaGuarnicao = $ordem_usada;
        }

        continue; // cotista usou vaga pendente, não usa a vaga atual
    }

    // Qualquer candidato (cotista ou não) pode ocupar vaga geral
    $linha['ordem_escolha_guarnicao'] = $ordem_escolha_atual;
    if ((int)$linha['id'] === $id_usuario) {
        $ordemEscolhaGuarnicao = $ordem_escolha_atual;
    }

    $ordem_escolha_atual++;
}

// SEGUNDA FASE — Se o candidato cotista ainda não recebeu posição
if (!isset($ordemEscolhaGuarnicao)) {
    foreach ($vetor_ordenado_candidatos as $linha) {
        if ((int)$linha['id'] === $id_usuario && !empty($linha['vaga_reservada']) && empty($linha['rm_escolheu_servir'])) {
            $ordemEscolhaGuarnicao = $ordem_escolha_atual;
            break;
        }
    }
}

// E finalmente, registra:
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


header("Location: ../sistema/candidato_eipot_escolha_cidade.php");
exit();
