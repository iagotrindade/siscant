<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 153414! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_especialidade = $_GET['id'];
$nome_esp = null;

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_especialidade_id($id_especialidade);
$nome_esp = $resultado_pesquisa[0]['nome'];

if($nome_esp == null)
{
    erro("Erro 1531! Especialidade não encontrada!");
    exit(); 
}

$resultado = $conexao->restaura_especialidade($id_especialidade,$nome_esp);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_especialidade, "17104", "especialidade", "Restaura", "Restaurou a especialidade: $nome_esp", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/especialidade_visualiza.php");

?>