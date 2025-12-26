<?php

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

// 03/09/2025 -> Iago Silva Aumentando temporariamente o tamanho do upload de arquivos
ini_set('upload_max_filesize', '20M'); // Define o tamanho máximo do arquivo para 20MB
ini_set('post_max_size', '20M');      // Define o tamanho máximo da mensagem POST (geralmente maior que upload_max_filesize)

if (!$_POST) {
    erro_mensagem("Erro 5623444!");
    exit();
}

if (!isset($_SESSION['chave']) || !isset($_SESSION['selecao'])) {
    $conexao = null;
    erro("Erro 403924! Não foi possível atualizar as datas da inscrição");
    exit();
}

if ($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1) {
    $conexao = null;
    erro("Erro 47862924! Não foi possível atualizar as datas da inscrição");
    exit();
}

if ($_POST['crip'] != hash('sha256', $_SESSION['chave'] . "freitas")) {
    $conexao = null;
    erro("Erro 474! Não foi possível atualizar as datas da inscrição");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);

if ($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema']) {
    $conexao = null;
    erro("Erro 40323423924! Não foi possível atualizar as datas da inscrição");
    exit();
}

$arquivo = $_FILES['arquivo'];
$fase = trim($_POST['fase']);

if ($arquivo == null) {
    $conexao = null;
    erro("Erro 3234224! O arquivo é obrigatório!");
    exit();
}

$datetime = date('Y-m-d H:i:s');

try {
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO

    if ($_FILES['arquivo'] && $_FILES['arquivo']['name'] != "") {
        $extensao = null;
        $nome_original = null;

        $codigo_criptografar = rand(1, 10000) . $datetime . "ata_heteroidentificacao_fase_" . $fase;
        $nome_arquivo = hash('sha256', $codigo_criptografar);

        $nome_arquivo = "ATA_HETEROIDENTIFICACAO_FASE_" . $fase . "_" . $nome_arquivo;

        $nome_original = $_FILES['arquivo']['name'];

        // Pasta onde o arquivo vai ser salvo
        $_UP['pasta'] = '../sistema/arquivos/atas_heteroidentificacao/';

        // Tamanho máximo do arquivo (em Bytes)
        $_UP['tamanho'] = 1024 * 1024 * 20;

        // Array com as extensões permitidas
        $_UP['extensoes'] = array('pdf', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'ods', 'png', 'jpg', 'jpeg');

        // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
        $_UP['renomeia'] = true;

        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES['arquivo']['error'] != 0) {
            //erro("Erro 94561! Arquivo não adicionado! " . $_UP['erros'][$_FILES['arquivo']['error']]); 
            erro("Erro 4743734574! Arquivo não adicionado! Selecione o arquivo no botão Browser de tamanho máximo de 2 MB ");
            exit(); // Para a execução do script
        }

        // Faz a verificação da extensão do arquivo
        $extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));

        if (array_search($extensao, $_UP['extensoes']) === false) {
            erro("Erro 234623634! Por favor envie arquivos nos formatos:<br> PDF, DOC, DOCX, XLS, XLSX, ODT, ODS, PNG, JPG ou JPEG");
            //erro("Erro 276437457! Por favor envie arquivos no formato PDF");
            exit();
        }

        // Faz a verificação do tamanho do arquivo
        if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
            erro("Erro 42374357457! O arquivo enviado é muito grande, envie arquivos de até 5 Mb.");
            exit();
        }

        // Primeiro verifica se deve trocar o nome do arquivo
        if ($_UP['renomeia'] == true) {
            // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão
            $nome_arquivo = $nome_arquivo . '.' . $extensao;
        } else {
            // Mantém o nome original do arquivo
            $nome_arquivo = $nome_original . '.' . $extensao;
        }
        // Depois verifica se é possível mover o arquivo para a pasta escolhida

        $tamanho_do_arquivo =  intval($_FILES['arquivo']['size']);

        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) {
            // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
            $resultadoArquivo = true;
        } else {
            // Não foi possível fazer o upload, provavelmente a pasta está incorreta

            $conexao = null;
            erro("Erro: 23642646! Não foi possível fazer o UPLOAD do arquivo!");
            exit();
        }
    }
} catch (Exception $e) {
    $conexao = null;
    erro("Erro 34534534! Não foi possível fazer o UPLOAD do arquivo!");
    exit();
}

$resultado_selecao = $conexao->get_selecao_id();

if ($fase == 1 && file_exists($_UP['pasta'] . $resultado_selecao[0]['ata_heteroidentificacao']) && $resultado_selecao[0]['ata_heteroidentificacao'] != null) {
    unlink($_UP['pasta'] . $resultado_selecao[0]['ata_heteroidentificacao']);
} elseif ($fase == 2 && file_exists($_UP['pasta'] . $resultado_selecao[0]['ata_heteroidentificacao_revisora']) && $resultado_selecao[0]['ata_heteroidentificacao_revisora'] != null) {
    unlink($_UP['pasta'] . $resultado_selecao[0]['ata_heteroidentificacao_revisora']);
} else {
    erro("Erro 234234! Arquivo não encontrado!");
}

if ($_POST)
    $resultado = $conexao->selecao_atualiza_atas_heteroidentificacao($nome_arquivo, $fase);
$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "16108", "selecao", "Update", "Atualizou as Atas de Heteroidentificação", "$alteracoes_detalhadas");
else {
    $conexao = null;
    erro("Erro 4534534584 Atas não alteradas!");
    exit();
}

header("Location: ../sistema/relatorio_heteroidentificacao.php");
exit();
