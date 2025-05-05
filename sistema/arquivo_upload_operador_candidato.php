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
    erro("Erro 3475675! Arquivo não adicionado");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

if($id_usuario_session <= 0 || $id_usuario_session == '')
{
    erro("Erro 34674356! Arquivo não adicionado");
    exit();
}


//if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador')
if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 345674357! Você não tem permissão para adicionar este arquivo!");
    exit();
}


if( $_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    $conexao = null;
    erro("Erro 34564364! Não foi possível adicionar o currículo"); 
    exit();
}

$id_curriculo = null;

if($_POST['id_curriculo'] == null || $_POST['id_curriculo'] == "")
{
    $conexao = null;
    erro("Erro 34535! Você deve selecionar qual arquivo está adicionando!"); 
    exit();
}

if($_POST['id_candidato'] == null || $_POST['id_candidato'] == "")
{
    $conexao = null;
    erro("Erro 346366! Não foi possível adicionar o arquivo!"); 
    exit();
}

$id_candidato = $_POST['id_candidato'];

if(isset($_POST['id_curriculo']))
    $id_curriculo = $_POST['id_curriculo'];


// Verifica se é o currículo Graduação em curso superior na área que o candidato postula
/*
if($_POST['id_curriculo'] != 42)
{
    $conexao = null;
    erro("Erro 457437547! Currículo inválido!"); 
    exit();
}
*/

