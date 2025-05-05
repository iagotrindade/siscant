<?php
include_once '../sistema/funcoes.php';
session_start();

if($_GET['crip'] != hash('sha256', $_SESSION['chave']."freitas".$_GET['id']))
{
    erro("Erro 8423144574! Não foi possível apagar o arquivo!"); 
    exit(); 
}

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 6743545! Você não tem permissão!");
    exit();
}

if(!inscricao())
{
    erro("Erro 3543455! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 15631! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_arquivo = $_GET['id'];


include_once 'conexao.php';
$conexao = new Conexao();

$verifica_arquivo_candidato = $conexao->get_arquivo_pagamento_id($id_arquivo);

if($verifica_arquivo_candidato[0]['id_candidato'] != $_SESSION['id_usuario'] || $verifica_arquivo_candidato[0]['apagado'] == 1)
{
    erro("Erro 15223434531! Erro ao apagar o arquivo!");
    exit();
}

$resultado = $conexao->apaga_arquivo_pagamento($_SESSION['id_usuario'],$id_arquivo);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_arquivo, "15110", "pagamento_inscricao", "Delete", "Apagou arquivo de pagamento de inscrição", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/candidato_pagamento_inscricao.php");

?>