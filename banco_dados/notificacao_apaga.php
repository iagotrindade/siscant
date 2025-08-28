<?php
include_once '../sistema/funcoes.php';
session_start();

if ($_SESSION['candidato'] == 1 || $_SESSION['perfil'] == 'candidato') {
    erro("Erro 456346456! Você não tem permissão!");
    exit();
}

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 346346346! A sua sessão expirou!");
    exit();
}

include_once 'conexao.php';
$conexao = new Conexao();

$cpf = $_SESSION['cpf'];

$id_notificacao = $_GET['id'];

$resultado_pesquisa = $conexao->get_notificacao_id($id_notificacao);

$resultado = $conexao->apaga_notificacao($id_notificacao);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $resultado['id'],
        "22106",
        "Assistente Virtual",
        "Delete",
        "Apagou a notificação: {$resultado['titulo']} e mensagem: {$resultado['mensagem']}",
        "$alteracoes_detalhadas"
    );

$conexao = null;
header("Location: ../sistema/notificacoes.php");
