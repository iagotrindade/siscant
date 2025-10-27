<?php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if (!$_POST) {
    erro_mensagem("Erro 5623444!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    $conexao = null;
    erro("Erro 403924! Não foi possível atualizar as datas da inscrição");
    exit();
}

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Erro 47862924! Não foi possível atualizar as datas da inscrição");
    exit();
}

if ($_POST['crip'] != hash('sha256', $_SESSION['chave'] . "freitas")) {
    $conexao = null;
    erro("Erro 474! Não foi possível atualizar as datas da inscrição");
    exit();
}

$id_selecao = trim($_POST['cod']);
if ($id_selecao != $_SESSION['selecao']) {
    $conexao = null;
    erro("Erro 403924! Não foi possível atualizar as datas da inscrição");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    $conexao = null;
    erro("Erro 40323423924! Não foi possível atualizar as datas da inscrição");
    exit();
}

$smpt = trim($_POST['smtp']);
$imap = trim($_POST['imap']);
$porta_smtp = trim($_POST['porta_smtp']);
$porta_imap = trim($_POST['porta_imap']);
$usuario_email = trim($_POST['usuario_email']);
$senha_email = trim($_POST['senha_email']);


if ($smpt == null || $imap == null || $porta_smtp == null || $porta_imap == null || $usuario_email == null || $senha_email == null) {
    $conexao = null;
    erro("Erro 3234224! Todos os dados são obrigatórios!");
    exit();
}


$resultado_selecao = $conexao->get_selecao_id();

$nome_selecao = $resultado_selecao[0]['nome'] . " - " . $resultado_selecao[0]['ano'];

if ($_POST)
    $resultado = $conexao->selecao_atualiza_dados_email($smpt, $imap, $porta_smtp, $porta_imap, $usuario_email, $senha_email, $id_selecao);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "16108", "selecao", "Update", "Atualizou as datas da inscrição para: $ini até $fim", "$alteracoes_detalhadas");
else {
    $conexao = null;
    erro("Erro 4534534584 Datas não atualizadas!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?datas_atualizadas=1");
exit();
