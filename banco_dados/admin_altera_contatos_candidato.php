<?php
// 14 MAIO 2024 

include_once 'conexao.php';
include_once '../sistema/funcoes.php';

session_start();

$conexao = new Conexao();

// ------- VALIDAÇÕES DE SESSÃO -------
if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    erro("Erro 2456534263!");
    exit();
}

// somente admin pode trocar contatos
if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 23445263!");
    exit();
}

// impede acesso de candidato (redundante mas ok)
if ($_SESSION['perfil'] == 'candidato' || ($_SESSION['candidato'] ?? 0) == 1) {
    erro("Erro 5345263!");
    exit();
}

// valida assinatura criptográfica
if ($_POST['criptografia'] != hash('sha256', $_SESSION['assinatura_sistema'])) {
    erro("Erro 234263!");
    exit();
}


// ------- RECEBE DADOS -------
$id_usuario      = $_POST['id_usuario'] ?? null;
$admin_password  = hash('sha256', $_POST['password'] ?? '');
$id_admin        = $_SESSION['id_usuario'];


// valida campos essenciais
if (!$id_usuario || !$admin_password) {
    erro("Erro 64263!");
    exit();
}


// ------- CARREGA O USUÁRIO A SER ALTERADO -------
$get_candidato = $conexao->get_usuario_id($id_usuario);

// garante exatamente 1 resultado
if (!$get_candidato || count($get_candidato) != 1) {
    erro("Erro wrew64263!");
    exit();
}

$dados_candidato = $get_candidato[0];


// ------- DEFINE VALORES NOVOS OU MANTÉM ANTIGOS -------
$novo_email        = empty($_POST['novo_email']) ? $dados_candidato['mail'] : $_POST['novo_email'];
$novo_residencial  = empty($_POST['novo_tel_residencial']) ? $dados_candidato['tel_residencial'] : $_POST['novo_tel_residencial'];
$novo_celular      = empty($_POST['novo_tel_celular']) ? $dados_candidato['tel_celular'] : $_POST['novo_tel_celular'];


// ------- ALTERA CONTATOS -------
$resultado = $conexao->altera_contatos_candidato(
    $id_usuario,
    $novo_email,
    $novo_residencial,
    $novo_celular,
    $id_admin,
    $admin_password
);


// ------- PROCESSA RESULTADO -------
if ($resultado) {

    $alteracoes_detalhadas = print_r($resultado, true);

    // registra no log
    $conexao->insere_log(
        $_SESSION['id_usuario'],
        $_SESSION['cpf'],
        "$id_usuario",
        "161508",
        "candidato",
        "Update",
        "Alterou o email do candidato " . $dados_candidato['cpf'] . " para " . $novo_email,
        $alteracoes_detalhadas
    );
} else {
    $conexao = null;
    erro("Erro 4582345344 Email não alterado!");
    exit();
}


// ------- REDIRECIONAMENTO -------
if (isset($_POST['medico_obrigatorio'])) {
    header("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_usuario#alterar_contatos");
} else {
    header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#alterar_contatos");
}

exit();
