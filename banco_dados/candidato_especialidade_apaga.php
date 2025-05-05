<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 6745! Somente candidatos podem realizar essa edição!");
    exit();
}

if(!inscricao())
{
    erro("Erro 35425! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

if($_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas".$_GET['id']))
{
    erro("Erro 848456! Não foi possível apagar a especialidade!"); 
    exit(); 
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_especialidade = $_GET['id'];
$nome_especialidade = null;

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $resultado_pesquisa[0]['nome'];

$resultado = $conexao->apaga_especialidade_candidato($id_especialidade,$_SESSION['id_usuario']);
$alteracoes_detalhadas = print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_especialidade, "15104", "candidato_x_especialidade", "Delete", "Apagou a inscrição da especialidade $nome_especialidade", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/candidato_especialidade_visualiza.php");

?>