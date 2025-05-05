<?php	

include_once '../sistema/funcoes.php';
session_start();

if(!$_SESSION['liberacao_prioridade_candidato_selecao'])
{
    erro_mensagem("Erro 324623434! Não está liberado para fazer o cadastro da prioridade!");
    exit();
}

if(!$_POST)
{
    erro_mensagem("Erro 5645645644!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    header("Location: ../index.php?erro=346154");
    exit();
}

if($_SESSION['perfil'] != 'candidato' || $_SESSION['candidato'] == 0)
{
    header("Location: ../index.php?erro=0534523544");
    exit();
}

if( $_POST['crip'] != hash('sha256', $_SESSION['cpf']."freitas"))
{
    $conexao = null;
    erro("Erro: 4154! Não foi possível cadastrar a prioridade!");
    exit();  
}

/*
if(!inscricao())
{
    $conexao = null;
    erro("Erro: 4894654! O período de inscrição foi encerrado!");
    exit();  
}
*/

include_once 'conexao.php';
$conexao = new Conexao();

$usuario_logado = $conexao->get_usuario_cpf($_SESSION['cpf']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    header("Location: ../index.php?erro=1823414");
    exit();
}

if($usuario_logado[0]['concorrendo'] == 0 || $usuario_logado[0]['concorrendo'] == null)
{
    erro("Erro: 32456236! Você está desclassificado!");
    exit();
}

$id_usuario_session = $_SESSION['id_usuario'];

$id_cidade = null;
$prioridade = null;
$id_especialidade = null;

$id_cidade = $_POST['id_cidade'];
$prioridade = $_POST['prioridade'];
$id_especialidade = $_POST['esp'];

if(!isset($_POST['id_cidade'])  || !isset($_POST['esp'])
|| !isset($_POST['prioridade']) || $prioridade == "" || $id_cidade == "" || $id_especialidade == "")
{
    erro("O campo CIDADE e PRIORIDADE são obrigatórios");
    exit();
}

$get_especialidade_id = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $get_especialidade_id[0]['nome'];

if($nome_especialidade == null || $nome_especialidade == "")
{
    $conexao = null;
    erro("Erro: 411222354! Não foi possível cadastrar a prioridade!");
    exit();    
}

$get_id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($id_usuario_session,$id_especialidade);

if(count($get_id_candidato_x_especialidade) == 0)
{
    $conexao = null;
    erro("Erro: 458568657! Não foi possível cadastrar a prioridade!");
    exit();
}

$id_candidato_x_especialidade = $get_id_candidato_x_especialidade[0]['id'];

if($id_candidato_x_especialidade == null || $id_candidato_x_especialidade <= 0)
{
    $conexao = null;
    erro("Erro: 411224! Não foi possível cadastrar a prioridade!");
    exit();
}

if($get_id_candidato_x_especialidade[0]['concorrendo'] == 0 || $get_id_candidato_x_especialidade[0]['concorrendo'] == null)
{
    //var_dump($get_id_candidato_x_especialidade);
    $conexao = null;
    erro("Erro: 34756756! Não foi possível cadastrar a prioridade, você está desclassificado dela!");
    exit();
}

$get_prioridade_especialidade_candidato = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

$contador = 0;

foreach ($get_prioridade_especialidade_candidato as &$linha) 
{
    if($linha['prioridade'] == $prioridade)
    {
        $conexao = null;
        erro("Erro: 4123445624! Não foi possível cadastrar a especialidade!");
        exit();
    }
    if($linha['id_cidade'] == $id_cidade)
    {
        $conexao = null;
        erro("Erro: 457457! Não foi possível cadastrar a especialidade!");
        exit();
    }
    $contador++;
}

if($prioridade !=  $contador+1)
{
    $conexao = null;
    erro("Erro: 99686786! Não foi possível cadastrar a especialidade!");
    exit();
}

$quantidade_cidades = $conexao->get_quantidade_cidades_especialidade($id_especialidade);  
$quantidade_cidades = $quantidade_cidades[0]['quantidade'];

if($prioridade > $quantidade_cidades)
{
    $conexao = null;
    erro("Erro: 2436347! Não foi possível cadastrar a especialidade!");
    exit();
}

$get_cidades_especialidade = $conexao->get_cidades_especialidade($id_especialidade);
$cidade_nao_encontrada_na_especialidade = true;
foreach ($get_cidades_especialidade as &$linha) 
{
    if($linha['id'] == $id_cidade)
    {
        $cidade_nao_encontrada_na_especialidade = false;
        break;
    }
}
if($cidade_nao_encontrada_na_especialidade)
{
    $conexao = null;
    erro("Erro: 546868! Não foi possível cadastrar a especialidade!");
    exit();
}


if($_POST)
    $resultado = $conexao->cadastra_prioridade($id_candidato_x_especialidade,$prioridade,$id_cidade);

$id_prioridade_adicionada = (int)$resultado['id_adicionado'];

if($id_prioridade_adicionada == null || $id_prioridade_adicionada == "")
{
    $conexao = null;
    erro("Erro: 411! Não foi possível cadastrar a prioridade!");
    exit();
}

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_prioridade_adicionada", "14114", "prioridade_cidade", "Insert", "Cadastrou prioridade $prioridade na Especialização $nome_especialidade", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4545584 currículo não cadastrado!");
    exit();
}

header("Location: ../sistema/candidato_especialidade_cadastrada_visualiza.php?esp=$id_especialidade");


?>



