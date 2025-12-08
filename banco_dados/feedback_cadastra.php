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

$titulo = trim($_POST['titulo']);
$mensagem = $_POST['descricao'];
$tipo = $_POST['tipo'];

if ($titulo == null || $mensagem == "" || $tipo == "") {
    erro("Os campos Título, Mensagem e tipo são obrigatórios!");
    exit();
}

if ($_POST) {
    $resultado = $conexao->insere_feedback($titulo, $mensagem, $tipo);
}

$alteracoes_detalhadas =  print_r($resultado);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22110",
        "FeedBack",
        "Insert",
        "Inseriu o FeedBack: $titulo e mensagem: $mensagem",
        "$alteracoes_detalhadas"
    );
else {
    $conexao = null;
    erro("Erro 4564 Feedback não cadastrado!");
    exit();
}

header("Location: ../sistema/feedbacks.php?sucesso=1#cadastro_feedbacks");
exit();
