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

$id_om = $_GET['id'];

$resultado_pesquisa = $conexao->get_om_id($id_om);

$resultado = $conexao->apaga_om($id_om);

$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $resultado['id'],
        "22106",
        "OM Apagada",
        "Delete",
        "Apagou a OM: {$resultado_pesquisa[0]['nome']} e abreviatura: {$resultado_pesquisa[0]['abreviatura']}. Detalhes:",
        "$alteracoes_detalhadas"
    );

$conexao = null;
header("Location: ../sistema/oms_cadastro.php?sucesso=1");
