<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 3534514! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
$nome_especialidade = null;
    
$id_especalidade = $_GET['id'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_especialidade_id($_GET['id']);
$nome_especialidade = $resultado_pesquisa[0]['nome'];

$resultado = $conexao->apaga_especialidade($id_especalidade,$nome_especialidade);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_especalidade, "15101", "especialidade", "Delete", "Apagou a especialidade: $nome_especialidade", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/especialidade_visualiza.php");

?>