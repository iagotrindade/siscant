<?php	

include_once '../sistema/funcoes.php';
include_once '../sistema/codigos/funcao_mostrar_recurso.php';
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
/*
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
*/
session_start(); // Inicia a sessão

$mostrar_recurso = $_POST['mostrar_recurso'];
$mostrar_recurso = (int)$mostrar_recurso;
$rm = $_POST['rm'];


$data_inicio_recurso = $_POST['data_inicio_recurso'];
$datetime = DateTime::createFromFormat('d/m/Y', $data_inicio_recurso);
$data_inicio_recurso_formatada = $datetime->format('Y-m-d');

$data_fim_recurso = $_POST['data_fim_recurso'];
$datetime = DateTime::createFromFormat('d/m/Y', $data_fim_recurso);
$data_fim_recurso_formatada = $datetime->format('Y-m-d');


//if($mostrar_recurso == "um") $mostrar_recurso = 1;
if($mostrar_recurso == null) $mostrar_recurso = 0;


$id_usuario = $_SESSION['id_usuario'];
$rm_usuario = $conexao->rm_usuario($id_usuario); 
$resultado_selecao = $conexao->get_selecao_id();
$id_selecao = $resultado_selecao[0]['id'];
$id_selecao = (int)$id_selecao;
$verifica_existencia = $conexao->get_recurso_visualiza($id_selecao, $rm_usuario);

if($verifica_existencia) {

    $resultado = $conexao->update_recurso_visualiza(
        $id_selecao,
        $rm,
        $data_inicio_recurso_formatada,
        $data_fim_recurso_formatada,
        $mostrar_recurso
    );

    $alteracoes_detalhadas =  print_r($resultado, true);

    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "161502", "selecao", "Update", "Atualizou as datas para dar permissão para o candidato selecionar a recurso onde quer servir para: $ini até $fim", "$alteracoes_detalhadas");
    
    header("Location: ../sistema/configuracoes_recursos.php?datas_atualizadas=1");
    exit();
}

else {

    $resultado = $conexao->insert_recurso_visualiza(
        $id_selecao,
        $rm,
        $data_inicio_recurso_formatada,
        $data_fim_recurso_formatada,
        $mostrar_recurso);
                    
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "161502", "selecao", "Update", "Atualizou as datas para dar permissão para o candidato selecionar a recurso onde quer servir para: $ini até $fim", "$alteracoes_detalhadas");


header("Location: ../sistema/configuracoes_recursos.php?datas_atualizadas=1");
exit();
}



 

?>



