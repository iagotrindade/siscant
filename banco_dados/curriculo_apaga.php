<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 5674! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
$nome_especialidade = null;
    
$id_curriculo = $_GET['id'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_curriculo_id($_GET['id']);
$nome_curriculo = $resultado_pesquisa[0]['nome'];

if($resultado_pesquisa == null)
{
    erro("Erro 64531! Currículo não encontrado!");
    exit();    
}

$resultado = $conexao->apaga_curriculo($id_curriculo,$nome_curriculo);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_curriculo, "15105", "curriculo", "Delete", "Apagou a opção de currículo: $nome_curriculo", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/curriculo_visualiza.php");

?>