<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 6745645! Você não tem permissão!");
    exit();
}

if(!inscricao())
{
    erro("Erro 35423455! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}



$cpf = $_SESSION['cpf'];
    
$id_especialidade = $_GET['id'];
$nome_arquivo = null;

if($_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas".$id_especialidade))
{
    erro("Erro 5757865785! Não foi possível apagar as prioridades!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($_SESSION['id_usuario'], $id_especialidade);
if(count($get_id_candidato_x_especialidade) != 1)
{
    $conexao = null;
    erro("Erro 54856800! Não foi possível apagar as prioridades!");
    exit();
}

$id_candidato_especialidade = (int)$get_id_candidato_x_especialidade[0]['id'];
if($id_candidato_especialidade <=0)
{
    $conexao = null;
    erro("Erro 4568468! Não foi possível apagar as prioridades!");
    exit();
}

$get_prioridade_especialidade_candidato = $conexao->get_prioridade_especialidade_candidato($id_candidato_especialidade);
if(count($get_prioridade_especialidade_candidato) == 0)
{
    $conexao = null;
    erro("Erro 76967976! Não foi possível apagar as prioridades!");
    exit();
}

$get_especialidade = $conexao->get_especialidade_id($id_especialidade);
if(count($get_especialidade) == 0)
{
    $conexao = null;
    erro("Erro 856786! Não foi possível apagar as prioridades!");
    exit();
}

$nome_especialidade = $get_especialidade[0]['nome'];

$resultado = $conexao->apaga_prioridade_candidato($id_candidato_especialidade);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_prioridade_cidade, "15108", "prioridade_cidade", "Delete", "Limpou as prioridades da especialidade $nome_especialidade", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/candidato_especialidade_cadastrada_visualiza.php?esp=$id_especialidade");


/*
$resultado_pesquisa = $conexao->verifica_prioridade_cidade_usuario($id_prioridade_cidade,$_SESSION['id_usuario']);
if(count($resultado_pesquisa) != 1)
{
    erro("Erro 4531! Não foi possível apagar a prioridade!");
    exit();
}
*/

/*

$resultado_pesquisa = $conexao->get_prioridade_cidade_id($id_prioridade_cidade);
$nome_cidade = $resultado_pesquisa[0]['nome_cidade'];
$prioridade = (int)$resultado_pesquisa[0]['prioridade'];
$id_especialidade = $resultado_pesquisa[0]['id_especialidade'];
$nome_especialidade =  $resultado_pesquisa[0]['ott_stt'] . " - " . $resultado_pesquisa[0]['nome_especialidade'];



if($prioridade > 0)
{
    $resultado = $conexao->apaga_prioridade_candidato($id_prioridade_cidade);
    $alteracoes_detalhadas =  print_r($resultado, true);
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_prioridade_cidade, "15108", "prioridade_cidade", "Delete", "Apagou a prioridade: $prioridade da Cidade $nome_cidade da Especialidade $nome_especialidade", $alteracoes_detalhadas);

    $conexao = null;
    header ("Location: ../sistema/candidato_especialidade_cadastrada_visualiza.php?esp=$id_especialidade");
}
*/


?>