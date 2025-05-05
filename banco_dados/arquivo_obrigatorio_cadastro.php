<?php	

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 562342344!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    header("Location: ../index.php?erro=435154");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    header("Location: ../index.php?erro=0545154");
    exit();
}

if( $_POST['criptografia'] != hash('sha256', $_SESSION['chave']."ten_freitas"))
{
    header("Location: ../index.php?erro=18484");
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


$nome_arquivo_obrigatorio = null;
$mulher = null;
$militar_ativa = null;
$reservista = null;
$cdi = null;
$vaga_reservada = null;

$nome_arquivo_obrigatorio = trim($_POST['nome_arquivo_obrigatorio']);

if(isset($_POST['mulher']))
    $mulher = '1';
if(isset($_POST['militar_ativa']))
    $militar_ativa = '1';
if(isset($_POST['reservista']))
    $reservista = '1';
if(isset($_POST['cdi']))
    $cdi = '1';
if(isset($_POST['vaga_reservada']))
    $vaga_reservada = '1';


if(isset($_POST['militar_ativa']) && isset($_POST['reservista']))
{
    erro("Erro 34578585468! O candidato não pode ser Militar da Ativa e Reservista ao mesmo tempo!");
    exit();
}


if($nome_arquivo_obrigatorio == null || $nome_arquivo_obrigatorio == "")
{
    erro("Erro 235345! O nome do arquivo é obrigatório!");
    exit();
}

if(strlen($nome_arquivo_obrigatorio) > 200)
{
    erro("Erro 36345763475! O nome é muito grande! No máximo 200 caracteres");
    exit();
}

$verifica_nome = $conexao->get_nome_doc_obrigatorio($nome_arquivo_obrigatorio);
if(count($verifica_nome) > 0)
{
    erro("Erro 5345! O nome deste arquivo obrigatório já existe!");
    exit();
}

if($_POST)
    $resultado = $conexao->cadastra_arquivo_obrigatorio($nome_arquivo_obrigatorio,$mulher,$militar_ativa,$reservista,$cdi, $vaga_reservada);
$id_arquivo_obrigatorio_adicionada = (int)$resultado['id_adicionado'];

$alteracoes_detalhadas =  print_r($resultado, true);

if($mulher == '1')
    $mulher = " - Obrigarótio para Mulheres -";
if($militar_ativa == '1')
    $militar_ativa = " - Obrigarótio para Militares da Ativa -";
if($militar_ativa == '1')
    $reservista = " - Obrigarótio para Reservistas -";
if($cdi == '1')
    $cdi = " - Obrigarótio para quem tem CDI -";

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_arquivo_obrigatorio_adicionada", "14111", "documentacao_obrigatoria", "Insert", "Cadastrou documento obrigatorio: $nome_arquivo_obrigatorio $mulher $militar_ativa $reservista $cdi", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4584 Arquivo Obrigatório não cadastrado!");
    exit();
}

header("Location: ../sistema/documentacao_obrigatoria_visualiza.php");
exit();

?>



