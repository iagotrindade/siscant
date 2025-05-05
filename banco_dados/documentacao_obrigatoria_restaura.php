<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 1532214! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
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

$resultado = $conexao->restaura_arquivo_obrigatorio($id_arquivo,$nome_doc);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_arquivo, "17102", "documentacao_obrigatoria", "Restaura", "Restaurou a documentação obrigatória: $nome_doc", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/documentacao_obrigatoria_visualiza.php");

?>