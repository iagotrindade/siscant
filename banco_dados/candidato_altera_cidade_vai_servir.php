<?php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 54437457457!");
    exit();
}

if(($_SESSION['perfil'] != 'admin'))
{
    erro("Erro 3473477! Permissão Negada!");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];



$cidade_escolheu_servir = (int)$_POST['cidade_candidato'];
$id_especialidade_servir = (int)$_POST['id_especialidade_servir'];
$cpf_candidato = $_POST['c_p_f_candidato_servir'];
$id_candidato = (int)$_POST['id_candidato_servir'];

if($cidade_escolheu_servir == 0) $cidade_escolheu_servir = null;


if($cidade_escolheu_servir <= 0 || $id_especialidade_servir <= 0 || $id_candidato <= 0)
{
    erro("Erro 236993234!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_candidato);
$get_cidade = $conexao->get_cidade_id($cidade_escolheu_servir);
$get_especialidade= $conexao->get_especialidade_id($id_especialidade_servir);
$nome_cidade = $get_cidade[0]['nome'];

$nome_especialidade = $get_especialidade[0]['nome'];

if(count($get_candidato) != 1 || count($get_cidade) != 1 || count($get_especialidade) != 1 || $nome_cidade == "")
{
    erro("Erro 2356457856!");
    exit();
}



$cadastra_cidade_vai_servir = $conexao->cadastra_cidade_candidato_vai_servir($id_candidato, $id_especialidade_servir, $cidade_escolheu_servir);
$alteracoes_detalhadas =  print_r($cadastra_cidade_vai_servir, true);

if($cadastra_cidade_vai_servir)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_candidato", "161506", "candidato_x_especialidade", "Update", "Usuário de ID $id_usuario alterou a cidade do candidato de CPF $cpf_candidato e ID $id_candidato para a cidade $nome_cidade de ID $cidade_escolheu_servir na especialidade $nome_especialidade de ID $id_especialidade_servir", "$alteracoes_detalhadas");
}
else
{
    $conexao = null;
    erro("Erro 263475475! Não foi possível salvar a escolha!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#concorrendo_especialidade_id_$id_especialidade_servir");
exit();

?>



