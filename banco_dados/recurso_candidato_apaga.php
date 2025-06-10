<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'candidato')
{
    erro("Erro 243734755! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 346346346! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
include_once 'conexao.php';
$conexao = new Conexao();

$id_recurso = (int)$_GET['id'];

$get_recursos_id = $conexao->get_recurso_id($id_recurso);

if(count($get_recursos_id) != 1)
{
    $conexao = null;
    erro("Erro 235235235! Não foi possível apagar o recurso"); 
    exit();
}

if(!insere_recurso())
{
    erro("Erro 125235346! Periodo de recurso fechado!");
    exit();
}


if($get_recursos_id[0]['etapa'] != $_SESSION['etapa_selecao'])
{
    erro("Erro 234634647! Só é possível apagar o recurso da etapa corrente do processo seletivo!");
    exit();
}

$get_usuario_recurso = $conexao->get_usuario_id($get_recursos_id[0]['id_candidato']);

if(count($get_usuario_recurso) != 1)
{
    $conexao = null;
    erro("Erro 34643436! Não foi possível apagar o recurso"); 
    exit();
}

if($_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas".$id_recurso))
{
    $conexao = null;
    erro("Erro 234634634! Não foi possível apagar o recurso"); 
    exit();
}

$cpf_candidato = $get_usuario_recurso[0]['cpf'];


$resultado = $conexao->apaga_recurso_candidato($id_recurso);



$alteracoes_detalhadas = print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_recurso, "15116", "recurso", "Delete", "Apagou o seu recurso de ID: $id_recurso", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/candidato_recurso.php");
?>