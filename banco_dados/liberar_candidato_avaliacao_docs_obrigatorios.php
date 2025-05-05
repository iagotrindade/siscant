<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 63263636!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 23623462363! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 435734574357! Você não tem permissão!");
    exit();
}

$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 4587575645! Não foi possível executar o arquivo!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$liberacao_avaliacao_docs_obrigatorios = (int)$get_selecao[0]['liberacao_avaliacao_docs_obrigatorios'];

if($liberacao_avaliacao_docs_obrigatorios == 1 && isset($_POST['liberacao']))
{
    erro("Erro 7568458757! Status não alterado"); 
    exit();
}
if($liberacao_avaliacao_docs_obrigatorios == 0 && !isset($_POST['liberacao']))
{
    erro("Erro 4574567456! Status não alterado"); 
    exit();
}

if(isset($_POST['liberacao']))
    $libera = 1;
else
    $libera = 0;


$alteracao = "";
if($liberacao_avaliacao_docs_obrigatorios && !isset($_POST['liberacao']))
    $alteracao = "liberado para NÃO LIBERADO";
if(!$liberacao_avaliacao_docs_obrigatorios && isset($_POST['liberacao']))
    $alteracao = "não liberado para LIBERADO";

$resultado = $conexao->libera_avaliacao_docs_obrigatorios($libera);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16146", "selecao", "Update", "Alterou a visualização dos candidatos da avaliação dos documentos obrigatórios de $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=avaliacao_curricular");
    exit();
}
else 
{
    erro("Erro 3426746747645! Status não alterado"); 
    exit();
}





?>