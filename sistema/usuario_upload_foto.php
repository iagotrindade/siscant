<?php

session_start();

if($_SESSION['id_usuario'] == null)
{
    header("Location: ../index.php?erro=85147");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    header("Location: ../index.php?erro=85147");
    exit();
}

$datetime = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
include_once '../banco_dados/conexao.php';
include_once '../sistema/funcoes.php';
$navegador = getBrowser();
$conexao = new Conexao();

if(!inscricao() && ($_SESSION['candidato'] == 1 || $_SESSION['perfil'] == 'candidato'))
{
    erro("Erro 999564! Não foi possível fazer o upload da foto!"); 
    exit();
}

if($_POST['crip'] != hash('sha256', $_SESSION['assinatura_sistema']."freitas"))
{
    erro("Erro 413564! Não foi possível fazer o upload da foto!"); 
    exit(); 
}

$mimetype = mime_content_type($_FILES['foto']['tmp_name']);
if($mimetype != 'image/jpeg' && $mimetype != 'image/png')
{
    erro("Erro 3854684568! O arquivo deve ser no formato JPG, PNG ou JPEG"); 
    exit();
}


try
{
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO
    
    $extensao = null;
    $nome_original = null;
    
    $codigo_criptografar = rand(1, 10000).$datetime."foto";
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    $nome_arquivo = $id_usuario_session . "_FT_". $nome_arquivo;    
    $nome_original = $_FILES['foto']['name'];    

    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = 'fotos/';

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

    // Array com as extensões permitidas
    $_UP['extensoes'] = array('png','jpg','jpeg');

    // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
    $_UP['renomeia'] = true;
    
    // Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
    
    // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['foto']['error'] != 0) 
    {
        //erro("Erro 94561! Arquivo não adicionado! " . $_UP['erros'][$_FILES['foto']['error']]); 
        erro("Erro 9464561! Arquivo não adicionado! Selecione o arquivo no botão Browser "); 
        exit(); // Para a execução do script
    }

    // Faz a verificação da extensão do arquivo
    $extensao = strtolower(end(explode('.', $_FILES['foto']['name'])));
    
    if (array_search($extensao, $_UP['extensoes']) === false) 
    {
        erro("Erro 545651! Por favor envie o arquivo nos formatos PNG, JPG ou JPEG");
        exit();
    }

    // Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['foto']['size']) 
    {
        erro("Erro 53101! O arquivo enviado é muito grande, envie arquivos de até 2Mb.");
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
    
    $tamanho_do_arquivo =  intval($_FILES['foto']['size']);
    
    // Depois verifica se é possível mover o arquivo para a pasta escolhida
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
        
        $apagado_foto_antiga = $conexao->apaga_foto_antiga();
        
        $resultado = $conexao->insere_foto($nome_arquivo,$extensao,$nome_original,$tamanho_do_arquivo);
        $last_id = (int)$resultado['id_adicionado'];
        $alteracoes_detalhadas =  print_r($resultado, true);
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "16102", "foto", "Insert", "Atualizou a sua foto", $alteracoes_detalhadas);
        $_SESSION['usuario_foto'] = $nome_arquivo;
        header ("Location: foto_upload.php");
    } 
    else 
    {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $conexao = null;
        //erro("Erro: 5124! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
}
catch (Exception $e) 
{
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 456465!");
    //erro("Exceção capturada! :" .  $e->getMessage());
    exit();
}
    
   
?>