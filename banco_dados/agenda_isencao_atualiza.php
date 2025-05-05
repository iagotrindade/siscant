<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 564364!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 403567924! Não foi possível atualizar as datas da isenção");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro 478546754624! Não foi possível atualizar as datas da isenção");
    exit();
}

if( $_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    $conexao = null;
    erro("Erro 473454! Não foi possível atualizar as datas da isenção");
    exit();
}

$id_selecao = trim($_POST['cod']);
if($id_selecao != $_SESSION['selecao'])
{
    $conexao = null;
    erro("Erro 403345924! Não foi possível atualizar as datas da isenção");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    $conexao = null;
    erro("Erro 4345924! Não foi possível atualizar as datas da inscrição");
    exit();
}

$data_inicio_isencao = trim($_POST['data_inicio_isencao']);
$data_fim_isencao = trim($_POST['data_fim_isencao']);

if($data_inicio_isencao == null || $data_fim_isencao == null)
{
    $conexao = null;
    erro("Erro 3234224! A data de Início e Final são obrigatórias!");
    exit();    
}
if($data_inicio_isencao == $data_fim_isencao)
{
    $conexao = null;
    erro("Erro 4234524! As datas não podem ser iguais!");
    exit();    
}

if(!valida_data($data_inicio_isencao))
{
    $conexao = null;
    erro("Erro 4245624! Data de INÍCIO inválida!");
    exit();    
}

if(!valida_data($data_fim_isencao))
{
    $conexao = null;
    erro("Erro 4346224! Data FINAL inválida!");
    exit();  
}

$ini = $data_inicio_isencao;
$fim = $data_fim_isencao;

$data_inicio_isencao = reverte_data($data_inicio_isencao);
$data_fim_isencao    = reverte_data($data_fim_isencao);


if(strtotime($data_inicio_isencao) >= strtotime($data_fim_isencao))
{
    $conexao = null;
    erro("Erro 8454! A data de início tem que ser menor do que a data final.");
    exit();
}



$resultado_selecao = $conexao->get_selecao_id();

$nome_selecao = $resultado_selecao[0]['nome'] . " - " . $resultado_selecao[0]['ano'];

if($_POST)
    $resultado = $conexao->selecao_atualiza_data_isencao($data_inicio_isencao,$data_fim_isencao);
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "16121", "selecao", "Update", "Atualizou as datas da isenção para: $ini até $fim", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4534 Datas não atualizadas!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?datas_atualizadas=1");
exit();

?>



