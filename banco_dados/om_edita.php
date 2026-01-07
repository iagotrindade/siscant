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

$id_om = $_POST['id_om'];
$nome = $_POST['nome'];
$abreviatura = trim($_POST['abreviatura']);
$rm = $_POST['rm'];
$comando_militar_area = $_POST['cma'];
$codom = $_POST['codom'];
$uf = $_POST['uf'];
$cep = $_POST['cep'];
$endereco = $_POST['endereco'];
$telefone = $_POST['telefone'];


if ($nome == null || $abreviatura == null || $rm == null || $comando_militar_area == null || $codom == null || $uf == null || $cep == null || $endereco == null) {
    erro("Todos os campos são obrigatórios!");
    exit();
}
if ($_POST) {
    $resultado = $conexao->edita_om($id_om, $nome, $abreviatura, $rm, $comando_militar_area, $codom, $uf, $cep, $endereco, $telefone);
}

$alteracoes_detalhadas =  print_r($resultado);

if ($resultado)
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $alteracoes_detalhadas['id'],
        "22116",
        "OM Editada",
        "Update",
        "Editou a om: $nome e abreviatura: $abreviatura",
        "$alteracoes_detalhadas"
    );
else {
    $conexao = null;
    erro("Erro 4564 OM não cadastrada!");
    exit();
}

header("Location: ../sistema/om_editar.php?id_om=$id_om&sucesso=1");
exit();
