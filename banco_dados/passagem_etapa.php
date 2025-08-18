<?php

include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 2342344!");
    exit();
}

if (!isset($_SESSION['selecao']) || !isset($_SESSION['chave'])) {
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 5673453454! Você não tem permissão!");
    exit();
}

if (inscricao()) {
    erro("Erro 24574! Inscrição em andamento!");
    exit();
}

$criptografia = $_POST['crip'];
if ($criptografia != hash('sha256', $_SESSION['chave'] . "freitas")) {
    erro("Erro 8445324574! Não foi possível executar o arquivo!");
    exit();
}

$etapa = (int)$_POST['etapa'];
if (empty($etapa)) {
    erro("Erro 52354! Você deve escolher um script a ser executado!");
    exit();
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$etapa_selecao = $get_selecao[0]['etapa'];

if ($etapa == 2) {
    $lista_candidatos = $conexao->get_candidatos_concorrendo();
    $usuarios_passaram_etapa2 = ["Lista de CPFs passaram etapa 2"];
    $candidatos_processados = [];

    foreach ($lista_candidatos as &$candidato) {
        $candidato['especialidades'] = $conexao->get_especialidade_candidato($candidato['id']);

        if (!in_array($candidato['id'], $candidatos_processados)) {
            $resultadoEtapaCandidato = $conexao->altera_etapa_candidato($candidato['id'], $etapa);

            if ($resultadoEtapaCandidato) {
                $usuarios_passaram_etapa2[] = $candidato['cpf'];

                // Observação geral
                $obs = "Cod: 95471. Candidato passou para etapa II!";
                $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $obs, 1);
                $alteracoes_detalhadas = print_r($resultado, true);

                if ($resultado) {
                    $conexao->insere_log(
                        $_SESSION['id_usuario'],
                        $candidato['cpf'],
                        $candidato['id'],
                        "14122",
                        "usuario",
                        "Insert",
                        "Observação adicionada, Candidato passou para ETAPA II",
                        $alteracoes_detalhadas
                    );
                }
            }

            $candidatos_processados[] = $candidato['id'];
        }

        // Processa cada especialidade
        foreach ($candidato['especialidades'] as $especialidade) {
            if ((int)$especialidade['concorrendo'] === 1 && (int)$especialidade['apagado'] === 0) {
                $resultadoEspecialidade = $conexao->altera_etapa_especialidade($candidato['id'], $especialidade['id_especialidade'], $etapa);

                if ($resultadoEspecialidade) {
                    $obsEspecialidade = "Cod: 95471. Candidato passou para etapa II na especialidade " . $especialidade['especialidade'] . "!";
                    $resultado = $conexao->cadastra_observacao_candidato($candidato['id'], $obsEspecialidade, 1);
                    $alteracoes_detalhadas_especialidade = print_r($resultado, true);

                    if ($resultado) {
                        $conexao->insere_log(
                            $_SESSION['id_usuario'],
                            $candidato['cpf'],
                            $candidato['id'],
                            "14122",
                            "usuario",
                            "Insert",
                            "Observação adicionada, Candidato passou para ETAPA II na especialidade " . $especialidade['especialidade'] . "!",
                            $alteracoes_detalhadas_especialidade
                        );
                    }
                }
            }
        }
    }
}

// Atualiza a etapa geral da seleção
$resultadoEtapaSelecao = $conexao->altera_etapa_selecao($etapa);
if ($resultadoEtapaSelecao) {
    $_SESSION['etapa_selecao'] = $etapa;

    $usuarios_log = isset($usuarios_passaram_etapa2)
        ? print_r($usuarios_passaram_etapa2, true)
        : "Etapa geral atualizada para $etapa";

    $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $_SESSION['id_usuario'],
        "16124",
        "usuario",
        "Update",
        "Passou a seleção para a ETAPA: $etapa",
        $usuarios_log
    );

    $conexao = null;
    header("Location: ../sistema/etapa_passagem.php?sucesso=1");
    exit();
}

header("Location: ../sistema/etapa_passagem.php?sucesso=0");
