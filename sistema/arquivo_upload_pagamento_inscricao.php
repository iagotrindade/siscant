<?php

$isento = 0;


session_start();
$datetime = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
include_once '../banco_dados/conexao.php';
include_once '../sistema/funcoes.php';
$navegador = getBrowser();
$conexao = new Conexao();



if(!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] == null)
{
    erro("Erro 675675645! Arquivo não adicionado!");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    erro("Erro 6767845! Arquivo não adicionado!");
    exit();
}

if($_SESSION['candidato'] != 1 || $_SESSION['perfil'] != 'candidato')
{
    erro("Erro 6745! Arquivo não adicionado!");
    exit();
}


if(!inscricao())
{
    erro("Erro 3545! Não é possível realizar a edição, o prazo já foi encerrado!");
    exit();
}

if($_POST == null)
{
    erro("Erro 4523474! Não foi possível fazer o upload do arquivo!"); 
    exit();    
}


if($_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 41347456! Não foi possível fazer o upload do arquivo!"); 
    exit(); 

}


try
{
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO
    
    $extensao = null;
    $nome_original = null;
    
    $arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($_SESSION['id_usuario']);
    
    if(count($arquivo_pagamento) > 0)
    {
        erro("Erro 4574! Arquivo já adicionado!"); 
        exit();
    }
    
    $mimetype = mime_content_type($_FILES['arquivo']['tmp_name']);
    if($mimetype != 'application/pdf')
    {
        erro("Erro 5756845684568! O arquivo deve ser no formato PDF!"); 
        exit();
    }
    
    $codigo_criptografar = rand(1, 10000).$datetime."arquivo_obrigatorio";
    //$nome_arquivo = substr(md5( $codigo_criptografar) ,0,12);
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    $nome_arquivo = $id_usuario_session . "_PG_". $nome_arquivo;    
    $nome_original = $_FILES['arquivo']['name'];    

    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = $_SESSION['pasta_arquivos'];

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

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
        erro("Erro 94564561! Arquivo não adicionado! Selecione o arquivo no botão Browser de tamanho máximo de 2 MB "); 
        exit(); // Para a execução do script
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
        erro("Erro 545101! O arquivo enviado é muito grande, envie arquivos de até 2 Mb.");
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
        $resultado = $conexao->insere_arquivo_pagamento($id_usuario_session,$nome_arquivo,$extensao,$nome_original,$tamanho_do_arquivo,$isento);
        if($resultado)
        {
            $last_id = (int)$resultado['id_adicionado'];
            $alteracoes_detalhadas =  print_r($resultado, true);
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14118", "pagamento_inscricao", "Insert", "Inseriu o pagamento da Inscrição", $alteracoes_detalhadas);
        }
        header ("Location: candidato_pagamento_inscricao.php");
    } 
    else 
    {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $conexao = null;
        erro("Erro: 5122344! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
}
catch (Exception $e) 
{
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 423556465!");
    //erro("Exceção capturada! :" .  $e->getMessage());
    exit();
}
    

   
?>