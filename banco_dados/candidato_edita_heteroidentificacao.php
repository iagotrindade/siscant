<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

$conexao = new Conexao();

if (!$_POST) {
    erro_mensagem("Erro 326264567!");
    exit();
}

if (!isset($_SESSION['selecao'])) {
    $conexao = null;
    erro("Erro 2346456456! Não foi possível salvar a avaliação");
    exit();
}

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'chc' && $_SESSION['perfil'] != 'cr' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Erro 3464457423! Não foi possível salvar a avaliação");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    $conexao = null;
    erro("Erro 2346236457! Não foi possível salvar a avaliação");
    exit();
}

//Trata o POST recebido
$id_parecer = $_POST['id_parecer'];
$id_avaliador = $_POST['id_avaliador'];
$parecer = $_POST['parecer'];
$justificativa = $_POST['justificativa'];
$fase = $_POST['fase'];
$id_candidato = $_POST['id_candidato'];
$cpf_candidato = $_POST['cpf_candidato'];

$pareceres = $conexao->get_pareceres_heteroidentificacao($id_candidato);

// Define o limite por fase
$limitesPorFase = [
    1 => 5,
    2 => 3,
];

// Garante que a fase seja numérica
$fase = (int) $fase;

// Verifica se a fase está entre as permitidas
if (!isset($limitesPorFase[$fase])) {
    $conexao = null;
    erro("Fase inválida para avaliação.");
    exit();
}

// Verificar se a análise foi feita pelo usuario logado


if ($_SESSION['id_usuario'] == $id_avaliador && $parecer && $justificativa && $fase) {
    $resultado = $conexao->editar_parecer_heteroidentificacao($id_candidato, $parecer, $justificativa, $id_parecer);

    if ($resultado) {
        $alteracoes_detalhadas =  print_r($resultado, true);

        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "161511", "heteroidentificacao", "Update", "Avaliador " . $_SESSION['cpf'] . " Alterou o parecer de Heteroidentificação do candidato $cpf_candidato", $alteracoes_detalhadas);

        header("Location: ../sistema/usuario_visualiza.php?id_usuario=" . $id_candidato . "#heteroidentificacao");
    } else {
        $conexao = null;
        erro("Erro 4545584 heteroidentificação não cadastrada!");
        exit();
    }
} else {
    $conexao = null;
    erro("Erro 2346236457! Não foi possível salvar a avaliação");
    exit();
}
