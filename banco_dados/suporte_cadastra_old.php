<?php	

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 2342342344!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    header("Location: ../index.php?erro=435154");
    exit();
}

if($_SESSION['perfil'] != 'candidato' || $_SESSION['candidato'] == 0)
{
    header("Location: ../index.php?erro=05451543254");
    exit();
}

if($_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 8413474! Não foi possível fazer a atualização dos dados !"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$usuario_logado = $conexao->get_usuario_cpf($_SESSION['cpf']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    header("Location: ../index.php?erro=184814");
    exit();
}


$motivo = null;
$mensagem = null;

$motivo = htmlspecialchars(trim($_POST['motivo']));
$mensagem = htmlspecialchars(trim($_POST['mensagem']));

if($motivo == "" || $mensagem == "")
{
    $conexao = null;
    erro("Os campos motivo e Mensagem são obrigatórios!");
    exit();
}

if(strlen($mensagem) > 2000)
{
    $conexao = null;
    erro("Erro 84908234! Não foi possível fazer o cadastro do suporte!");
    exit();
}

if(strlen($motivo) < 2 || strlen($motivo) > 100)
{
    $conexao = null;
    erro("Erro 83434! Não foi possível fazer o cadastro do suporte!");
    exit();
}

if($_POST)
    $resultado = $conexao->insere_suporte($_SESSION['id_usuario'], $motivo, $mensagem);
$id_suporte_adicionado = (int)$resultado['id_adicionado'];

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_suporte_adicionado", "14115", "mensagem", "Insert", "Inseriu o suporte ID: $id_suporte_adicionado", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4564324534!");
    exit();
}
header("Location: ../sistema/suporte.php#fimpagina");

?>



