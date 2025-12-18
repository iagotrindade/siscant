<?php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if (!$_POST) {
    erro_mensagem("Erro 236346346!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    $conexao = null;
    erro("Erro 4326436456! Não foi possível atualizar as datas");
    exit();
}

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Erro 2643246346! Não foi possível atualizar as datas");
    exit();
}

if ($_POST['crip'] != hash('sha256', $_SESSION['chave'] . "freitas")) {
    $conexao = null;
    erro("Erro 24747457! Não foi possível atualizar as datas");
    exit();
}

$id_selecao = trim($_POST['cod']);
if ($id_selecao != $_SESSION['selecao']) {
    $conexao = null;
    erro("Erro 4567457457! Não foi possível atualizar as datas");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    $conexao = null;
    erro("Erro 643743745! Não foi possível atualizar as datas da inscrição");
    exit();
}

$data_inicio_cidade = trim($_POST['data_inicio_cidade']);
$data_fim_cidade = trim($_POST['data_fim_cidade']);
$especialidades_liberadas = $_POST['especialidades_liberadas'];

if ($data_inicio_cidade == null || $data_fim_cidade == null) {
    $conexao = null;
    erro("Erro 46454567! A data de Início e Final são obrigatórias!");
    exit();
}

if ($data_inicio_cidade == $data_fim_cidade) {
    $conexao = null;
    erro("Erro 4224! As datas não podem ser iguais!");
    exit();
}

if (!valida_data($data_inicio_cidade)) {
    $conexao = null;
    erro("Erro 346324634! Data de INÍCIO inválida!");
    exit();
}

if (!valida_data($data_fim_cidade)) {
    $conexao = null;
    erro("Erro 34234343! Data FINAL inválida!");
    exit();
}

$ini = $data_inicio_cidade;
$fim = $data_fim_cidade;

$data_inicio_cidade = reverte_data($data_inicio_cidade);
$data_fim_cidade    = reverte_data($data_fim_cidade);


if (strtotime($data_inicio_cidade) >= strtotime($data_fim_cidade)) {
    $conexao = null;
    erro("Erro 5345345! A data de início tem que ser menor do que a data final.");
    exit();
}

$lista_especialidades = $conexao->get_especialidade();

$nova_lista = array();
foreach ($lista_especialidades as &$valor) {
    array_push($nova_lista, $valor['id']);
}

if ($_POST)
$resultado_reset = $conexao->reseta_especialidades_escolha_guarnicao();
$resultado_especialidade = $conexao->insere_especialidades_guarnicao($especialidades_liberadas);

$resultado = $conexao->selecao_atualiza_data_cidade($data_inicio_cidade, $data_fim_cidade);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "161502", "selecao", "Update", "Atualizou as datas e as Especialidades para dar permissão para o candidato selecionar a cidade onde quer servir para: $ini até $fim", "$alteracoes_detalhadas");
else {
    $conexao = null;
    erro("Erro 378568568 Datas não atualizadas!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?datas_atualizadas=1");
exit();
