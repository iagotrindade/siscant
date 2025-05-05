<?php

session_start();
$datetime = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
include_once '../banco_dados/conexao.php';
include_once '../sistema/funcoes.php';
$navegador = getBrowser();
$conexao = new Conexao();


if(!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == null)
{
    erro("Erro 363263246! Arquivo não adicionado!");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    erro("Erro 23462362346! Arquivo não adicionado!");
    exit();
}

if($_SESSION['candidato'] == 1 || $_SESSION['perfil'] == 'candidato')
{
    erro("Erro 236262346! Arquivo não adicionado!");
    exit();
}

if($_POST == null)
{
    erro("Erro 2347474357! Não foi possível fazer o upload do arquivo!"); 
    exit();    
}


if($_POST['criptografia'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    //erro("Erro 267423747! Não foi possível fazer o upload do arquivo!"); 
    exit(); 
}

$cpf_candidato  = $_POST['cpf_candidato'];
if($cpf_candidato == null || $cpf_candidato == 0)
{
    erro("Erro 23474274357! Não foi possível fazer o upload do arquivo!"); 
    exit(); 
}

$id_candidato  = (int)$_POST['id_candidato'];
if($id_candidato == null || $id_candidato == 0)
{
    erro("Erro 234734747! Não foi possível fazer o upload do arquivo!"); 
    exit(); 
}

$label  = $_POST['label'];
if($label == null || $label == '')
{
    erro("Erro 3262347634567! A descrição do arquivo é obrigatória!"); 
    exit(); 
}


try
{
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO
    
    $extensao = null;
    $nome_original = null;
    
    $codigo_criptografar = rand(1, 10000).$datetime."arquivo_adicionado_para_candidato";
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    $nome_arquivo = $id_usuario_session . "_ADD_P_C_". $nome_arquivo;    
    $nome_original = $_FILES['arquivo']['name'];    

    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = //$_SESSION['pasta_arquivos'];
    $_UP['pasta'] = 'arquivos_add_p_cand/';

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 5; // 2Mb

    // Array com as extensões permitidas
    $_UP['extensoes'] = array('pdf','doc','docx','odt','xls','xlsx','ods','png','jpg','jpeg');
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
    if ($_FILES['arquivo']['error'] != 0) 
    {
        //erro("Erro 94561! Arquivo não adicionado! " . $_UP['erros'][$_FILES['arquivo']['error']]); 
        erro("Erro 4743734574! Arquivo não adicionado! Selecione o arquivo no botão Browser de tamanho máximo de 2 MB "); 
        exit(); // Para a execução do script
    }

    // Faz a verificação da extensão do arquivo
    $extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
    
    if (array_search($extensao, $_UP['extensoes']) === false) 
    {
      erro("Erro 234623634! Por favor envie arquivos nos formatos:<br> PDF, DOC, DOCX, XLS, XLSX, ODT, ODS, PNG, JPG ou JPEG");
      //erro("Erro 276437457! Por favor envie arquivos no formato PDF");
      exit();
    }

    // Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['arquivo']['size']) 
    {
        erro("Erro 42374357457! O arquivo enviado é muito grande, envie arquivos de até 5 Mb.");
        exit();
    }

    // Primeiro verifica se deve trocar o nome do arquivo
    if ($_UP['renomeia'] == true) 
    {
      // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão
      $nome_arquivo = $nome_arquivo.'.'.$extensao;
    } 
    else 
    {
      // Mantém o nome original do arquivo
      $nome_arquivo = $nome_original.'.'.$extensao;
    }
    // Depois verifica se é possível mover o arquivo para a pasta escolhida
    
    $tamanho_do_arquivo =  intval($_FILES['arquivo']['size']);
    
    
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
        $resultado = $conexao->insere_arquivo_para_candidato($id_candidato,$label,$nome_arquivo,$extensao,$nome_original,$tamanho_do_arquivo);
        if($resultado)
        {
            $last_id = (int)$resultado['id_adicionado'];
            $alteracoes_detalhadas =  print_r($resultado, true);
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_candidato, $last_id, "14128", "add_arquivo_para_candidato", "Insert", "Inseriu um arquivo para o candidato de CPF: $cpf_candidato e ID: $id_candidato", $alteracoes_detalhadas);
        }
        header ("Location: usuario_visualiza.php?id_usuario=$id_candidato.php#insere_arquivo_candidato");
    } 
    else 
    {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $conexao = null;
        erro("Erro: 23642646! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
}
catch (Exception $e) 
{
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 423743564356!");
    //erro("Exceção capturada! :" .  $e->getMessage());
    exit();
}
    

   
?>