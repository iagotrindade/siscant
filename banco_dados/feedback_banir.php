<?php

include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 2345344!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 987654! Você não tem permissão para acessar esta página.");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    header("Location: ../index.php?erro=435154");
    exit();
}

if ($_POST['criptografia'] != hash('sha256', $_SESSION['assinatura_sistema'])) {
    header("Location: ../index.php?erro=18484");
    exit();
}

include_once 'conexao.php';

$conexao = new Conexao();

$usuario_logado = $conexao->get_usuario_cpf($_SESSION['cpf']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    header("Location: ../index.php?erro=184814");
    exit();
}

$id_feedback = trim($_POST['id_feedback']);
$motivo_banimento = trim($_POST['motivo_banimento']);

if ($id_feedback == null || $motivo_banimento == "") {
    erro("Os campos Usuário, Feedback e Motivo do Banimento são obrigatórios!");
    exit();
}

if ($_POST) {
    $feedback = $conexao->get_feedback_id($id_feedback);
    $resultado = $conexao->insere_feedback_ban($feedback['id_usuario'], $motivo_banimento, $id_feedback);
}

$alteracoes_detalhadas =  print_r($resultado);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22113",
        "FeedBack",
        "Delete",
        "Baniu o usuário: " . $feedback['nome_completo'] . " com o motivo: " . $motivo_banimento . " do Feedback: ",
        "$alteracoes_detalhadas"
    );
else {
    $conexao = null;
    erro("Erro 4564 Usuário não banido!");
    exit();
}

header("Location: ../sistema/feedbacks.php?sucesso=1#banidos_feedbacks");
exit();
