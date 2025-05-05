<?php

session_start();
$datetime = date('Y-m-d H:i:s');
$ip = $_SERVER['REMOTE_ADDR'];
include_once '../banco_dados/conexao.php';
include_once '../sistema/funcoes.php';
$navegador = getBrowser();
$conexao = new Conexao();

$id_especialidade = $_POST['user'];

if($_SESSION['id_usuario'] == null)
{
    erro("Erro 674355! Arquivo não adicionado");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    erro("Erro 67432355! Arquivo não adicionado");
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

if( $_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    $conexao = null;
    erro("Erro 4564674! Não foi possível adicionar o currículo"); 
    exit();
}

$id_curriculo = null;

if($_POST['id_curriculo'] == null)
{
    $conexao = null;
    erro("Erro 4574! Você deve selecionar qual arquivo está adicionando!"); 
    exit();
}

if(isset($_POST['id_curriculo']))
    $id_curriculo = (int)$_POST['id_curriculo'];



try
{
    /////////////////////////////////////////////////////////////////////////////
    // ADICIONA ARQUIVO
    
    $extensao = null;
    $nome_original = null;
    
    $arquivo_selecionado = $resultado = $conexao->get_curriculo_id($id_curriculo);
    
    
    $label = $arquivo_selecionado[0]['nome'];
    
    if($label == "" || $label == null)
    {
        $conexao = null;
        erro("Erro 45345674! Arquivo não adicionado!"); 
        exit();
    }
    
    $data_inicio = trim($_POST['data_inicio']);
    $data_fim = trim($_POST['data_fim']);
    $carga_horaria = trim($_POST['carga_horaria']);
    if($arquivo_selecionado[0]['carga_horaria_obrigatoria'] == 1)
    {
        if($data_inicio == null || $data_fim == null || $carga_horaria == null)
        {
            $conexao = null;
            erro("Os campos data de início, data de fim e resumo são obrigatórios para o carregamento deste arquivo!"); 
            exit();
        }
    }
    
    if($carga_horaria == "")
        $carga_horaria = null;
    
    if(strlen ($carga_horaria) > 200)
    {
        erro("O Resumo do PDF deve ter até 200 caracteres"); 
        exit();
    }
        
    
    if($data_inicio != null && !valida_data($data_inicio))
    {
        $conexao = null;
        erro("Data de Início inválida!"); 
        exit();
    }
    else $data_inicio = reverte_data ($data_inicio);
        
    if($data_fim != null && !valida_data($data_fim))
    {
        $conexao = null;
        erro("Data de Finalização inválida!"); 
        exit();
    }
    else $data_fim = reverte_data ($data_fim);
    
    if($data_inicio != null || $data_fim != null)
    {
        if(strtotime($data_inicio) > strtotime($data_fim))
        {
            $conexao = null;
            erro("Erro 84234! A data de início tem que ser menor do que a data final.");
            exit();
        }        
    }
    
    $mimetype = mime_content_type($_FILES['arquivo']['tmp_name']);
    if($mimetype != 'application/pdf')
    {
        erro("Erro 8633757567! O arquivo deve ser no formato PDF!"); 
        exit();
    }
    
    $codigo_criptografar = rand(1, 10000).$datetime."curriculo";
    //$nome_arquivo = substr(md5( $codigo_criptografar) ,0,12);
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    $nome_arquivo = $id_usuario_session . "_C_". $id_curriculo . "_E_" . $id_especialidade . "_" . $nome_arquivo;    
    $nome_original = $_FILES['arquivo']['name'];    

    // Pasta onde o arquivo vai ser salvo
    $_UP['pasta'] = $_SESSION['pasta_arquivos'];

    // Tamanho máximo do arquivo (em Bytes)
    $_UP['tamanho'] = 1024 * 1024 * 5; // 5 MB

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
        erro("Erro 94523561! Arquivo não adicionado! Selecione o arquivo a ser adicionado! No formato PDF e ele deve ter no máximo 5 MBs"); 
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
        erro("Erro 545101! O arquivo enviado é muito grande, envie arquivos de até 5 MBs.");
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
    
    $get_id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($id_usuario_session,$id_especialidade);
    
    if($get_id_candidato_x_especialidade == null || count($get_id_candidato_x_especialidade) <= 0)
    {
        $conexao = null;
        erro("Erro: 4124! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $get_curriculo_id = $conexao->get_curriculo_id($id_curriculo);
    if(count($get_curriculo_id) <= 0 || $get_curriculo_id[0]['apagado'] == 1)
    {
        $conexao = null;
        erro("Erro: 543535344! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $quantidade_maxima_uploads = (int)$get_curriculo_id[0]['quantidade_maxima_uploads'];
    
    if($quantidade_maxima_uploads == null || $quantidade_maxima_uploads == 0)
    {
        $conexao = null;
        erro("Erro: 534544! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $verifica_quantos_arquivos_ja_tem = $conexao->verifica_se_candidato_ja_colocou_curriculo($get_id_candidato_x_especialidade[0]['id'],$id_curriculo);
    
    if(count($verifica_quantos_arquivos_ja_tem) >= $quantidade_maxima_uploads)
    {
        $conexao = null;
        erro("Erro: 41235344! Não foi possível fazer o UPLOAD do arquivo! O LIMITE MÁXIMO de uploads é $quantidade_maxima_uploads!");
        exit();
    }

    if($_SESSION['selecao_regiao'] == 7 && $arquivo_selecionado[0]['carga_horaria_obrigatoria'] == 1) 
    {
        // O documento não pode ter data maior do que o início de inscrição - 1 dia

        if($data_fim != null && $_SESSION['selecao_data_inicial_inscricao'] != null)
        {
            $data_inicio_ = new DateTime($data_inicio);
            $dtinscricao = new DateTime($_SESSION['selecao_data_inicial_inscricao']);
            $dtfim = new DateTime($data_fim);


            // Verifica se não ultrapassou 10 anos
            $diferenca = $data_inicio_->diff($dtfim);
            if ($diferenca->y > 10 || ($diferenca->y == 10 && ($diferenca->m > 0 || $diferenca->days > 0))) 
            {
                erro("Erro: 23534775! Experiência maior do que 10 anos ");
                exit();
            }

            // Verifica se tem mais de 10 anos somado com o que já foi colocado
            $arquivos_de_experiencia_profissional = $conexao->arquivos_experiencia_profissional_especialidade($get_id_candidato_x_especialidade[0]['id']);

            $total_anos = $diferenca->y;
            $total_meses = $diferenca->m;
            if($arquivos_de_experiencia_profissional)
            foreach ($arquivos_de_experiencia_profissional as $curriculo) 
            {
                $data_inicial_existente_ = new DateTime($curriculo['data_inicio']);
                $data_final_existente_ = new DateTime($curriculo['data_termino']);
                $dif = $data_inicial_existente_->diff($data_final_existente_);
                $total_anos = $total_anos + $dif->y;
                $total_meses = $total_meses + $dif->m;
            }
            $total_anos = $total_anos + $total_meses/12;
            if ($total_anos >= 10) 
            {
                erro("Erro: 264578568! Experiência maior do que 10 anos ");
                exit();
            }


            $dtinscricao->modify('-1 day');
            $data_erro = $dtinscricao->format('d/m/Y');
            if($dtfim >= $dtinscricao)
            {
                $conexao = null;
                erro("Erro: 253678! A data de FIM é maior do que " . $data_erro);
                exit();
            }
        }
    }
    
    
    // 12 RM e se for experiência profissional
    if($arquivo_selecionado[0]['carga_horaria_obrigatoria'] == 1 && (($_SESSION['12_regiao'] == true && $_SESSION['selecao_regiao'] == 12) || $_SESSION['selecao_regiao'] == 7))
    {
        
        // Verifica sobreposição de datas para experiência profissional

        $arquivos_de_experiencia_profissional = $conexao->arquivos_experiencia_profissional_especialidade($get_id_candidato_x_especialidade[0]['id']);
        
        $data_inicial_teste = reverte_data($data_inicio);
        $data_final_teste = reverte_data($data_fim);

        $data_inicial_teste = strtotime($data_inicial_teste);
        $data_final_teste = strtotime($data_final_teste);
        
        foreach ($arquivos_de_experiencia_profissional as $curriculo) 
        {
            $data_inicial_existente = strtotime($curriculo['data_inicio']);
            $data_final_existente = strtotime($curriculo['data_termino']);
            
            $sobreposicao = sobreposicao_datas($data_inicial_teste, $data_final_teste, $data_inicial_existente, $data_final_existente);

            if($sobreposicao)
            {
                $conexao = null;
                erro("Erro: 235252354! Currículo com sobreposição de data!<br> Currículo cadastrado: ".$curriculo['label'] . "<br> Data de início ". trata_data($curriculo['data_inicio'])." e Data de fim ". trata_data($curriculo['data_termino'])." ");
                exit();
            }
        }
    }
    
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
        $resultado = $conexao->insere_curriculo($get_id_candidato_x_especialidade[0]['id'],$id_curriculo,$label,$nome_arquivo,$extensao,$nome_original,$tamanho_do_arquivo, $data_inicio,$data_fim, $carga_horaria);
        if($resultado)
        {
            $last_id = (int)$resultado['id_adicionado'];
            $alteracoes_detalhadas =  print_r($resultado, true);
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14113", "especialidade_curriculo", "Insert", "Inseriu um currículo: $label na especialidade $id_especialidade", $alteracoes_detalhadas);
        }
        header ("Location: candidato_especialidade_cadastrada_visualiza.php?criptografia=".$criptografia."&esp=".$id_especialidade."#fim_pagina");
    } 
    else 
    {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $conexao = null;
        erro("Erro: 5234124! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
}
catch (Exception $e) 
{
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 456465!");
    exit();
}
    
   
?>