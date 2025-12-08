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

$resultado_pesquisa = $conexao->get_pergunta_questionario_id($id_pergunta);

$resultado = $conexao->apaga_pergunta_questionario($id_pergunta);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $resultado['id'],
        "22108",
        "Questionário Inscrição",
        "Delete",
        "Apagou a pergunta do Questionário de Inscrição: {$resultado['pergunta']}",
        "$alteracoes_detalhadas"
    );

$conexao = null;
header("Location: ../sistema/configuracao_selecao.php");
