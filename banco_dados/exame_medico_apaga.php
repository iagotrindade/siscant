<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 5674! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_exame_medico = $_GET['id'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_exame_medico_id($_GET['id']);
$sessao = $resultado_pesquisa[0]['sessao'];
$dia_exame = $resultado_pesquisa[0]['dia_exame'];
$cidade = $resultado_pesquisa[0]['cidade'];

$nome_exame = "Sessão: " . $sessao. " Dia do Exame: " . $dia_exame . " Cidade: " . $cidade;

if($resultado_pesquisa == null)
{
    erro("Erro 64531! Exame não encontrado!");
    exit();    
}

$resultado = $conexao->apaga_exame_medico($id_exame_medico,$nome_exame);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_exame_medico, "15112", "exame_medico", "Delete", "Apagou a o exame médico $nome_exame", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/configuracao_selecao.php?exame_medico_apagado=1#exame_medico");

?>