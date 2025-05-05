<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 456414! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
$cpf_usuario = null;
    
$id_usuario = $_GET['id'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_usuario_id($_GET['id']);
$cpf_usuario = $resultado_pesquisa[0]['cpf'];

if($cpf_usuario == null)
{
    erro("Erro 131! Não foi possível apagar o usuário!");
    exit();
}

$resultado = $conexao->apaga_usuario($id_usuario);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_usuario, "15107", "usuario", "Delete", "Apagou o usuário: $cpf_usuario", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario");

?>