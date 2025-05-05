<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 2342344!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 5673453454! Você não tem permissão!");
    exit();
}

/*
if(inscricao())
{
    erro("Erro 274! Inscrição em andamento!"); 
    exit(); 
}
*/
$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 8445324574! Não foi possível executar o arquivo!"); 
    exit(); 
}




include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$liberacao_comprovante_inscricao = (int)$get_selecao[0]['liberacao_comprovante_inscricao'];

if($liberacao_comprovante_inscricao == 1 && isset($_POST['liberacao']))
{
    erro("Erro 324563246! Status não alterado"); 
    exit();
}
if($liberacao_comprovante_inscricao == 0 && !isset($_POST['liberacao']))
{
    erro("Erro 2346247457! Status não alterado"); 
    exit();
}

if(isset($_POST['liberacao']))
    $libera = 1;
else
    $libera = 0;


$alteracao = "";
if($liberacao_comprovante_inscricao && !isset($_POST['liberacao']))
    $alteracao = "liberado para NÃO LIBERADO";
if(!$liberacao_comprovante_inscricao && isset($_POST['liberacao']))
    $alteracao = "não liberado para LIBERADO";

$resultado = $conexao->libera_comprovante_inscricao($libera);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16115", "selecao", "Update", "Alterou a liberação dos comprovantes de inscrição para os candidatos de $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=inscricao");
    exit();
}
else 
{
    erro("Erro 425743574! Status não alterado"); 
    exit();
}







?>