<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 342563246!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 23562346! Não foi possível atualizar a data");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro 2346457457! Não foi possível atualizar a data");
    exit();
}

if( $_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    $conexao = null;
    erro("Erro 23462476! Não foi possível atualizar a data");
    exit();
}

$id_selecao = trim($_POST['cod']);
if($id_selecao != $_SESSION['selecao'])
{
    $conexao = null;
    erro("Erro 53756757! Não foi possível atualizar a data");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    $conexao = null;
    erro("Erro 2346743734! Não foi possível atualizar as datas da inscrição");
    exit();
}

$data_maxima_nascimento = trim($_POST['data_maxima_nascimento']);
$data_minima_nascimento = trim($_POST['data_minima_nascimento']);

if($data_maxima_nascimento == null && $data_minima_nascimento == null)
{
    $conexao = null;
    erro("Erro 2346243674! Pelo menos uma data é obrigatória!");
    exit();    
}

if($data_maxima_nascimento != null && !valida_data($data_maxima_nascimento))
{
    $conexao = null;
    erro("Erro 562362346! Data máxima é inválida!");
    exit();    
}
if($data_minima_nascimento != null && !valida_data($data_minima_nascimento))
{
    $conexao = null;
    erro("Erro 2673457347547! Data mínima é inválida!");
    exit();    
}

$data_maxima_nascimento   = reverte_data($data_maxima_nascimento);
$data_minima_nascimento   = reverte_data($data_minima_nascimento);

$resultado_selecao = $conexao->get_selecao_id();

$nome_selecao = $resultado_selecao[0]['nome'] . " - " . $resultado_selecao[0]['ano'];

if($_POST)
    $resultado = $conexao->selecao_atualiza_data_maxima_nascimento($data_maxima_nascimento,$data_minima_nascimento);
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "16140", "selecao", "Update", "Atualizou a data máxima de nascimento para inscrição: $data_maxima_nascimento e a data mínima para $data_minima_nascimento", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 23462346 Data não atualizada!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?datas_atualizadas=1");
exit();

?>



