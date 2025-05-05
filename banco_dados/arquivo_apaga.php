<?php
include_once '../sistema/funcoes.php';
session_start();

if($_GET['crip'] != hash('sha256', "freitas".$_GET['id']))
{
    erro("Erro 62346424646! Não foi possível apagar o arquivo!"); 
    exit(); 
}

if($_SESSION['candidato'] == 1 || $_SESSION['perfil'] == 'candidato')
{
    erro("Erro 456346456! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 346346346! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_arquivo = $_GET['id'];
$nome_arquivo = null;

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_arquivos_candidato_id($id_arquivo);
$nome_arquivo = $resultado_pesquisa[0]['label'];
$id_candidato = $resultado_pesquisa[0]['id_usuario'];

if($resultado_pesquisa[0]['_usuario_ultima_atualizacao'] != $_SESSION['id_usuario'] && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 5473457357! Erro ao apagar o arquivo!");
    exit();
}

$resultado = $conexao->apaga_arquivo_candidato($id_arquivo);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_arquivo, "15114", "arquivo", "Delete", "Apagou um arquivo do candidato ID $id_candidato, nome do arquivo: $nome_arquivo", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato.php#insere_arquivo_candidato");

?>