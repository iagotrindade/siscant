<?php

include_once '../sistema/funcoes.php';
session_start();

if (!$_POST) {
    erro_mensagem("Erro 2342344!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    erro_mensagem("Erro 24148, não foi possível fazer o login no sistema!");
    exit();
}

if (!isset($_POST['usuario']) || !isset($_POST['senha'])) {
    erro_mensagem("Erro 5415! O campo usuário e senha são obrigatórios!");
    exit();
}

$usuario = $_POST["usuario"];
$senha   = $_POST["senha"];

$usuario = $usuario = str_replace('\'', '', $usuario);
$usuario = $usuario = str_replace('"', '', $usuario);
$usuario = $usuario = str_replace('=', '', $usuario);
$usuario = $usuario = str_replace(' ', '', $usuario);
$usuario = addslashes($usuario);

$senha = $senha = str_replace('\'', '', $senha);
$senha = $senha = str_replace('"', '', $senha);
$senha = $senha = str_replace('=', '', $senha);
$senha = $senha = str_replace(' ', '', $senha);
$senha = addslashes($senha);

if ($senha == null || $senha == '' || $_POST["senha"] == null || $_POST["senha"] == '') {
    erro_mensagem("Erro 2352355! Usuário não encontrado");
    exit();
}
if ($usuario == null || $usuario == '' || $_POST["usuario"] == null || $_POST["usuario"] == '') {
    erro_mensagem("Erro 457456456! Usuário não encontrado");
    exit();
}

if (!is_numeric($usuario)) {
    erro_mensagem("Erro 1479! O campo usuário é somente números!!!");
    exit();
}

include_once 'conexao.php';
$conexao = new Conexao();

$_SESSION['ip_login'] = $_SERVER['REMOTE_ADDR'];
$ip = $_SESSION['ip_login'];
$datetime = date('Y-m-d H:i:s');

if ($ip != null) {
    $quantidade_logins = $conexao->quantidades_tentativas_login($ip);
    if (count($quantidade_logins) >= 80) {
        erro_mensagem("SEU IP FOI BLOQUEADO! O limite de 80 tentativas de login foi atingido! Tente novamente no próximo dia!");
        exit();
    }
} else {
    erro_mensagem("Erro 4564! Não foi possível fazer o seu login!");
    exit();
}


$senha = hash('sha256', $senha);

//echo $senha;
//exit();

if ($_POST)
    $resultado = $conexao->login($usuario, $senha, $_SESSION['selecao']);

if (count($resultado) == 1) {
    $_SESSION['id_usuario'] = $resultado[0]['id'];
    $_SESSION['cpf'] = $resultado[0]['cpf'];
    $_SESSION['perfil'] = $resultado[0]['perfil'];
    $_SESSION['apagado'] = $resultado[0]['apagado'];
    $_SESSION['trocar_senha'] = $resultado[0]['trocar_senha'];
    $_SESSION['nome_completo'] = $resultado[0]['nome_completo'];
    $_SESSION['assinatura_sistema'] = $resultado[0]['assinatura_sistema'];
    $_SESSION['senha'] = $resultado[0]['senha'];
    $_SESSION['candidato'] = $resultado[0]['candidato'];
    $_SESSION['concorrendo'] = $resultado[0]['concorrendo'];
    $_SESSION['candidato_etapa'] = $resultado[0]['etapa'];

    $_SESSION['medico_obrigatorio'] = $resultado[0]['medico_obrigatorio'];

    $_SESSION['usuario_foto'] = "user.jpg";

    $foto_usuario = $conexao->get_foto_usuario($resultado[0]['id']);
    if (count($foto_usuario) > 0)
        $_SESSION['usuario_foto'] = $foto_usuario[0]["nome"];

    $alteracao = "Usuário " . $resultado[0]['cpf'] . " fez login";
    $insere_log = null;

    $resultado_selecao = $conexao->get_selecao_id();

    if ($resultado_selecao[0]['id'] == null) {
        erro_mensagem("Erro 4891479! Não foi possível fazer o login!!!");
        exit();
    }

    $_SESSION['selecao_codigo'] = $resultado_selecao[0]['codigo'];
    $_SESSION['selecao_ano'] = $resultado_selecao[0]['ano'];
    $_SESSION['selecao_regiao'] = $resultado_selecao[0]['rm'];
    $_SESSION['selecao_nome'] = $resultado_selecao[0]['nome'];

    $_SESSION['selecao_pagamento'] = false;
    if ($resultado_selecao[0]['pagamento'] != null && $resultado_selecao[0]['pagamento'] == '1')
        $_SESSION['selecao_pagamento'] = true;

    $_SESSION['etapa_selecao'] = $resultado_selecao[0]['etapa'];
    $_SESSION['liberacao_prioridade_candidato_selecao'] = $resultado_selecao[0]['liberacao_prioridade_candidato'];

    $_SESSION['selecao_data_inicial_inscricao'] = $resultado_selecao[0]['data_inicio_inscricao'];
    $_SESSION['selecao_data_final_inscricao'] = $resultado_selecao[0]['data_fim_inscricao'];

    $_SESSION['selecao_data_inicial_avaliacao'] = $resultado_selecao[0]['data_inicio_avaliacao'];
    $_SESSION['selecao_data_final_avaliacao'] = $resultado_selecao[0]['data_fim_avaliacao'];

    $_SESSION['selecao_data_inicial_isencao'] = $resultado_selecao[0]['data_inicio_isencao'];
    $_SESSION['selecao_data_final_isencao'] = $resultado_selecao[0]['data_fim_isencao'];

    $_SESSION['selecao_data_inicial_cidade'] = $resultado_selecao[0]['data_inicio_cidade'];
    $_SESSION['selecao_data_final_cidade'] = $resultado_selecao[0]['data_fim_cidade'];

    $_SESSION['data_inicio_recurso'] = $resultado_selecao[0]['data_inicio_recurso'];
    $_SESSION['data_fim_recurso'] = $resultado_selecao[0]['data_fim_recurso'];

    $_SESSION['cabecalho_relatorio'] = null;

    if ($_SESSION['selecao_regiao'] == 3)
        $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
            EXÉRCITO BRASILEIRO<br>
            COMANDO MILITAR DO SUL<br>
            COMANDO DA 3ª REGIÃO MILITAR<br>
            (Gov das Armas Prov do RS/1821)<br>
            REGIÃO DOM DIOGO DE SOUZA<br>';

    if ($_SESSION['selecao_regiao'] == 8)
        $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
            EXÉRCITO BRASILEIRO<br>
            COMANDO MILITAR DO SUL<br>
            COMANDO DA 8ª REGIÃO MILITAR<br>
            (Gov das Armas Prov do PA/1821)<br>
            REGIÃO FORTE DO PRESÉPIO<br>';

    if ($_SESSION['selecao_regiao'] == 6)
        $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
            EXÉRCITO BRASILEIRO<br>
            COMANDO DA 6ª REGIÃO MILITAR<br>
            (Governo das Armas Província da Bahia/1821)<br>
            REGIÃO MARECHAL CANTUÁRIA<br>';

    if ($_SESSION['selecao_regiao'] == 12)
        $_SESSION['cabecalho_relatorio'] = 'MINISTÉRIO DA DEFESA<br>
            EXÉRCITO BRASILEIRO<br>
            COMANDO MILITAR DA 12ª REGIÃO MILITAR<br>
             (Comando de Elementos de Fronteira/1948)<br>
            (FORTE MENDONÇA FURTADO)<br>';

    $insere_log = $conexao->insere_log($resultado[0]['id'], $resultado[0]['cpf'], null, "14103", "log", "Login", $alteracao, null);

    $conexao = null;

    if ($resultado_selecao[0]['encerrada'] == 1 && $resultado[0]['perfil'] != 'admin') {
        session_destroy();
        $conexao = null;
        echo "Seleção encerrada!";
        exit();
    }

    if (($resultado[0]['perfil'] == 'avaliador' || $resultado[0]['perfil'] == 'documentos')  && !avaliacao()) {
        /*
        session_destroy();
        $conexao = null;
        echo "Avaliação não disponível!";
        exit();
         * 
         */
    }

    //var_dump(avaliacao());
    //exit();


    if ($resultado[0]['trocar_senha'] == 1) {
        $u = hash('sha256', $resultado[0]['cpf']);
        header("Location: ../alterar_senha.php?u=$u");
    } else
        header("Location: ../sistema/index.php");
} else {
    $pagina = "siscant/index.php";
    if (isset($_POST["pagina_acessada"])) {
        $pagina = substr($_POST["pagina_acessada"], 1);
    }

    $insere_tentativa = $conexao->tentativa_login($usuario, $senha, $_SESSION['selecao']);
    header("Location: ../../$pagina?usuario_senha=invalido");
    $conexao = null;
    exit();
}
