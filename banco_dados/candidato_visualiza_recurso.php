<?php

include_once '../sistema/funcoes.php';
include_once '../sistema/codigos/funcao_mostrar_recurso.php';
include_once 'conexao.php';

session_start();

$conexao = new Conexao();

// Verifica requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    erro_mensagem("Requisição inválida.");
    exit;
}

// Verificações de sessão
if (!isset($_SESSION['chave'], $_SESSION['selecao'])) {
    $conexao = null;
    erro("Sessão expirada ou inválida.");
    exit;
}

if ($_SESSION['perfil'] !== 'admin' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Permissão negada para atualizar datas.");
    exit;
}

// Validação das datas
$data_inicio_recurso = trim($_POST['data_inicio_recurso']);
$data_fim_recurso    = trim($_POST['data_fim_recurso']);

if (!$data_inicio_recurso || !$data_fim_recurso) {
    erro("As datas de início e fim são obrigatórias.");
    exit;
}

if ($data_inicio_recurso === $data_fim_recurso) {
    erro("As datas de início e fim não podem ser iguais.");
    exit;
}

if (!valida_data($data_inicio_recurso)) {
    erro("Data de início inválida.");
    exit;
}

if (!valida_data($data_fim_recurso)) {
    erro("Data final inválida.");
    exit;
}

$inicio_dt = DateTime::createFromFormat('d/m/Y', $data_inicio_recurso);
$fim_dt    = DateTime::createFromFormat('d/m/Y', $data_fim_recurso);

if (!$inicio_dt || !$fim_dt || $inicio_dt >= $fim_dt) {
    erro("A data de início deve ser menor que a data final.");
    exit;
}

$data_inicio_formatada = $inicio_dt->format('Y-m-d');
$data_fim_formatada    = $fim_dt->format('Y-m-d');

$mostrar_recurso = (int) ($_POST['mostrar_recurso'] ?? 0);
$rm_usuario = $_POST['rm'] ?? null;

if (!$rm_usuario) {
    erro("RM do usuário não informada.");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_selecao = (int) $conexao->get_selecao_id()[0]['id'];
$registro = $conexao->get_recurso_visualiza($id_selecao, $rm_usuario);

// Atualiza ou insere registro
if ($registro) {
    $resultado = $conexao->update_recurso_visualiza(
        $id_selecao,
        $rm_usuario,
        $data_inicio_formatada,
        $data_fim_formatada,
        $mostrar_recurso
    );
} else {
    $resultado = $conexao->insert_recurso_visualiza(
        $id_selecao,
        $rm_usuario,
        $data_inicio_formatada,
        $data_fim_formatada,
        $mostrar_recurso
    );
}

// Insere log se atualização ocorreu com sucesso
if ($resultado) {
    $descricao = "Atualizou a configuração de interposição de recursos: $data_inicio_recurso até $data_fim_recurso";
    $conexao->insere_log($id_usuario, $_SESSION['cpf'], $_SESSION['selecao'], "161502", "selecao", "Update", $descricao, print_r($resultado, true));
}

// Redireciona
header("Location: ../sistema/configuracoes_recursos.php?datas_atualizadas=1");
exit;
