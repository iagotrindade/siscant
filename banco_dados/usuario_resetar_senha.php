<?php
include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 64564!");
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
$candidato = $_POST['candidato'];

$nova_senha = null;
$nova_senha_crip = null;

$rand = rand(100, 10000);
$string = "altera_senha";
$codigo_criptografar = $rand.time().$string;
$nova_senha = substr(md5($codigo_criptografar) ,0,6);

$nova_senha_crip =  hash('sha256', $nova_senha);

include_once 'conexao.php';
$conexao = new Conexao();

$get_usuario= $conexao->get_usuario_id($id_usuario);

$nome = null;

$nome_completo = $get_usuario[0]['nome_completo'];
$cpf_candidato = $get_usuario[0]['cpf'];
$mail = $get_usuario[0]['mail'];
$nome_guerra = $get_usuario[0]['nome_guerra'];
$posto_grad = $get_usuario[0]['posto_grad'];

if($cpf_usuario != $cpf_candidato)
{
    erro("Erro 1534661! Candidato não encontrado!");
    exit();
}

if($candidato != 0 && $candidato != 1)
{
    erro("Erro 1561! Candidato não encontrado!");
    exit();
}

if($candidato == 1)
{
    if($nome_completo == null)
    {
        erro("Erro 1564! Candidato não encontrado!");
        exit();
    }
    else $nome = $nome_completo;
}
if($candidato == 0)
{
    if($nome_guerra == null || $posto_grad == null)
    {
        erro("Erro 1465564! Candidato não encontrado!");
        exit();
    }
    else $nome = $posto_grad . " ". $nome_guerra;
}
    

$resultado = $conexao->reseta_senha_candidato($id_usuario,$cpf_usuario,$nova_senha_crip);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
{
    if($candidato == 0)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_usuario, "16103", "usuario", "Update", "Resetou a senha do usuário $nome, CPF: $cpf_usuario", $alteracoes_detalhadas);
    
    if($candidato == 1)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_usuario, "16107", "usuario", "Update", "Resetou a senha do candidato $nome, CPF: $cpf_usuario", $alteracoes_detalhadas);
    
    $foi_enviado_email= false;
    if($mail != null)
      //  include_once './mail_reseta_senha_usuario.php';
        include_once './sendmail.php';
    
    $conexao = null;
    if($foi_enviado_email)
        header ("Location: ../sistema/usuario_altera_senha.php?id_usuario=$id_usuario&senha_alterada=1");
    else
        header ("Location: ../sistema/usuario_altera_senha.php?id_usuario=$id_usuario&senha_alterada=0");
}
else
    header ("Location: ../sistema/usuario_altera_senha.php?id_usuario=$id_usuario&senha_alterada=0");

?>