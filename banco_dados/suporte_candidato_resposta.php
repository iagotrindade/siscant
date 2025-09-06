<?php
include_once '../sistema/funcoes.php';

$resposta = null;
$id_suporte = null;

if (!$_POST) {
    erro_mensagem("Erro 234234!");
    exit();
}

if ($_POST['resposta'] == "") {
    erro("O campo resposta é obrigatório!");
    exit();
}
if ($_POST['id_suporte'] == "") {
    erro("Erro: 456465 Algo errado não está certo!");
    exit();
}

if ($_POST['resposta'] != "")
    $resposta = htmlspecialchars(trim($_POST['resposta']));

if ($_POST['id_suporte'] != "")
    $id_suporte = $_POST['id_suporte'];

if ($resposta == "" || $resposta == null) {
    erro("Erro: 3254! O campo resposta é obrigatório");
    exit();
}


include_once 'conexao.php';
session_start();
$datetime = date('Y-m-d H:i:s');

$conexao = new Conexao();

if (!isset($_SESSION['id_usuario'])) {
    erro("Erro 15235! A sua sessão expirou! Faça novamente o registro");
    exit();
}
if (!isset($_SESSION['cpf'])) {
    erro("Erro 15123645! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}
if ($_SESSION['candidato'] == 1) {
    erro("Erro 4846556! Somente candidatos podem realizar esse cadastro");
    exit();
}

if ($_SESSION['perfil'] != "admin" && $_SESSION['perfil'] != "ouvidor") {
    erro("Erro 4846! Você não tem permissão para responder este suporte!");
    exit();
}

$resultado_suporte = $conexao->get_suporte_candidato_id($id_suporte);

$cpf_requerente = $resultado_suporte[0]['cpf'];
$mail = $resultado_suporte[0]['mail_usuario'];
$nome_completo_requerente = $resultado_suporte[0]['nome_completo'];
$data_enviado_requerente = $resultado_suporte[0]['data_enviado'];
$mensagem_requerente = $resultado_suporte[0]['mensagem'];

$lista_suporte = $conexao->get_suporte_candidato($resultado_suporte[0]['id_usuario_remetente']);

// 03/09/2025 -> Iago Silva marcando todos os suportes como lindos.
if ($id_suporte != null && $resposta != null) {
    if ($_POST)
        $resultado = $conexao->insere_resposta_candidato($id_suporte, $resposta, $_SESSION['id_usuario']);
    $alteracoes_detalhadas =  print_r($resultado, true);
    if ($resultado) {
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "16104", "suporte_candidato", "Update", "Respondeu para o candidato CPF: $cpf_requerente o ID suporte: $id_suporte", $alteracoes_detalhadas);
        if ($mail != null)
            include_once './mail_resposta_suporte.php';
    }
    foreach ($lista_suporte as $suporte) {
        if ($suporte['id'] != $id_suporte && $suporte['id_usuario_respondeu'] != $_SESSION['id_usuario'] && empty($suporte['resposta'])) {
            $resultado = $conexao->insere_resposta_candidato($suporte['id'], '', $suporte['id_usuario_respondeu']);
        }
    }

    $conexao = null;
    header("Location: ../sistema/suporte_candidato_visualiza.php?id_suporte=$id_suporte");
}
