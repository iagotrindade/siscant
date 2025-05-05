<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 345565! Você não tem permissão!");
    exit();
}

if(!inscricao())
{
    erro("Erro 3234545! Não é possível realizar a edição, o prazo já foi encerrado!");
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
    erro("Erro 4564674! Não foi possível adicionar o currículo"); 
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_especialidade_curriculo = $_GET['id'];
$nome_curriculo = null;

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_especialidade_curriculo($id_especialidade_curriculo);

$nome_curriculo = $resultado_pesquisa[0]['label'];
$id_especialidade = $resultado_pesquisa[0]['id_especialidade'];

if($id_especialidade > 0)
{
    $resultado = $conexao->apaga_curriculo_especialidade($id_especialidade_curriculo);
    $alteracoes_detalhadas = print_r($resultado, true);
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_especialidade_curriculo, "15106", "especialidade_curriculo", "Delete", "Apagou o currículo: $nome_curriculo", $alteracoes_detalhadas);

    $conexao = null;
    header ("Location: ../sistema/candidato_especialidade_cadastrada_visualiza.php?esp=$id_especialidade");
}
?>