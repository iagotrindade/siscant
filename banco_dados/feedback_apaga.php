<?php
include_once '../sistema/funcoes.php';
session_start();

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 346346346! A sua sessão expirou!");
    exit();
}

include_once 'conexao.php';
$conexao = new Conexao();

$cpf = $_SESSION['cpf'];

$id_feedback = $_GET['id'];

$feedback_atual = $conexao->get_feedback_id($id_feedback);

if($feedback_atual['id_usuario'] != $_SESSION['id_usuario']){
    erro("Você pode apagar apenas os feedbacks enviados por você!");
    exit();
}

$resultado = $conexao->apaga_feedback($id_feedback);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22111",
        "FeedBack",
        "Insert",
        "Deletou Feedback: " . $feedback_atual['titulo'] . " e mensagem: " . $feedback_atual['descricao'] . "",
        "$alteracoes_detalhadas"
    );

$conexao = null;
header("Location: ../sistema/feedbacks.php?sucesso=1.php#feedbacks");
