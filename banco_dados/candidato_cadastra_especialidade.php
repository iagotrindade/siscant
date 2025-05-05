<?php
include_once '../sistema/funcoes.php';


$especialidade=null;
$registro_conselho=null;
$data_habilitacao=null;

/*
if(!$_POST)
{
    erro_mensagem("Erro 45756765!");
    exit();
}
*/
if ($_POST['especialidade'] == null || $_POST['especialidade'] == "")
{
    erro("O campo especialidade é obrigatório!");
    exit();
}
    
 if($_POST['especialidade'] != "")
    $especialidade = htmlspecialchars($_POST['especialidade']);
 
if($_POST['registro_conselho'] != "")
    $registro_conselho = htmlspecialchars($_POST['registro_conselho']);

if($_POST['data_habilitacao'] != "")
    $data_habilitacao = htmlspecialchars($_POST['data_habilitacao']);

    
include_once 'conexao.php';
session_start();
$datetime = date('Y-m-d H:i:s');

if($_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 9413474! Não foi possível fazer a atualização dos dados !"); 
    exit(); 
}

$conexao = new Conexao();

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 6745! Somente candidatos podem realizar esse cadastro!");
    exit();
}

if($data_habilitacao == null || !valida_data($data_habilitacao))
{
    erro("Erro 3263474756! Data de habilitação inválida!");
    exit();
}

if (($data_habilitacao == null || !valida_data($data_habilitacao)) && $_SESSION['selecao_codigo'] !== 'eipot') {
    erro("Erro 3263474756! Data de habilitação inválida!");
    exit();
}

if(!inscricao())
{
    erro("Erro 34545425! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

if(!isset($_SESSION['id_usuario']) || !isset($_SESSION['cpf']))
{
    erro("Erro 15235! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if($_SESSION['selecao_regiao'] == 7)
{
    $inscricoes = $conexao->get_especialidade_candidato($_SESSION['id_usuario']);
    if(count($inscricoes) > 0)
    {
        erro("Erro 2645756768! Só é possível se cadastrar em uma especialidade!");
        exit();
    }
}

$data_habilitacao = trata_data($data_habilitacao);


$verifica_candidato_especialidade = $conexao->verifica_especialidade_candidato($_SESSION['id_usuario'], $especialidade);
if(count($verifica_candidato_especialidade) > 0)
{
    erro("Erro 4856! Você já está cadastrado(a) nesta especialidade");
    exit();
}    

$get_especialidade = $conexao->get_especialidade_id($especialidade);


/////////////////
// Verifica se o candidato é Tenente, se for não pode se cadastrar em STT

$get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);

if($get_especialidade[0]['ott_stt'] == "stt")
{
    if($get_candidato[0]['posto_grad'] == 'asp' || $get_candidato[0]['posto_grad'] == '2_ten' || $get_candidato[0]['posto_grad'] == '1_ten')
    {
        erro("Erro 43456! Você já foi ou é Oficial, não pode se cadastrar como Sargento!");
        exit();
    }
}


if($_POST)
    $resultado = $conexao->insere_especialidade_candidato($_SESSION['id_usuario'],$especialidade,$registro_conselho, $data_habilitacao);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "14109", "candidato_x_especialidade", "Insert", "Se inscreveveu na especialide ".$get_especialidade[0]['nome'], $alteracoes_detalhadas);

$conexao = null;

header ("Location: ../sistema/candidato_especialidade_visualiza.php#fim_pagina");

        

?>