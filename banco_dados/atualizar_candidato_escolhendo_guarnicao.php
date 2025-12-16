<?php
include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 653453444!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 15684! Você não tem permissão!");
    exit();
}

include_once 'conexao.php';

$conexao = new Conexao();

$crip = $_POST['crip'];
$id_especialidade = $_POST['id_especialidade'];
$id_candidato = $_POST['id_candidato'];

$especialidade_editar = $conexao->get_especialidade_id($id_especialidade);

if (hash('sha256', $_SESSION['assinatura_sistema']) != $crip) {
    erro("Erro 3248! Nada foi alterado!");
    exit();
}

if ($especialidade_editar[0]['apagado'] == 1) {
    erro("Erro 8933234! A especialidade esta apagada! ");
    exit();
}

if ($id_especialidade && $id_candidato) {
    $resultado = $conexao->atualiza_candidato_escolhendo($id_especialidade, $id_candidato);
    $alteracoes_detalhadas =  print_r($resultado, true);
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_especialidade, "16111", "cidade_x_especialidade", "Update", "Atualizou as cidades da especialidade " . $especialidade_editar[0]['nome'], $alteracoes_detalhadas);

    $conexao = null;

    // 12/07/2025 -> Iago Silva -> Alteração para redirecionar para a página de edição da especialidade de acordo com a Seleção
    if (isset($_SESSION['eipot'])) {
        header("Location: ../sistema/especialidade_eipot_editar.php?id_especialidade=$id_especialidade&sucesso=1");
    } else {
        header("Location: ../sistema/especialidade_editar.php?id_especialidade=$id_especialidade&sucesso=1");
    }
} else {
    $conexao = null;
    if (isset($_SESSION['eipot'])) {
        header("Location: ../sistema/especialidade_eipot_editar.php?id_especialidade=$id_especialidade&sucesso=1");
    } else {
        header("Location: ../sistema/especialidade_editar.php?id_especialidade=$id_especialidade&sucesso=1");
    }
}
