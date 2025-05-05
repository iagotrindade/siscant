<?php

session_start();

if($_SESSION['id_usuario'] == null)
{
    header("Location: ../index.php?erro=546474757");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    header("Location: ../index.php?erro=37547547");
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
    erro("Erro 999564! Não foi possível fazer o upload do arquivo!"); 
    exit();
}

if($_POST['crip'] != hash('sha256', $_SESSION['assinatura_sistema']."freitas"))
{
    erro("Erro 499569! Não foi possível fazer o upload do arquivo!"); 
    exit(); 
}

if($_SESSION['selecao_pagamento'] == 0 || $_SESSION['selecao_pagamento'] == null)
{
    erro("Erro 874586967! A seleção está configurada para não cobrar do candidato!"); 
    exit();
}

try
{
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO
    
    $extensao = null;
    $nome_original = null;
    
    $codigo_criptografar = rand(1, 10000).$datetime."csv";
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    $nome_arquivo = $id_usuario_session . "_CSV_". $nome_arquivo;    
    $nome_original = $_FILES['arquivo_csv']['name'];    

    $mimetype = mime_content_type($_FILES['arquivo_csv']['tmp_name']);
    if($mimetype != 'text/plain')
    {
        erro("Erro 3854684568! O arquivo deve ser no formato CSV"); 
        exit();
    }
    
    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = 'csv/';

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 4; // 4Mb

    // Array com as extensões permitidas
    $_UP['extensoes'] = array('csv');

    // Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
    $_UP['renomeia'] = true;
    
    // Array com os tipos de erros de upload do PHP
    $_UP['erros'][0] = 'Não houve erro';
    $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
    $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
    $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
    $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
    
    // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
    if ($_FILES['arquivo_csv']['error'] != 0) 
    {
        //erro("Erro 94561! Arquivo não adicionado! " . $_UP['erros'][$_FILES['foto']['error']]); 
        erro("Erro 4875468468! Arquivo não adicionado! Selecione o arquivo no botão Browser "); 
        exit(); // Para a execução do script
    }

    // Faz a verificação da extensão do arquivo
    $extensao = strtolower(end(explode('.', $_FILES['arquivo_csv']['name'])));
    
    if (array_search($extensao, $_UP['extensoes']) === false) 
    {
        erro("Erro 3457345734! Por favor envie o arquivo no formato CSV");
        exit();
    }

    // Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['arquivo_csv']['size']) 
    {
        erro("Erro 56956795679! O arquivo enviado é muito grande, envie arquivos de até 4 MB.");
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
    
    $tamanho_do_arquivo =  intval($_FILES['arquivo_csv']['size']);
    
    // Depois verifica se é possível mover o arquivo para a pasta escolhida
    if (move_uploaded_file($_FILES['arquivo_csv']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        $apagado_csv_antiga = $conexao->apaga_csv_antigo();
        
        $row = 1;
        if (($handle = fopen("csv/".$nome_arquivo, "r")) !== FALSE) 
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
            {
                $num = count($data);
                //echo "<p> $num campos na linha $row: <br /></p>\n";
                $id_gru = null;
                $cpf = null;
                $valor = null;
                $data_pagamento = null;
                $numero_ref = null;
                $situacao = null;
                
                $row++;
                
                for($celula=0; $celula < $num; $celula++) 
                {
                    if($celula == 0) $id_gru = $data[$celula];
                    if($celula == 1) $cpf = $data[$celula];
                    if($celula == 2) $valor = $data[$celula];
                    if($celula == 3) $data_pagamento = $data[$celula];
                    if($celula == 4) $numero_ref = $data[$celula];
                    if($celula == 5) $situacao = $data[$celula];
                    //echo $data[$celula] . "<br />\n";
                }
                
                $cpf = trim($cpf);
                $cpf = str_replace('.','',$cpf);
                $cpf = str_replace('-','',$cpf);
                if(strlen($cpf) < 11 || strlen($cpf) > 11) continue;
                if(!valida_cpf($cpf)) continue;
                
                $verifica_cpf = $conexao->get_usuario_cpf($cpf);
                if(count($verifica_cpf) != 1) continue;
                if($verifica_cpf[0]['candidato'] != '1') continue;
                
                if($valor != null && $valor != '')
                    $valor = str_replace(',','.',$valor);
                else $valor = 0;
                
                $valor = floatval($valor);
                
                if($data_pagamento != null && $data_pagamento != '')
                {
                    if(strlen($data_pagamento) == 10)
                        $data_pagamento = trata_data($data_pagamento);
                    if(strlen($data_pagamento) > 15)
                        $data_pagamento = trata_data_hora($data_pagamento);
                }
                
                $resultado = $conexao->insere_linha_csv($id_gru, $cpf, $valor, $data_pagamento, $numero_ref, $situacao);
            }
            fclose($handle);
        }
        
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "22101", "CSV", "Insert", "Inseriu uma planilha dos pagamentos CSV", $alteracoes_detalhadas);
        header ("Location: gru_pagas.php");
    } 
    else 
    {
        $conexao = null;
        exit();
    }
}
catch (Exception $e) 
{
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 357357854686!");
    exit();
}
    
   
?>