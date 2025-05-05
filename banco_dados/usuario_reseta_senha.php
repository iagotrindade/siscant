<?php
include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 86786744!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 15614! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$cpf = $_SESSION['cpf'];
    
$id_usuario = $_POST['id_usuario'];
$cpf_usuario = $_POST['c_p_f'];

include_once 'conexao.php';
$conexao = new Conexao();

$resultado = $conexao->usuario_reseta_senha($id_usuario,$cpf_usuario);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_usuario, "16103", "usuario", "Update", "Resetou a senha do usuário $cpf_usuario", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/usuario_altera_senha.php?id_usuario=$id_usuario&senha_alterada=1");

?>