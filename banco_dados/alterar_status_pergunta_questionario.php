<?php

include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

$conexao = new Conexao();

if (!$_GET['id'] || !$_GET['status']) {
    erro_mensagem("Erro 326264567!");
    exit();
}

if (!isset($_SESSION['selecao'])) {
    $conexao = null;
    erro("Erro 2346456456! Não foi possível salvar a alteração");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Erro 3464457423! Não foi possível salvar a alteração");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    $conexao = null;
    erro("Erro 2346236457! Não foi possível salvar a avaliação");
    exit();
}

$id_pergunta = $_GET['id'];
$status = $_GET['status'];

$resultado = $conexao->alterar_status_pergunta_questionario($id_pergunta, $status, $usuario_logado[0]['id']);

if ($resultado) {
    $alteracoes_detalhadas =  print_r($resultado, true);

    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "22109", "heteroidentificacao", "Insert", "Usuário " . $_SESSION['cpf'] . " alterou uma pergunta do Questionário de Inscrição", $alteracoes_detalhadas);

    header("Location: ../sistema/configuracao_selecao.php");
} else {
    $conexao = null;
    erro("Erro 4545584 heteroidentificação não cadastrada!");
    exit();
}
