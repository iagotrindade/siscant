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

if ($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador') {
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

$id_candidato = $_POST['id_candidato'];
$id_especialidade = trim($_POST['id_especialidade']);
$id_usuario_avaliador = $_POST['id_usuario_avaliador'];
$checklist = $_POST['checklist'];

$candidato = $conexao->get_usuario_id($id_candidato);
$avaliador = $conexao->get_usuario_id($id_usuario_avaliador);

if ($id_candidato == null || $id_especialidade == null || $id_usuario_avaliador == null || $checklist == null) {
    erro("Erro 184814! Dados incompletos.");
    exit();
}

// Processar o checklist
if ($_POST) {
    $resultados = [];
    $pelo_menos_um_processado = false;

    // A estrutura é: checklist[id_especialidade][tipo][id_documento][dados]
    foreach ($checklist as $especialidade_id => $tipos_documentos) {
        // Verificar se esta especialidade corresponde à que estamos processando
        if ($especialidade_id != $id_especialidade) {
            continue;
        }

        foreach ($tipos_documentos as $tipo_documento => $documentos) {
            foreach ($documentos as $id_documento => $dados) {
                // NOVA LÓGICA: Se não existe 'status', significa checkbox DESMARCADO
                if (!array_key_exists('status', $dados)) {
                    $entregue = 0; // Checkbox desmarcado
                } else {
                    $entregue = ($dados['status'] == '1') ? 1 : 0;
                }

                $id_documento_val = isset($dados['id_documento']) ? $dados['id_documento'] : $id_documento;
                $tipo_documento_val = isset($dados['tipo_documento']) ? $dados['tipo_documento'] : $tipo_documento;
                $id_checklist_existente = isset($dados['id_checklist']) ? $dados['id_checklist'] : null;

                echo "Processando: ID Doc: $id_documento_val, Tipo: $tipo_documento_val, Entregue: $entregue<br>";

                $resultado = $conexao->atualiza_checklist_documentos(
                    $id_candidato,
                    $id_especialidade,
                    $id_usuario_avaliador,
                    $id_documento_val,
                    $tipo_documento_val,
                    $entregue,
                    $id_checklist_existente
                );

                if ($resultado) {
                    $pelo_menos_um_processado = true;
                }
                $resultados[] = $resultado;
            }
        }
    }

    // Modificar a verificação de sucesso
    $sucesso = $pelo_menos_um_processado;
}

$alteracoes_detalhadas = "Checklist atualizado para candidato $id_candidato na especialidade $id_especialidade";

// Verificar se pelo menos uma operação foi bem sucedida
$sucesso = !empty($resultados) && in_array(true, $resultados, true);

if ($sucesso) {
    $insere_log = $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        $id_candidato,
        "22115",
        "Checklist Documentos",
        "Update",
        "O avaliador de CPF " . $avaliador[0]['cpf'] . " atualizou checklist de documentos do candidato " . $candidato[0]['nome_completo'] . ".",
        "$alteracoes_detalhadas"
    );

    // Redirecionar de volta para a página do candidato
    header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato&sucesso_checklist=1#checklist_documentos");
    exit();
} else {
    $conexao = null;
    erro("Erro 4564! Checklist não foi atualizado.");
    exit();
}
