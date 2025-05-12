<?php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Erro 3246346! Não foi possível atualizar as datas");
    exit();
}

$id_candidato = $_GET['id'] ?? null;
$tipo = $_GET['tipo'] ?? null;

if ($id_candidato) {
    $usuario = $_SESSION['id_usuario'];

    $datetime = date('Y-m-d H:i:s');

    $atas_candidato = $conexao->get_candidato_atas_is($id_candidato);

    $resultado = $conexao->apaga_ata_is_candidato($id_candidato, $datetime, $usuario, $tipo);

    //Deletar os arquivos da pasta arquivos_add_p_cand/atas_is localizada dentro de sistema

    if ($tipo == 'ata_is') {
        $diretorio = "../sistema/arquivos_add_p_cand/atas_is/" . $atas_candidato['ata_is'];
    } elseif ($tipo == 'ata_is_recurso') {
        $diretorio = "../sistema/arquivos_add_p_cand/atas_is/" . $atas_candidato['ata_is_recurso'];
    } else {
        erro("Erro 234234! Tipo de ata inválido!");
        exit();
    }

    if ($resultado) {
        if (file_exists($diretorio)) {
            unlink($diretorio);
        } else {
            erro("Erro 234234! Arquivo não encontrado!");
        }

        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "15114", "ata_is_recurso", "Delete", "Apagou a ata de IS recurso do candidato ID $id_candidato", $atas_candidato[1]);
        $conexao = null;

        header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato");
    };
}
