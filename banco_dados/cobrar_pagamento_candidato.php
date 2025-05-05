<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 74574273!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 58689679! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 548894689! Você não tem permissão!");
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
    erro("Erro 568585473! Não foi possível fazer a atualização!"); 
    exit(); 
}

$valor = null;
if(isset($_POST['valor_cobrado']))
    $valor = $_POST['valor_cobrado'];


$apelido = null;
if(isset($_POST['apelido']))
    $apelido = $_POST['apelido'];

if(isset($_POST['pagamento']) && $apelido == null || $apelido == '')
{
    erro("Erro 73474264!  Apelido da UG/Gestão responsável pela arrecadação é Obrigatório!"); 
    exit();
}

$valor = $valor = str_replace('.','',$valor);
$valor = $valor = str_replace(',','.',$valor);
$valor = $valor = str_replace('R$','',$valor);
$valor = $valor = str_replace(' ','',$valor);

$valor = (float)$valor;

if($valor == 0) $valor = null;

if(isset($_POST['pagamento']) && $valor == null )
{
    erro("Erro 243674327457!  O valor é obrigatório!"); 
    exit();
}

if(!isset($_POST['pagamento']))
{
    $valor = null;
    $apelido = null;
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$liberacao_pagamento_inscricao = (int)$get_selecao[0]['pagamento'];


if($liberacao_pagamento_inscricao == 0 && !isset($_POST['pagamento']))
{
    erro("Erro 469679467! Status não alterado"); 
    exit();
}

if(isset($_POST['pagamento']))
    $libera = 1;
else
    $libera = 0;


$alteracao = "";
if($liberacao_pagamento_inscricao && !isset($_POST['pagamento']))
    $alteracao = "cobrar para NÃO COBRAR";
if(!$liberacao_pagamento_inscricao && isset($_POST['pagamento']))
    $alteracao = "não cobrar para COBRAR";

$descricao = null;
if($valor > 0 || $apelido != '')
    $descricao = " - Valor de R$: $valor e Apelido UG: $apelido ";

$resultado = $conexao->cobrar_candidato($libera,$valor,$apelido);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16142", "selecao", "Update", "Alterou o status de pagamento da seleção de $alteracao $descricao", $alteracoes_detalhadas);
    $_SESSION['selecao_pagamento'] = $libera;
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=inscricao");
    exit();
}
else 
{
    erro("Erro 568458457! Pagamento não alterado"); 
    exit();
}



?>