<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 24567436!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 35475477! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 5673453454! Você não tem permissão!");
    exit();
}

if(inscricao())
{
    erro("Erro 235235! Inscrição em andamento!"); 
    exit(); 
}

$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 8445324574! Não foi possível executar o arquivo!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$liberacao_avaliacao_curricular = (int)$get_selecao[0]['liberacao_avaliacao_curricular'];

if($liberacao_avaliacao_curricular == 1 && isset($_POST['liberacao']))
{
    erro("Erro 234643646! Status não alterado"); 
    exit();
}
if($liberacao_avaliacao_curricular == 0 && !isset($_POST['liberacao']))
{
    erro("Erro 34757858! Status não alterado"); 
    exit();
}

if(isset($_POST['liberacao']))
    $libera = 1;
else
    $libera = 0;


$alteracao = "";
if($liberacao_avaliacao_curricular && !isset($_POST['liberacao']))
    $alteracao = "liberado para NÃO LIBERADO";
if(!$liberacao_avaliacao_curricular && isset($_POST['liberacao']))
    $alteracao = "não liberado para LIBERADO";

$resultado = $conexao->libera_avaliacao_curricular($libera);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16128", "selecao", "Update", "Alterou a visualização dos candidatos da avaliação curricular de $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=avaliacao_curricular");
    exit();
}
else 
{
    erro("Erro 436347547! Status não alterado"); 
    exit();
}





?>