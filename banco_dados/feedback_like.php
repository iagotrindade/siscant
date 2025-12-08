<?php

include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 2345344!");
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

$action = trim($_POST['action']);
$id_feedback = $_POST['id_feedback'];

if ($action == null || $id_feedback == "") {
    erro("O campo Status do feedback é obrigatório!");
    exit();
}

if ($_POST) {
    $feedback_atual = $conexao->get_feedback_id($id_feedback);
    $resultado = $conexao->feedback_like($id_feedback, $action, $_SESSION['id_usuario']);
}

$alteracoes_detalhadas =  print_r($resultado);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22112",
        "FeedBack",
        "Insert",
        "Alterou o Status do Feedback: " . $feedback_atual['titulo'] . " e mensagem: " . $feedback_atual['descricao'] . " para: " . $status,
        "$alteracoes_detalhadas"
    );
else {
    $conexao = null;
    erro("Erro 4564 Like/Dislike não registrado!");
    exit();
}

header("Location: ../sistema/feedbacks.php?sucesso=1#feedbacks");
exit();