if($_SESSION['perfil'] == 'avaliador')
{
    
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
    $avaliador_pode_avaliar_id_especialidade = false;
    
    foreach ($lista_especialidade_avaliador as $linha_avaliador) 
    {
        if($linha_avaliador['id_especialidade'] == $id_especialidade)
            $avaliador_pode_avaliar_id_especialidade = true;
    }
    if(!$avaliador_pode_avaliar_id_especialidade)
    {
        $conexao = null;
        erro("Erro 56434386970! Você não tem permissão para adicionar este arquivo!");
        exit();
    }
}



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
        erro("Erro 6436456! Arquivo não adicionado!"); 
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
            erro("Erro 237595! Os campos data de início, data de fim e carga horária são obrigatórios para o carregamento deste arquivo!"); 
            exit();
        }
    }
    
    if($carga_horaria == "")
        $carga_horaria = null;
    
    if($data_inicio != null && !valida_data($data_inicio))
    {
        $conexao = null;
        erro("Erro 234235! Data de Início inválida!"); 
        exit();
    }
    else $data_inicio = reverte_data ($data_inicio);
        
    if($data_fim != null && !valida_data($data_fim))
    {
        $conexao = null;
        erro("Erro 7554785678! Data de Finalização inválida!"); 
        exit();
    }
    else $data_fim = reverte_data ($data_fim);
    
    if($data_inicio != null || $data_fim != null)
    {
        if(strtotime($data_inicio) >= strtotime($data_fim))
        {
            $conexao = null;
            erro("Erro 43643645! A data de início tem que ser menor do que a data final.");
            exit();
        }        
    }
    
    $mimetype = mime_content_type($_FILES['arquivo']['tmp_name']);
    if($mimetype != 'application/pdf')
    {
        erro("Erro 45783858! O arquivo deve ser no formato PDF!"); 
        exit();
    }
    
    $codigo_criptografar = rand(1, 10000).$datetime."curriculo";
    //$nome_arquivo = substr(md5( $codigo_criptografar) ,0,12);
    $nome_arquivo = hash('sha256', $codigo_criptografar); 
    
    $nome_arquivo = $id_candidato . "_C_". $id_curriculo . "_E_" . $id_especialidade . "_" . $nome_arquivo;    
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
        erro("Erro 54754787632! Arquivo não adicionado! Selecione o arquivo a ser adicionado! No formato PDF e ele deve ter no máximo 5 MBs"); 
        exit(); 
    }

    // Faz a verificação da extensão do arquivo
    $extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));
    
    if (array_search($extensao, $_UP['extensoes']) === false) 
    {
      //erro("Erro 545651! Por favor envie arquivos nos formatos:<br> PDF, DOC, DOCX, XLS, XLSX, ODT, ODS, PNG, JPG ou JPEG");
      erro("Erro 75478548! Por favor envie arquivos no formato PDF");
      exit();
    }

    // Faz a verificação do tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['arquivo']['size']) 
    {
        erro("Erro 34757567! O arquivo enviado é muito grande, envie arquivos de até 5 MBs.");
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
    
    $get_id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($id_candidato,$id_especialidade);
    
    if($get_id_candidato_x_especialidade == null || count($get_id_candidato_x_especialidade) <= 0)
    {
        $conexao = null;
        erro("Erro: 54685468756! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $get_curriculo_id = $conexao->get_curriculo_id($id_curriculo);
    if(count($get_curriculo_id) <= 0 || $get_curriculo_id[0]['apagado'] == 1)
    {
        $conexao = null;
        erro("Erro: 789789! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $quantidade_maxima_uploads = (int)$get_curriculo_id[0]['quantidade_maxima_uploads'];
    
    if($quantidade_maxima_uploads == null || $quantidade_maxima_uploads == 0)
    {
        $conexao = null;
        erro("Erro: 890890! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $verifica_quantos_arquivos_ja_tem = $conexao->verifica_se_candidato_ja_colocou_curriculo($get_id_candidato_x_especialidade[0]['id'],$id_curriculo);
    
    $get_especialidade = $conexao->get_especialidade_id($id_especialidade);
    $nome_especialidade = "";
    $ott_stt = "";
    
    if(isset($get_especialidade[0]['nome']))
    {
        $nome_especialidade = $get_especialidade[0]['nome'];
        $ott_stt = $get_especialidade[0]['ott_stt'];
    }
    
    if($ott_stt != 'stt' && $id_curriculo == '21')
    {
        $conexao = null;
        erro("Erro: 432634646! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }

    if(count($verifica_quantos_arquivos_ja_tem) >= $quantidade_maxima_uploads)
    {
        $conexao = null;
        erro("Erro: 45675757! Não foi possível fazer o UPLOAD do arquivo! O LIMITE MÁXIMO de uploads é $quantidade_maxima_uploads!");
        exit();
    }
    
    if($id_curriculo == 36 && $get_especialidade[0]['id'] != 7)
    {
        $conexao = null;
        erro("Erro: 35745856899! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
    
    $cpf_candidato = null;
    $get_candidato_id = $conexao->get_usuario_id($id_candidato);
    if(isset($get_candidato_id[0]['cpf']))
        $cpf_candidato = $get_candidato_id[0]['cpf'];
    
    if($cpf_candidato == null)
    {
        $conexao = null;
        erro("Erro: 567567124! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
        
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_arquivo)) 
    {
        // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
        $resultado = $conexao->insere_curriculo($get_id_candidato_x_especialidade[0]['id'],$id_curriculo,$label,$nome_arquivo,$extensao,$nome_original,$tamanho_do_arquivo, $data_inicio,$data_fim, $carga_horaria);
        if($resultado)
        {
            $last_id = (int)$resultado['id_adicionado'];
            $alteracoes_detalhadas =  print_r($resultado, true);
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14125", "especialidade_curriculo", "Insert", "Inseriu para o candidato $cpf_candidato o currículo: $label na especialidade $nome_especialidade", $alteracoes_detalhadas);
        }
        
        header ("Location: usuario_visualiza.php?id_usuario=".$id_candidato."#avaliacao_id_".$id_especialidade);
    } 
    else 
    {
        // Não foi possível fazer o upload, provavelmente a pasta está incorreta
        $conexao = null;
        erro("Erro: 432564778! Não foi possível fazer o UPLOAD do arquivo!");
        exit();
    }
}
catch (Exception $e) 
{
    $conexao = null;
    erro("O arquivo não foi adicionado. Erro 2315234545!");
    exit();
}
    
   
?>