<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 236234643643!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 234624364645! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 274373467! Você não tem permissão!");
    exit();
}


$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."vagas"))
{
    erro("Erro 6426457457! Não foi possível fazer a atualização!"); 
    exit(); 
}

$id_especialidade = (int)$_POST['id_especialidade'];
$quantidade_vagas = (int)$_POST['quantidade_vagas'];
$id_cidade = (int)$_POST['cidade'];

if($id_especialidade == 0)
{
    erro("Erro 473475756! Não foi possível fazer a atualização!"); 
    exit(); 
}
if($id_cidade == 0)
{
    erro("Erro 473475475! Cidade não selecionada!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_cidade_especialidade = $conexao->get_cidades_especialidade_por_cidade($id_especialidade, $id_cidade);

$nome_especialidade = $get_cidade_especialidade[0]['nome_especialidade'];
$nome_cidade = $get_cidade_especialidade[0]['nome'];

$resultado = $conexao->numero_vagas_cidade_especialidade_atualiza($id_especialidade, $id_cidade, $quantidade_vagas);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16143", "cidade_x_especialidade", "Update", "Alterou o nº de vagas para $quantidade_vagas da especialidade $nome_especialidade da cidade $nome_cidade", $alteracoes_detalhadas);
    header ("Location: ../sistema/especialidade_editar.php?id_especialidade=$id_especialidade");
    exit();
}
else 
{
    erro("Erro 4573858757! Atualização não realizada"); 
    exit();
}







?>