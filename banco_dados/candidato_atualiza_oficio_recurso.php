<?php
include_once '../sistema/funcoes.php';

if (!$_POST) {
    erro("Erro 346346346!");
    exit();
}

$id_recurso = null;
if ($_POST['id_recurso'] != "")
    $id_recurso = (int)htmlspecialchars(trim($_POST['id_recurso']));

$cpf_candidato = null;
if ($_POST['cpf_candidato'] != "")
    $cpf_candidato = htmlspecialchars(trim($_POST['cpf_candidato']));

$cpf_candidato = $cpf_candidato = str_replace('.', '', $cpf_candidato);
$cpf_candidato = $cpf_candidato = str_replace('-', '', $cpf_candidato);

$id_candidato = null;
if ($_POST['id_candidato'] != "")
    $id_candidato = (int)htmlspecialchars(trim($_POST['id_candidato']));

$cidade_data = null;
if ($_POST['cidade_dt'] != "")
    $cidade_data = htmlspecialchars((trim($_POST['cidade_dt'])));

$presidente = null;
if ($_POST['presidente'] != "")
    $presidente = htmlspecialchars(trim($_POST['presidente']));

if ($_POST['obs_etapa'] != "")
    $obs_etapa = htmlspecialchars(trim($_POST['obs_etapa']));

$paragrafo1 = null;
if ($_POST['paragrafo1'] != "")
    $paragrafo1 = htmlspecialchars(trim($_POST['paragrafo1']));

$paragrafo2 = null;
if ($_POST['paragrafo2'] != "")
    $paragrafo2 = htmlspecialchars(trim($_POST['paragrafo2']));

$status_final = null;
if ($_POST['status_final'] != "")
    $status_final = htmlspecialchars(trim($_POST['status_final']));

$especialidade_recurso = null;
if (isset($_POST['especialidade_recurso']))
    $especialidade_recurso = (int)htmlspecialchars(trim($_POST['especialidade_recurso']));

session_start();
include_once 'conexao.php';
$datetime = date('Y-m-d H:i:s');

$conexao = new Conexao();

if (!isset($_SESSION['selecao'])) {
    erro("Erro 345734757! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}


if (!isset($_SESSION['chave'])) {
    erro("Erro 58745857! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    if ($_SESSION['perfil'] != 'jise') {
        erro("Erro 4327347! Não é possivel fazer essa edição!");
        exit();
    }
}


$get_candidato = $conexao->get_usuario_id($id_candidato);
if ($get_candidato[0]['id_selecao'] != $_SESSION['selecao']) {
    erro("Erro 43574374357! Não foi possível fazer a atualização dos dados !");
    exit();
}
if ($get_candidato[0]['cpf'] != $cpf_candidato) {
    erro("Erro 324643646! Não foi possível fazer a atualização dos dados !");
    exit();
}

$get_recurso_id = $conexao->get_recurso_id($id_recurso);
if ($get_recurso_id[0]['id_candidato'] != $id_candidato) {
    erro("Erro 2473478! Não foi possível fazer a atualização dos dados!");
    exit();
}

if ($obs_etapa != '3' && $_SESSION['perfil'] != 'jise') {
    erro("Erro 2473478! Não foi possível fazer a atualização dos dados!");
    exit();
}

$para_avaliador = $get_recurso_id[0]['para_avaliador'];
$id_especialidade = $get_recurso_id[0]['id_especialidade'];
if ($especialidade_recurso != null) {
    $para_avaliador = 1;
    $id_especialidade = $especialidade_recurso;
}

$resultado = $conexao->edita_recurso_oficio(
    $id_recurso,
    $id_candidato,
    $status_final,
    $cidade_data,
    $presidente,
    $paragrafo1,
    $paragrafo2,
    $id_especialidade,
    $para_avaliador
);

$alteracoes_detalhadas =  print_r($resultado, true);
if ($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "16147", "recurso", "Update", "Operador " . $_SESSION['cpf'] . " atualizou dados do ofício recurso ID: $id_recurso do candidato $cpf_candidato", $alteracoes_detalhadas);

$conexao = null;
header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#recursos");
