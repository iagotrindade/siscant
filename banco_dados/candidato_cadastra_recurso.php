<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';

if (!$_POST) {
    erro("Erro 753457435745!");
    exit();
}

$datetime = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
$navegador = getBrowser();

$etapa = null;
if ($_POST['etapa'] != "")
    if ($_POST['etapa'] == '3 - Documental' || $_POST['etapa'] == '3 - IS') {
        $obs_etapa = $_POST['etapa'];
    }
$etapa = (int)htmlspecialchars(trim($_POST['etapa']));

$data_abertura = null;
if ($_POST['data_abertura'] != "")
    $data_abertura = htmlspecialchars(reverte_data(trim($_POST['data_abertura'])));

$avaliador = null;
if ($_POST['avaliador'] != "")
    $avaliador = htmlspecialchars(trim($_POST['avaliador']));

$status = null;
if ($_POST['status'] != "")
    $status = htmlspecialchars(trim($_POST['status']));

$analise = null;
if ($_POST['analise'] != "")
    $analise = htmlspecialchars(trim($_POST['analise']));

$id_especialidade = null;
if ($_POST['especialidade'] != "")
    $id_especialidade = (int)htmlspecialchars(trim($_POST['especialidade']));

if ($data_abertura != null && !valida_data($_POST['data_abertura'])) {
    erro("Erro 888546331! Data de abertura inválida!");
    exit();
}

$cpf_candidato = $_POST['c_p_f_candidato'];
$id_candidato = $_POST['id_candidato'];
$cidade_isgrec = $_POST['cidade_isgrec'];

if($obs_etapa == '3 - IS' && !$cidade_isgrec) {
    erro("Erro 875435745! Você selecionou que o recurso é de Inspeção de Saúde. Selecione a cidade da ISGREC!");
    exit();
}

session_start();

$datetime = date('Y-m-d H:i:s');

$conexao = new Conexao();

$label  = $_POST['label'] ?? '';
if ($label == null || $label == '') {
    $label = 'Arquivo do Recurso';
}

try {
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO

    $extensao = null;
    $nome_original = null;

    $codigo_criptografar = rand(1, 10000) . $datetime . "arquivo_adicionado_para_candidato";
    $nome_arquivo = hash('sha256', $codigo_criptografar);

    $nome_arquivo = $id_candidato . "_ADD_P_C_" . $nome_arquivo;

    $nome_original = $_FILES['arquivo']['name'];

    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = //$_SESSION['pasta_arquivos'];
    $_UP['pasta'] = '../sistema/arquivos_add_p_cand/recursos/';

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 8; // 2Mb

    // Array com as extensões permitidas
    $_UP['extensoes'] = array('pdf', 'doc', 'docx', 'odt', 'xls', 'xlsx', 'ods', 'png', 'jpg', 'jpeg');
    //$_UP['extensoes'] = array('pdf');

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
} catch (Exception $e) {
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 423743564356!");
    //erro("Exceção capturada! :" .  $e->getMessage());
    exit();
}

if (!isset($_SESSION['selecao'])) {
    erro("Erro 234623463467! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if (!isset($_SESSION['chave'])) {
    erro("Erro 23623462346! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if ($_SESSION['perfil'] != 'admin') {
    erro("Erro 2346324634! Não é possivel o cadastro!");
    exit();
}

if ($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave'])) {
    erro("Erro 324634634! Não foi possível fazer o cadastro !");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_candidato);
if ($get_candidato[0]['id_selecao'] != $_SESSION['selecao']) {
    erro("Erro 2346346346! Não foi possível fazer o cadastro !");
    exit();
}
if ($get_candidato[0]['cpf'] != $cpf_candidato) {
    erro("Erro 26346346! Não foi possível fazer o cadastro !");
    exit();
}

if (!isset($_SESSION['eipot']) == 1) {

    if ($etapa == null || $data_abertura == null || $avaliador == null) {
        erro("Erro 679687! <br>Os campos Etapa, data de abertura e se é para o avaliador realizar a análise, são obrigatórios!");
        exit();
    }
}

if ($id_especialidade == null && $avaliador == '1') {
    erro("Erro 42373457547! <br>A especialidade é obrigatória para direcionar ao avaliador!");
    exit();
}

if ($avaliador == '1' && ($status != '' || $analise != null)) {
    erro("Erro 3475856868! <br>Você selecionou que é para o avaliador realizar a análise, logo não pode alterar o status nem preencher a análise!");
    exit();
}

/*
    if($avaliador == '0' && ($status == '' || $analise == null))
    {
        erro("Erro 45734574357! <br>O recurso não pode ser cadastrado sem o status ou sem a análise!"); 
        exit();
    } */

$datetime = date('Y-m-d H:i:s');
$id_usuario_analise = null;
$data_analise = null;

if ($avaliador == 0 && $analise != null) {
    $id_usuario_analise = $_SESSION['id_usuario'];
    $data_analise = $datetime = date('Y-m-d H:i:s');
}

$resultado = $conexao->cadastra_recurso(
    $id_candidato,
    $id_especialidade,
    $etapa,
    $cidade_isgrec,
    $obs_etapa,
    $data_abertura,
    $avaliador,
    $status,
    $id_usuario_analise,
    $data_analise,
    $analise,
    $nome_original,
    $nome_arquivo,
    $extensao,
    $tamanho_do_arquivo
);

$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado && $resultadoArquivo)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "14130", "recurso", "Insert", "Operador " . $_SESSION['cpf'] . " Cadastrou um recurso para o candidato $cpf_candidato", $alteracoes_detalhadas);

$conexao = null;
header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#recursos");
