<?php
session_start();

include_once '../banco_dados/conexao.php';
include_once '../sistema/funcoes.php';

$navegador = getBrowser();
$conexao = new Conexao();

$datetime = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];

if(!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == null)
{
    erro("Erro 6433456432! Arquivo não adicionado!!!");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    erro("Erro 6432! Arquivo não adicionado!!!");
    exit();
}

$selecao = $conexao->get_selecao_id();

if($selecao[0]['codigo'] != 'cet' && $_SESSION['candidato'] != 1)
{
    erro("Erro 67453432! Arquivo não adicionado!");
    exit();
}

if(!inscricao())
{
    erro("Erro 3545! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

$id_arquivo_obrigatorio = null;
if($_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 413474! Não foi possível fazer o upload do arquivo!"); 
    exit(); 
}

if($_POST == null)
{
    erro("Erro 4523474! Não foi possível fazer o upload do arquivo!"); 
    exit();    
}

if($_POST['id_arquivo_obrigatorio'] == null)
{
    erro("Erro 4574! Você deve selecionar qual arquivo está adicionando!"); 
    exit();
}

if($_POST['id_candidato'] != null)
{
    $id_candidato = $_POST['id_candidato'];
}

if(isset($_POST['id_arquivo_obrigatorio']))
    $id_arquivo_obrigatorio = $_POST['id_arquivo_obrigatorio'];
try
{

    /*
    // Testa abrindo o arquivo de forma binaria para ver se é PDF
    $file_teste_pdf = $_FILES['arquivo']['tmp_name'];
    // Abrir o arquivo no modo binário
    $handle = fopen($file_teste_pdf, 'rb');
    // Ler os primeiros 5 bytes do arquivo
    $header = fread($handle, 5);
    if($mimetype != 'application/pdf' && $header != "%PDF-")
*/
    
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO
    
    $extensao = null;
    $nome_original = null;
    
    $arquivo_selecionado = $resultado = $conexao->get_documentacao_obrigatoria($id_arquivo_obrigatorio);
    $label = $arquivo_selecionado[0]['nome'];
    if($label == "" || $label == null)
    {
        erro("Erro 45345674! Arquivo não adicionado!"); 
        exit();
    }

    $codigo_criptografar = rand(1, 10000).$datetime."arquivo_obrigatorio";
    //$nome_arquivo = substr(md5( $codigo_criptografar) ,0,12);
    $nome_arquivo = hash('sha256', $codigo_criptografar); //md5($codigo_criptografar);
    
    $nome_arquivo = $id_candidato . "_DO_". $id_arquivo_obrigatorio . "_" . $nome_arquivo;    
    $nome_original = $_FILES['arquivo']['name'];    

    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = $_SESSION['pasta_arquivos'];

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 5; // 5Mb

    // Array com as extensões permitidas
    //$_UP['extensoes'] = array('pdf','doc','docx','odt','xls','xlsx','ods','png','jpg','jpeg');
    $_UP['extensoes'] = array('pdf');

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
        erro("Erro 94235346561! Arquivo não adicionado! Selecione o arquivo PDF no botão Browser de no máximo 5 MB"); 
        exit(); // Para a execução do script
    }

    // Verifica se o arquivo é PDF pelo mine_type
    $mimetype = mime_content_type($_FILES['arquivo']['tmp_name']);
    if($mimetype != 'application/pdf')
    {
        erro("Erro 45783858! O arquivo deve ser no formato PDF!"); 
        exit();
    }

    // Faz a verificação da extensão do arquivo
    $extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
    
    if (array_search($extensao, $_UP['extensoes']) === false) 
    {
      //erro("Erro 545651! Por favor envie arquivos nos formatos:<br> PDF, DOC, DOCX, XLS, XLSX, ODT, ODS, PNG, JPG ou JPEG");
      erro("Erro 545651! Por favor envie arquivos no formato PDF");
      exit();
    }

    // Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['arquivo']['size']) 
    {
        erro("Erro 545101! O arquivo enviado é muito grande, envie arquivos de até 5 MB.");
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
    
    $get_id_arquivo_obrigatorio = $conexao->get_documentacao_obrigatoria_id($id_arquivo_obrigatorio);
    if(count($get_id_arquivo_obrigatorio) <= 0 || $get_id_arquivo_obrigatorio[0]['apagado'] == 1)
    {
        $conexao = null;
        erro("Erro: 535344! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $verifica_documento_obrigatorio_candidato = $conexao->verifica_documento_obrigatorio_candidato($id_arquivo_obrigatorio,$id_candidato);
    if(count($verifica_documento_obrigatorio_candidato) > 0)
    {
        $conexao = null;
        erro("Erro: 589! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
        $resultado = $conexao->insere_arquivo_obrigatorio($id_candidato, $id_arquivo_obrigatorio, $label,$nome_arquivo, $extensao, $nome_original, $tamanho_do_arquivo);
        if($resultado)
        {
            $last_id = (int)$resultado['id_adicionado'];
            $alteracoes_detalhadas =  print_r($resultado, true);
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14110", "documento_obrigatorio", "Insert", "Inseriu o documento $label", $alteracoes_detalhadas);
        }
        header ("Location: documentos_obrigatorios_visualiza.php#fim_pagina");
    } 
    else 
    {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $conexao = null;
        erro("Erro: 5124! Não foi possível fazer o UPLOAD do arquivo!");
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