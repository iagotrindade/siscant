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


$lista_cidades_editar = array();
if (isset($_POST['cidades']))
    $lista_cidades_editar = $_POST['cidades'];

include_once 'conexao.php';

$conexao = new Conexao();

$crip = $_POST['crip'];
$id_especialidade = $_POST['id_especialidade'];

$especialidade_editar = $conexao->get_especialidade_id($id_especialidade);

if (hash('sha256', $_SESSION['assinatura_sistema']) != $crip) {
    erro("Erro 3248! Especialidade não alterada!");
    exit();
}

$lista_especialidades = $conexao->get_cidades_especialidade($id_especialidade);

$nova_lista = array();
foreach ($lista_especialidades as &$valor) {
    array_push($nova_lista, $valor['id']);
}

$arrayDiferenca = array_diff($lista_cidades_editar, $nova_lista);
$arrayDiferenca2 = array_diff($nova_lista, $lista_cidades_editar);


//////////////////////////////////////////////////////
// Verifica se existe usuário na seleção
//////////////////////////////////////////////////////

if ($especialidade_editar[0]['apagado'] == 1) {
    erro("Erro 8933234! O especialidade esta apagada! ");
    exit();
}

////////////////////////////////////////////////////////
// Edita o Usuário
//////////////////////////////////////////////////////

if (count($arrayDiferenca) > 0 || count($arrayDiferenca2) > 0) {
    $apaga_especialidades = $conexao->apaga_cidades_especialidade($id_especialidade);
    $resultado2 = $conexao->insere_especialidade_x_cidade($id_especialidade, $lista_cidades_editar);
    $alteracoes_detalhadas2 =  print_r($resultado2, true);
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_especialidade, "16111", "cidade_x_especialidade", "Update", "Atualizou as cidades da especialidade " . $especialidade_editar[0]['nome'], $alteracoes_detalhadas2);

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
