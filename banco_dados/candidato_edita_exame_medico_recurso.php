<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';

session_start();

if (!$_POST) {
    erro("Erro 56754674!");
    exit();
}

if (!isset($_SESSION['selecao'])) {
    erro("Erro 463475467! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if (!isset($_SESSION['chave'])) {
    erro("Erro 45745547! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if ($_SESSION['perfil'] != 'jise' && $_SESSION['perfil'] != 'admin') {
    erro("Erro 3426346346! Não é possivel fazer essa edição!");
    exit();
}

if ($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'] . $_SESSION['chave'])) {
    erro("Erro 3567457547! Não foi possível fazer a atualização dos dados !");
    exit();
}

$conexao = new Conexao();

$apto_saude = null;
if ($_POST['apto_saude_recurso'] != "")
    $apto_saude = htmlspecialchars(trim($_POST['apto_saude_recurso']));
$grupo_saude = null;
if ($_POST['grupo_saude_recurso'] != "")
    $grupo_saude = htmlspecialchars(trim($_POST['grupo_saude_recurso']));
$data_exame_saude = null;
if ($_POST['data_exame_saude_recurso'] != "")
    $data_exame_saude = htmlspecialchars(reverte_data(trim($_POST['data_exame_saude_recurso'])));
$cid_saude = null;
if ($_POST['cid_saude_recurso'] != "")
    $cid_saude = htmlspecialchars(trim($_POST['cid_saude_recurso']));
$obs_saude = null;
if ($_POST['observacao_exame_saude_recurso'] != "")
    $obs_saude = htmlspecialchars(trim($_POST['observacao_exame_saude_recurso']));

if ($_POST['data_exame_saude_recurso'] != null && !valida_data($_POST['data_exame_saude_recurso'])) {
    erro("Erro 23464236436! Data do exame inválida!");
    exit();
}

if (strlen($obs_saude) > 2000) {
    erro_mensagem("Erro 23464356436! Observação muito grande! Não foi possível fazer o cadastro do RECURSO Exame médico!");
    exit();
}
if (strlen($cid_saude) > 2000) {
    erro_mensagem("Erro 23464356436! CID muito grande! Não foi possível fazer o cadastro do RECURSO Exame médico!");
    exit();
}

$cpf_candidato = $_POST['c_p_f_candidato'];
$id_candidato = $_POST['id_candidato'];

$datetime = date('Y-m-d H:i:s');

$label = 'ATA ISGRec';

try {
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO

    if ($_FILES['ata_is_recurso'] && $_FILES['ata_is_recurso']['name'] != "") {
        $extensao = null;
        $nome_original = null;

        $codigo_criptografar = rand(1, 10000) . $datetime . "arquivo_adicionado_para_candidato";
        $nome_arquivo = hash('sha256', $codigo_criptografar);

        $nome_arquivo = $id_candidato . "_ADD_P_C_" . $nome_arquivo;

    
        $nome_original = $_FILES['ata_is_recurso']['name'];

        // Pasta onde o arquivo vai ser salvo
        $_UP['pasta'] = //$_SESSION['pasta_arquivos'];
            $_UP['pasta'] = '../sistema/arquivos_add_p_cand/atas_is/';

        // Tamanho máximo do arquivo (em Bytes)
        $_UP['tamanho'] = 1024 * 1024 * 5; // 2Mb

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
        if ($_FILES['ata_is_recurso']['error'] != 0) {
            //erro("Erro 94561! Arquivo não adicionado! " . $_UP['erros'][$_FILES['arquivo']['error']]); 
            erro("Erro 4743734574! Arquivo não adicionado! Selecione o arquivo no botão Browser de tamanho máximo de 2 MB ");
            exit(); // Para a execução do script
        }

        // Faz a verificação da extensão do arquivo
        $extensao = strtolower(end(explode('.', $_FILES['ata_is_recurso']['name'])));

        if (array_search($extensao, $_UP['extensoes']) === false) {
            erro("Erro 234623634! Por favor envie arquivos nos formatos:<br> PDF, DOC, DOCX, XLS, XLSX, ODT, ODS, PNG, JPG ou JPEG");
            //erro("Erro 276437457! Por favor envie arquivos no formato PDF");
            exit();
        }

        // Faz a verificação do tamanho do arquivo
        if ($_UP['tamanho'] < $_FILES['ata_is_recurso']['size']) {
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

        $tamanho_do_arquivo =  intval($_FILES['ata_is_recurso']['size']);

        if (move_uploaded_file($_FILES['ata_is_recurso']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) {
            // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
            $resultadoArquivo = true;
        } else {
            // Não foi possível fazer o upload, provavelmente a pasta está incorreta
            $conexao = null;
            erro("Erro: 23642646! Não foi possível fazer o UPLOAD do arquivo!");
            exit();
        }
    } else {
        $resultadoArquivo = false;
        $nome_arquivo = $conexao->get_candidato_atas_is($id_candidato)['ata_is_recurso'];
    }
} catch (Exception $e) {
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 423743564356!");
    //erro("Exceção capturada! :" .  $e->getMessage());
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_candidato);
if ($get_candidato[0]['id_selecao'] != $_SESSION['selecao']) {
    if ($get_candidato[0]['medico_obrigatorio'] != 1) {
        erro("Erro 74564568! Não foi possível fazer a atualização dos dados !");
        exit();
    }
}
if ($get_candidato[0]['cpf'] != $cpf_candidato) {
    erro("Erro 3574574567! Não foi possível fazer a atualização dos dados !");
    exit();
}

if ($_POST)
    $resultado = $conexao->candidato_edita_exame_medico_recurso(
        $id_candidato,
        $apto_saude,
        $grupo_saude,
        $data_exame_saude,
        $cid_saude,
        $obs_saude,
        $nome_arquivo,
    );

$alteracoes_detalhadas =  print_r($resultado, true);

if ($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "16136", "usuario", "Update", "Operador " . $_SESSION['cpf'] . " atualizou o recurso exame médico do candidato $cpf_candidato", $alteracoes_detalhadas);

$conexao = null;

if ($_POST['medico_obrigatorio'] == 'sim')
    header("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_candidato#exame_medico");
if ($_POST['medico_obrigatorio'] == 'nao')
    header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#exame_medico");

echo "TUDO NA BOA!";
