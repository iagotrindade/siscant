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

$id = $_POST['id'];
$pergunta = trim($_POST['pergunta']);
$resposta = $_POST['resposta'];

if ($pergunta == null || $resposta == "") {
    erro("Os campos Pergunta e Resposta são obrigatórios!");
    exit();
}

if ($_POST) {
    $resultado = $conexao->edita_pergunta_resposta($id, $pergunta, $resposta, $usuario_logado[0]['id']);
}

$alteracoes_detalhadas =  print_r($resultado);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22102",
        "Assistente Virtual",
        "Update",
        "Editou a pergunta: $pergunta e resposta: $resposta",
        "$alteracoes_detalhadas"
    );
else {
    $conexao = null;
    erro("Erro 4564 Pergunta e Resposta não atualizadas!");
    exit();
}

header("Location: ../sistema/atualizar_assistente_virtual.php?id_pergunta=$id");
exit();
