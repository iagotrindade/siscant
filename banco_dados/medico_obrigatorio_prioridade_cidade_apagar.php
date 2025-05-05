<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['candidato'] == 1 || $_SESSION['perfil'] != 'admin')
{
    erro("Erro 34673467! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_POST['c_p_f_medico'];
$id_especialidade = $_POST['id_especialidade'];
$id_medico = $_POST['id_medico'];

if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    erro("Erro 23456347! Não foi possível apagar as prioridades!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($id_medico, $id_especialidade);
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
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_prioridade_cidade, "15113", "prioridade_cidade", "Delete", "Limpou as prioridades da especialidade $nome_especialidade do médico obrigatório $cpf", $alteracoes_detalhadas);

$conexao = null;
header("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=especialidade#especialidade");


?>