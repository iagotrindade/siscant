<?php

include_once '../sistema/funcoes.php';
session_start();

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 346346346! A sua sessão expirou!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 987654! Você não tem permissão para acessar esta página.");
    exit();
}

include_once 'conexao.php';

$conexao = new Conexao();

$id_ban = $_GET['id_feedback_ban'];
$usuario_id = $_GET['usuario_id'];

$usuario = $conexao->get_usuario_id($usuario_id);

$resultado = $conexao->apaga_feedback_ban($id_ban);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22114",
        "FeedBack",
        "Delete",
        "Deletou o Ban do usuário " . $usuario['nome_completo'] . " da área de Feedbacks",
        "$alteracoes_detalhadas"
    );

$conexao = null;
header("Location: ../sistema/feedbacks.php?sucesso=1.php#banidos_feedbacks");
