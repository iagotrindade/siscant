<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 234623463456!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 234624674! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 347357534! Você não tem permissão!");
    exit();
}

$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 4574544! Não foi possível fazer a alteração!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$encerra_selecao = (int)$get_selecao[0]['encerrada'];

if($encerra_selecao == 1 && isset($_POST['encerra_selecao']))
{
    erro("Erro 236243643! Status não alterado"); 
    exit();
}
if($encerra_selecao == 0 && !isset($_POST['encerra_selecao']))
{
    erro("Erro 2364364364! Status não alterado"); 
    exit();
}

if(isset($_POST['encerra_selecao']))
    $encerra = 1;
else
    $encerra = 0;

$observacao = null;
$alteracao = "";
if($encerra_selecao && !isset($_POST['encerra_selecao']))
{
    $alteracao = "Encerrada para Aberta";
    $observacao = 'Seleção em andamento';
}
if(!$encerra_selecao && isset($_POST['encerra_selecao']))
{
    $alteracao = "Aberta para Encerrada";
    $observacao = 'Seleção encerrada';
}

$resultado = $conexao->encerra_selecao($encerra,$observacao);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16141", "selecao", "Update", "Alterou o status de seleção encerrada para $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=selecao_encerrada");
    exit();
}
else 
{
    erro("Erro 3246236! Status não alterado"); 
    exit();
}





?>