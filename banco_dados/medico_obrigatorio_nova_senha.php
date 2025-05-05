<?php
include_once '../sistema/funcoes.php';
session_start();

erro_mensagem("Solicite a liberação dessa função para o Administrador do sistema!");
exit();

if(!$_POST)
{
    erro_mensagem("Erro 234534656!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 477546756! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 346456456! A sua sessão expirou!");
    exit();
}

if($_SESSION['selecao_codigo'] != 'mfdv')
{
    erro("Erro 436456456! Página não encontrada!");
    exit();
}

    
$id_usuario = $_POST['id_usuario'];
$cpf_usuario = $_POST['c_p_f'];
$nova_senha = $_POST['senha_medico'];

if($nova_senha == null || $cpf_usuario == null || $id_usuario == null)
{
    erro("Erro 547457457! Senha não alterada!");
    exit();
}

if($nova_senha == '123@siscant' || $nova_senha == '123@SISCANT')
{
    erro("Erro 436436436! Senha não alterada!");
    exit();
}
    
if(strlen($nova_senha) < 8)
{
    erro("Erro 2346347! A senha deve conter pelo menos 8 caracteres!");
    exit();
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_usuario= $conexao->get_usuario_id($id_usuario);

$nome_completo = $get_usuario[0]['nome_completo'];
$cpf_candidato = $get_usuario[0]['cpf'];
$mail = $get_usuario[0]['mail'];
$nome_guerra = $get_usuario[0]['nome_guerra'];
$posto_grad = $get_usuario[0]['posto_grad'];

if($cpf_usuario != $cpf_candidato)
{
    erro("Erro 436436435! Candidato não encontrado!");
    exit();
}

if($get_usuario[0]['candidato'] != 1 || $get_usuario[0]['medico_obrigatorio'] != 1)
{
    erro("Erro 4564363456! Médico não encontrado!");
    exit();
}

$resultado = $conexao->altera_senha_medico_obrigatorio($id_usuario,$nova_senha);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
{
    if($candidato == 1)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_usuario, "16129", "usuario", "Update", "Resetou a senha do médico obrigatório $cpf_usuario", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/medico_obrigatorio_altera_senha.php?id_usuario=$id_usuario&senha_alterada=1");
}

?>