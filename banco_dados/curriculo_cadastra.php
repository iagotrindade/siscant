<?php	

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 234234644!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    header("Location: ../index.php?erro=346154");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    header("Location: ../index.php?erro=053454");
    exit();
}

if( $_POST['criptografia'] != hash('sha256', $_SESSION['assinatura_sistema']))
{
    header("Location: ../index.php?erro=18445684");
    exit();
}




$nome_curriculo = null;
$pontuacao = null;
$carga_horaria = null;
$multiplicador = null;
$quantidade_multiplicacao = null;
$quantidade_maxima = null;
$id_curriculo_adicionado = null;

$nome_curriculo = trim($_POST['nome_curriculo']);
$pontuacao = $_POST['pontuacao'];
$quantidade_maxima = trim($_POST['quantidade_maxima']);

if(isset($_POST['carga_horaria_obrigatoria'])) $carga_horaria = 1;
else $carga_horaria = 0;

if((int)$_POST['multiplicador'] > 1) 
{
    $multiplicador = 1;
    $quantidade_multiplicacao = (int)$_POST['multiplicador'];
}
else $multiplicador = 0;


if($nome_curriculo == null || $nome_curriculo == "")
{
    erro("O nome do currículo é obrigatório!");
    exit();
}

if($quantidade_maxima == null || $quantidade_maxima == "")
{
    erro("A quantidade máxima de uploads é obrigatório!");
    exit();
}


if($pontuacao == null || $pontuacao == "" )
{
    erro("Erro 42434523! A pontuação é obrigatória, preencha a casa depois da vírgula!");
    exit();
}

$quantidade_maxima = (int) $quantidade_maxima;

if($quantidade_maxima == 0 || !is_int($quantidade_maxima))
{
    erro("Erro 42423! A quantidade tem que ser um inteiro e maior que ZERO!");
    exit();
}

if($quantidade_maxima > 99 || $quantidade_maxima < 0)
{
    erro("Erro 3253! A quantidade máxima de uploads tem que ser maior que zero e menor que 99!");
    exit();
}

$pontuacao = (float)$pontuacao;
$pontuacao = $pontuacao*1000;

/*
if($pontuacao <= 0)
{
    erro("Erro 32454! A pontuação não foi validada!");
    exit();
}
*/
include_once 'conexao.php';
$conexao = new Conexao();

$usuario_logado = $conexao->get_usuario_cpf($_SESSION['cpf']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    header("Location: ../index.php?erro=18445814");
    exit();
}

$verifica_nome = $conexao->get_nome_curriculo($nome_curriculo);
if(count($verifica_nome) > 0)
{
    erro("Erro 5345! O nome deste currículo já existe!");
    exit();
}

if($_POST)
    $resultado = $conexao->cadastra_curriculo($nome_curriculo,$pontuacao,$carga_horaria,$quantidade_maxima,$multiplicador,$quantidade_multiplicacao);
$id_curriculo_adicionado = (int)$resultado['id_adicionado'];

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_curriculo_adicionado", "14112", "curriculo", "Insert", "Cadastrou opção de currículo: $nome_curriculo", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4545584 currículo não cadastrado!");
    exit();
}

header("Location: ../sistema/curriculo_visualiza.php");
exit();

?>



