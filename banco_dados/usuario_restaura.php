<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 4514! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
$nome_especialidade = null;
    
$id_usuario = $_GET['id'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_usuario_id($_GET['id']);

if($resultado_pesquisa == null)
{
    erro("Erro 1564! Usuário não encontrado!");
    exit();
}    
$nome = null;
$posto_grad = $resultado_pesquisa[0]['posto_grad'];
$nome_guerra = $resultado_pesquisa[0]['nome_guerra'];

$nome = $posto_grad . " " . $nome_guerra;

if($nome == null)
{
    erro("Erro 64531! Currículo não encontrado!");
    exit();    
}

$resultado = $conexao->restaura_usuario($id_usuario,$nome);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_usuario, "17105", "usuario", "Restaura", "Restaurou o usuário: $nome", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario");

?>