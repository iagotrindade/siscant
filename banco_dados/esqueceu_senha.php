<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 235344!");
    exit();
}

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=134656");
    exit();
}
$conexao = new Conexao();

$selecao = $conexao->get_selecao_id();

if ($selecao[0]['codigo'] == 'cet') {
    erro_mensagem("Não é possível resetar a senha em Seleção CET!");
    exit();
}


$cpf_usuario = htmlspecialchars(trim($_POST['cpf']));
$mail_usuario = htmlspecialchars(trim($_POST['mail']));

$nova_senha_crip = null;
$rand = rand(100, 10000);
$string = "altera_senha";
$codigo_criptografar = $rand . time() . $string;
$nova_senha = substr(md5($codigo_criptografar), 0, 6);
$nova_senha_crip =  hash('sha256', $nova_senha);

// Resolvido o problema do email do SiSCanT gerando uma senha de APP no clinet de email
//$nova_senha_crip = 'e6faaeeed6eeb9dd2bb3265ec1ab0a15f1f963eb25103d0f607bf67aa1ac5a0c'; //VAI DIRETO PRA 123@siscant


$get_usuario = $conexao->get_usuario_cpf($cpf_usuario);

if ($get_usuario == null) {
    $conexao = null;
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=27457");
    exit();
}

if (!filter_var($mail_usuario, FILTER_VALIDATE_EMAIL)) {
    $conexao = null;
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=348567895");
    exit();
}

if ($get_usuario[0]['mail'] != $mail_usuario) {
    $conexao = null;
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=43457665");
    exit();
}

$nome = null;
$id_usuario = null;
$id_usuario = $get_usuario[0]['id'];
$nome_completo = $get_usuario[0]['nome_completo'];
$cpf_candidato = $get_usuario[0]['cpf'];
$nome_guerra = $get_usuario[0]['nome_guerra'];
$posto_grad = $get_usuario[0]['posto_grad'];
$candidato = $get_usuario[0]['candidato'];

if ($cpf_usuario != $cpf_candidato) {
    $conexao = null;
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=52134565346");
    exit();
}

if ($candidato != 0 && $candidato != 1) {
    $conexao = null;
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=66456436");
    exit();
}

if ($candidato == 1) {
    if ($nome_completo == null) {
        $conexao = null;
        header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=789746654");
        exit();
    } else $nome = $nome_completo;
}
if ($candidato == 0) {
    if ($nome_guerra == null || $posto_grad == null) {
        $conexao = null;
        header("Location: ../esqueceu_senha.php?erro=nao_encontrado&erro_master=856165");
        exit();
    } else $nome = $posto_grad . " " . $nome_guerra;
}

if ($id_usuario == null) {
    $conexao = null;
    header("Location: ../esqueceu_senha.php?erro=nao_encontrado");
    exit();
}

$resultado = $conexao->esqueci_reseta_senha($id_usuario, $cpf_usuario, $nova_senha_crip);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado) {

    $_SESSION['id_usuario'] = $id_usuario;

    if ($candidato == 0)
        $insere_log = $conexao->insere_log($id_usuario, $cpf_usuario, $id_usuario, "16109", "usuario", "Update", "Usuário $nome esqueceu e resetou a senha CPF: $cpf_usuario", $alteracoes_detalhadas);

    if ($candidato == 1)
        $insere_log = $conexao->insere_log($id_usuario, $cpf_usuario, $id_usuario, "16109", "usuario", "Update", "Candidato $nome esqueceu e resetou a senha CPF: $cpf_usuario", $alteracoes_detalhadas);

    //  $foi_enviado_email = null;

    //var_dump($mail_usuario); exit;

    if ($mail_usuario != null)
        //    $enviar->enviarEmailComSwaks($mail_usuario, $assunto, $mensagem, $smtpServer);
        include_once './mail_resetar_senha.php';
    header("Location: ../esqueceu_senha.php?senha_alterada=1&email=".$mail_usuario."");

    /*
    $conexao = null;
    $_SESSION['id_usuario'] = null;
    if($foi_enviado_email)
        header ("Location: ../esqueceu_senha.php?senha_alterada=1");
    else
        header ("Location: ../esqueceu_senha.php?senha_alterada=0");

        */
}
