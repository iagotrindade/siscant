<?php
exit();
include_once '../sistema/funcoes.php';
session_start();

if($_SESSION['selecao'] == null)
{
    header("Location: ../../index.php");
    exit();
}

// <editor-fold defaultstate="collapsed" desc="GET VARIÁVEIS">

$nome_completo = null;
$cpf = null;
$telefone = null;
$motivo = null;
$mail = null;
$mail2 = null;
$mensagem = null;

if(!$_POST)
{
    erro_mensagem("Erro 76874!");
    exit();
}
if (
        $_POST['motivo']        == null ||
        $_POST['nome_completo'] == null ||
        $_POST['cpf']           == null ||
        $_POST['telefone']      == null ||
        $_POST['mail']          == null || 
        $_POST['mail2']          == null || 
        $_POST['mensagem']      == null
    )
{
    erro_mensagem("Erro 34565644! Todos os campos são obrigatórios!");
    exit();
}

$motivo = htmlspecialchars(trim($_POST['motivo']));
$nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
$cpf = htmlspecialchars(trim($_POST['cpf']));
$telefone = htmlspecialchars(trim($_POST['telefone']));
$mail = htmlspecialchars(trim($_POST['mail']));
$mail2 = htmlspecialchars(trim($_POST['mail2']));
$mensagem = htmlspecialchars(trim($_POST['mensagem']));

if($motivo == "" || $nome_completo == "" || $cpf == "" || $telefone == "" || $mail == "" || $mail2 == "" || $mensagem == "")
{
    erro_mensagem("Erro 45345744! Todos os campos são obrigatórios!");
    exit();
}
if($motivo == null || $nome_completo == null || $cpf == null || $telefone == null || $mail == null || $mail2 == null || $mensagem == null)
{
    erro_mensagem("Erro 45674544! Todos os campos são obrigatórios!");
    exit();
}

if(strlen($mensagem) > 2000)
{
    erro_mensagem("Erro 34634534! Mensagem muito grande! Não foi possível fazer o cadastro do suporte!");
    exit();
}

if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
{
    erro_mensagem("Erro 2352358! E-Mail Inválido!");
    exit();
}

if($mail != $mail2)
{
    erro_mensagem("Erro 8468468! O campo E-mail e o campo Repita o seu E-mail devem ser iguais!");
    exit();
}

$cpf = $cpf = str_replace('.','',$cpf);
$cpf = $cpf = str_replace('-','',$cpf);

if(!valida_cpf($cpf))
{
    erro_mensagem("Erro 325345! CPF Inválido!");
    exit();
}

$_SESSION['suporte_inicial'] = $cpf;

include_once 'conexao.php';
include_once '../sistema/funcoes.php';
$conexao = new Conexao();


$usuario_ja_cadastrado = $conexao->get_usuario_cpf($cpf);    

if($usuario_ja_cadastrado != null)
{
    erro_mensagem("Erro 235346! Você já possui cadastro no sistema! Para solicitar um suporte, você deve entrar no sistema!");
    exit();
}

$resultado = $conexao->insere_suporte_inicial($_SESSION['selecao'], $motivo,$nome_completo,$cpf,$telefone,$mail,$mensagem); 

if($resultado)
{
    $alteracoes_detalhadas =  print_r($resultado, true);
    $log_suporte = $conexao->insere_log(null, $cpf, null, "14105", "suporte", "Insert", "Suporte inicial enviado pelo CPF: $cpf", $alteracoes_detalhadas);
}
else
{
    erro_mensagem("Erro 235346346! Erro ao enviar o suporte!");
    $conexao = null;
    exit();
}
$conexao = null;

$cpf_criptografado = hash('sha256', $cpf);

header("Location: ../sistema/suporte_inicial_enviado_sucesso.php?c=$cpf_criptografado");
exit();
    
    