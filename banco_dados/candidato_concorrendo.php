<?php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if (!$_POST) {
    erro_mensagem("Erro 236784!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    erro("Erro 24578963!");
    exit();
}

if (($_SESSION['perfil'] != 'admin') || $_SESSION['candidato'] == '1') {
    erro("Erro 235475673! Permissão Negada!");
    exit();
}

if ($_POST['criptografia'] != hash('sha256', $_SESSION['chave'] . "freitas")) {
    erro("Erro 234456456263!");
    exit();
}

$id_usuario = $_POST['id_usuario'];
$observacao = trim($_POST['justificativa']);
$concorrendo = 0;

if (isset($_POST['concorrendo']))
    $concorrendo = 1;

if ($id_usuario == "" || $id_usuario == null || $observacao == "" || $observacao == null) {
    erro("Erro A Justificativa é obrigatória!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_usuario);

if (count($get_candidato) != 1) {
    erro("Erro 64263!");
    exit();
}

if ($get_candidato[0]['concorrendo'] == $concorrendo) {
    erro("Erro 346456! O status não foi alterado!");
    exit();
}

$candidato_concorrendo = "CONCORRENDO";
if ($concorrendo == 0)
    $candidato_concorrendo = "DESCLASSIFICADO";

// Muda Status concorrendo especialidade
$get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);
$esta_concorrendo_em_outa_especialidade = false;
foreach ($get_especialidade_candidato as &$especialidade) {
    if ($especialidade['concorrendo'] == 1) {
        $esta_concorrendo_em_outa_especialidade = true;

        $conexao->status_concorrendo_especialidade($especialidade['id_candidato_x_especialidade'], $concorrendo, $observacao);
    }
}
if ($esta_concorrendo_em_outa_especialidade == false && !isset($_SESSION['eipot'])) {
    erro("Erro 2358923085! O candidato para participar do processo deve estar concorrendo em pelo menos uma especialidade!");
    exit();
}

// Muda Status concorrendo no Processo
$resultado_concorrendo = $conexao->status_concorrendo($id_usuario, $concorrendo, $observacao);
$alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
if ($resultado_concorrendo)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16116", "usuario", "Update", "Alterou o status do candidato " . $get_candidato[0]['cpf'] . " para $candidato_concorrendo no processo seletivo", "$alteracoes_detalhadas");
else {
    $conexao = null;
    erro("Erro 423345634 Não mudou o status!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#concorrendo");
exit();
