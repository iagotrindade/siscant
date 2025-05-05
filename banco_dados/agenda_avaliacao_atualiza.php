<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 5623444!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 403924! Não foi possível atualizar as datas da inscrição");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro 47862924! Não foi possível atualizar as datas da inscrição");
    exit();
}

if( $_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    $conexao = null;
    erro("Erro 474! Não foi possível atualizar as datas da inscrição");
    exit();
}

$id_selecao = trim($_POST['cod']);
if($id_selecao != $_SESSION['selecao'])
{
    $conexao = null;
    erro("Erro 403924! Não foi possível atualizar as datas da inscrição");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    $conexao = null;
    erro("Erro 40323423924! Não foi possível atualizar as datas da inscrição");
    exit();
}

$data_inicio_avaliacoes = trim($_POST['data_inicio_avaliacao']);
$data_fim_avaliacoes = trim($_POST['data_fim_avaliacao']);

if($data_inicio_avaliacoes == null || $data_fim_avaliacoes == null)
{
    $conexao = null;
    erro("Erro 3234345224! A data de Início e Final são obrigatórias!");
    exit();    
}
if($data_inicio_avaliacoes == $data_fim_avaliacoes)
{
    $conexao = null;
    erro("Erro 4234524! As datas não podem ser iguais!");
    exit();    
}

if(!valida_data($data_inicio_avaliacoes))
{
    $conexao = null;
    erro("Erro 425624! Data de INÍCIO inválida!");
    exit();    
}

if(!valida_data($data_fim_avaliacoes))
{
    $conexao = null;
    erro("Erro 4245624! Data FINAL inválida!");
    exit();  
}

$ini = $data_inicio_avaliacoes;
$fim = $data_fim_avaliacoes;

$data_inicio_avaliacoes = reverte_data($data_inicio_avaliacoes);
$data_fim_avaliacoes    = reverte_data($data_fim_avaliacoes);


if(strtotime($data_inicio_avaliacoes) >= strtotime($data_fim_avaliacoes))
{
    $conexao = null;
    erro("Erro 84! A data de início tem que ser menor do que a data final.");
    exit();
}





$nome_selecao = $resultado_selecao[0]['nome'] . " - " . $resultado_selecao[0]['ano'];

if($_POST)
    $resultado = $conexao->selecao_atualiza_data_avaliacao($data_inicio_avaliacoes,$data_fim_avaliacoes);
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "16119", "selecao", "Update", "Atualizou as datas da avaliação curricular para: $ini até $fim", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 45343684 Datas não atualizadas!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?datas_atualizadas=1");
exit();

?>



