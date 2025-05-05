<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 213562364!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 3263463! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 45743224! Você não tem permissão!");
    exit();
}

$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 236426745! Não foi possível executar o arquivo!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$liberacao_prioridade_candidato = (int)$get_selecao[0]['liberacao_prioridade_candidato'];

if($liberacao_prioridade_candidato == 1 && isset($_POST['liberacao']))
{
    erro("Erro 234643646! Status não alterado"); 
    exit();
}
if($liberacao_prioridade_candidato == 0 && !isset($_POST['liberacao']))
{
    erro("Erro 2362346745! Status não alterado"); 
    exit();
}

if(isset($_POST['liberacao']))
    $libera = 1;
else
    $libera = 0;


$alteracao = "";
if($liberacao_prioridade_candidato && !isset($_POST['liberacao']))
    $alteracao = "liberado para NÃO LIBERADO";
if(!$liberacao_prioridade_candidato && isset($_POST['liberacao']))
    $alteracao = "não liberado para LIBERADO";

$resultado = $conexao->libera_prioridade_candidato($libera);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16139", "selecao", "Update", "Alterou a liberação para candidatos selecionarem as prioridades das cidades de $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=avaliacao_curricular");
    exit();
}
else 
{
    erro("Erro 3246236! Status não alterado"); 
    exit();
}





?>