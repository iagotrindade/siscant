<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 464514! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if( $_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas".$_GET['id']))
{
    $conexao = null;
    erro("Erro 456463453474! Não foi possível adicionar o documento"); 
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_arquivo = $_GET['id'];
$nome_doc = null;

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_documentacao_obrigatoria($id_arquivo);
$nome_doc = $resultado_pesquisa[0]['nome'];

if($nome_doc == null)
{
    erro("Erro 1531! Documentação não encontrada!");
    exit(); 
}
    $resultado = $conexao->apaga_arquivo_obrigatorio_cadastrado($id_arquivo,$nome_doc);
    $alteracoes_detalhadas =  print_r($resultado, true);
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_arquivo, "15102", "documentacao_obrigatoria", "Delete", "Apagou a documentação obrigatória: $nome_doc", $alteracoes_detalhadas);

    $conexao = null;
    header ("Location: ../sistema/documentacao_obrigatoria_visualiza.php");


?>