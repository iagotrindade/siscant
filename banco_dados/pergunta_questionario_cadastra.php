<?php

include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 2345344!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    header("Location: ../index.php?erro=435154");
    exit();
}

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1) {
    header("Location: ../index.php?erro=0545154");
    exit();
}

if ($_POST['criptografia'] != hash('sha256', $_SESSION['assinatura_sistema'])) {
    header("Location: ../index.php?erro=18484");
    exit();
}

include_once 'conexao.php';

$conexao = new Conexao();

$usuario_logado = $conexao->get_usuario_cpf($_SESSION['cpf']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    header("Location: ../index.php?erro=184814");
    exit();
}

$pergunta = trim($_POST['pergunta']);
$tipo_campo = $_POST['tipo_campo'];
$ativo = $_POST['ativo'];


if ($pergunta == null || $tipo_campo == "" || $ativo == "") {
    erro("Os campos Pergunta e Tipo de Campo e Ativo são obrigatórios!");
    exit();
}

if ($_POST) {
    $resultado = $conexao->insere_pergunta_questionario($pergunta, $tipo_campo, $ativo, $usuario_logado[0]['id']);
}

$alteracoes_detalhadas =  print_r($resultado);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22107",
        "Questionário Inscrição",
        "Insert",
        "Inseriu no questionário da inscrição pergunta: $pergunta",
        "$alteracoes_detalhadas"
    );
else {
    $conexao = null;
    erro("Erro 4564 Pergunta e Resposta não cadastradas!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?sucesso=1");
exit();
