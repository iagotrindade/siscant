<?php	

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 2345344!");
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

if( $_POST['criptografia'] != hash('sha256', $_SESSION['assinatura_sistema']))
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


$nome_especialidade = null;
$teste_pratico = null;
$musica = null;

$nome_especialidade = trim($_POST['nome_especialidade']);
$ott_stt = $_POST['ott_stt'];

if(isset($_POST['teste_pratico'])) $teste_pratico = 1;
else $teste_pratico = 0;

if(isset($_POST['nota_av'])) $nota_av = 1;
else $nota_av = 0;

if(isset($_POST['musica'])) $musica = 1;
else $musica = 0;

$lista_cidades = null;
if(isset($_POST['cidades']))
    $lista_cidades = $_POST['cidades'];
if(isset($_POST['cidades']) == 0)
{$lista_cidades = null;}


if($ott_stt == "ott" && $musica == 1)
{
    erro("As especialidades de música são somente para STT!");
    exit();
}

if($nome_especialidade == null || $nome_especialidade == "" || count($lista_cidades) == 0 || $lista_cidades == null)
{
    erro("O nome da especialidade e pelo menos uma cidade é obrigatório!");
    exit();
}

if($usuario_logado[0]['codigo_selecao'] == "ott_stt" && $ott_stt == "")
{
    erro("Erro 2354! A seleção de STT ou OTT é obrigatória");
    exit();
}

if($ott_stt == "" || $ott_stt == null)
{
    erro("Erro 345345345! A seleção de STT ou OTT é obrigatória");
    exit();
}
    

$verifica_nome = $conexao->get_nome_especialidade($nome_especialidade, $ott_stt);
if(count($verifica_nome) > 0)
{
    erro("Erro 5345! A especialidade $ott_stt - $nome_especialidade já está cadastrada!");
    exit();
}

if($_POST)
    $resultado = $conexao->insere_especialidade($nome_especialidade, $teste_pratico, $nota_av, $ott_stt, $musica);
$id_especialidade_adicionada = (int)$resultado['id_adicionado'];

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_especialidade_adicionada", "14106", "especialidade", "Insert", "Inseriu a especialidade: $nome_especialidade", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4564 Especialidade não cadastrada!");
    exit();
}


if($id_especialidade_adicionada != null && $id_especialidade_adicionada > 0)
    $resultado2 = $conexao->insere_especialidade_x_cidade($id_especialidade_adicionada, $lista_cidades);

$alteracoes_detalhadas =  print_r($resultado2, true);


if($resultado2)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "14107", "cidade_x_especialidade", "Insert", "Inseriu cidade(s) na Especialidade $nome_especialidade", "$alteracoes_detalhadas");

header("Location: ../sistema/especialidade_visualiza.php");
exit();

?>



