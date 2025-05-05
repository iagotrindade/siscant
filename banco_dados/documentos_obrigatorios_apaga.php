<?php
include_once '../sistema/funcoes.php';
session_start();

if($_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas".$_GET['id']))
{
    erro("Erro 844574! Não foi possível apagar o arquivo!"); 
    exit(); 
}

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 6745! Você não tem permissão!");
    exit();
}

if(!inscricao())
{
    erro("Erro 3545! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_arquivo = $_GET['id'];
$nome_arquivo = null;

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_documentacao_obrigatoria_id($id_arquivo);
$nome_arquivo = $resultado_pesquisa[0]['nome'];

$verifica_arquivo_candidato = $conexao->verifica_documento_obrigatorio_candidato($id_arquivo, $_SESSION['id_usuario']);

if($verifica_arquivo_candidato[0]['id_candidato'] != $_SESSION['id_usuario'])
{
    erro("Erro 15231! Erro ao apagar o arquivo!");
    exit();
}

$resultado = $conexao->apaga_arquivo_obrigatorio_inserido($_SESSION['id_usuario'],$id_arquivo);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_arquivo, "15103", "documento_obrigatorio", "Delete", "Apagou um documento obrigatório: $nome_arquivo", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/documentos_obrigatorios_visualiza.php");

?>