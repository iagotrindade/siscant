<?php
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 456514! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_candidato = $_GET['id'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado_pesquisa = $conexao->get_usuario_id($id_candidato);
if($resultado_pesquisa == null)
{
    erro("Candidato não encontrado!");
    exit();
}
$cpf_candidato = $resultado_pesquisa[0]['cpf'];
$candidato_apagado = $resultado_pesquisa[0]['apagado'];

if($candidato_apagado == 0)
{
    erro("Candidato não está apagado!");
    exit();
}

if($candidato_apagado == 1)
{
    $resultado = $conexao->restaura_candidato($id_candidato,$cpf_candidato);
    $alteracoes_detalhadas =  print_r($resultado, true);
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_candidato, "17101", "usuario", "Restaura", "Restaurou o candidato: $cpf_candidato", $alteracoes_detalhadas);

    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato");
}
?>