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

$id_pergunta = $_GET['id'];

$resultado_pesquisa = $conexao->get_pergunta_resposta_id($id_pergunta);

$resultado = $conexao->apaga_pergunta_resposta($id_pergunta);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $resultado['id'],
        "22103",
        "Assistente Virtual",
        "Delete",
        "Apagou a pergunta: {$resultado['pergunta']} e resposta: {$resultado['resposta']}",
        "$alteracoes_detalhadas"
    );

$conexao = null;
header("Location: ../sistema/assistente_virtual.php");
