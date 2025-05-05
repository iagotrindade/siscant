<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'om')
{
    erro("Erro 4645123! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$id_obs = $_GET['id'];
$id_usuario = $_GET['crip'];

include_once 'conexao.php';
$conexao = new Conexao();

if($id_obs == null)
{
    erro("Erro 1531! Documentação não encontrada!");
    exit(); 
}

$obs = $conexao->get_observacao_id($id_obs);

if($obs[0]['sistema'] == 1)
{
    erro("Erro 4531! A observação do sistema não pode ser apagada!");
    exit();
}

if($obs[0]['_usuario_ultima_atualizacao'] != $_SESSION['id_usuario'])
{
    erro("Erro 2354890! Você só pode apagar as suas observações");
    exit();
}

$resultado = $conexao->apaga_observacao_id($id_obs);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_obs, "15111", "observacao", "Delete", "Apagou a observacao ID: $id_obs", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#observacoes");


?>