<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 462346346!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 34236346! Não foi possível atualizar as datas");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro 3246346! Não foi possível atualizar as datas");
    exit();
}

if( $_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    $conexao = null;
    erro("Erro 36532463246! Não foi possível atualizar as datas");
    exit();
}

$id_selecao = trim($_POST['cod']);
if($id_selecao != $_SESSION['selecao'])
{
    $conexao = null;
    erro("Erro 36326346! Não foi possível atualizar as datas");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    $conexao = null;
    erro("Erro 23462346346! Não foi possível atualizar as datas da inscrição");
    exit();
}

$data_inicio_recurso = trim($_POST['data_inicio_recurso']);
$data_fim_recurso = trim($_POST['data_fim_recurso']);

if($data_inicio_recurso == null || $data_fim_recurso == null)
{
    $conexao = null;
    erro("Erro 46454567! A data de Início e Final são obrigatórias!");
    exit();    
}

if($data_inicio_recurso == $data_fim_recurso)
{
    $conexao = null;
    erro("Erro 534253246! As datas não podem ser iguais!");
    exit();    
}

if(!valida_data($data_inicio_recurso))
{
    $conexao = null;
    erro("Erro 35345! Data de INÍCIO inválida!");
    exit();    
}

if(!valida_data($data_fim_recurso))
{
    $conexao = null;
    erro("Erro 34234343! Data FINAL inválida!");
    exit();  
}

$ini = $data_inicio_recurso;
$fim = $data_fim_recurso;

$data_inicio_recurso = reverte_data($data_inicio_recurso);
$data_fim_recurso    = reverte_data($data_fim_recurso);


if(strtotime($data_inicio_recurso) >= strtotime($data_fim_recurso))
{
    $conexao = null;
    erro("Erro 3246346! A data de início tem que ser menor do que a data final.");
    exit();
}

$resultado_selecao = $conexao->get_selecao_id();

$nome_selecao = $resultado_selecao[0]['nome'] . " - " . $resultado_selecao[0]['ano'];

if($_POST)
    $resultado = $conexao->selecao_atualiza_data_recurso($data_inicio_recurso,$data_fim_recurso);
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "161502", "selecao", "Update", "Atualizou as datas para dar permissão para o candidato selecionar a recurso onde quer servir para: $ini até $fim", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 34634346 Datas não atualizadas!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?datas_atualizadas=1");
exit();

?>



