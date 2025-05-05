<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 484465422!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 78945615!");
    exit();
}

if(($_SESSION['perfil'] != 'om'))
{
    erro("Erro 46541515!");
    exit();
}

$get_selecao = $conexao->get_selecao_id();
$etapa_selecao = $get_selecao[0]['etapa'];


$id_usuario = (int)$_POST['id_usuario'];

if( $_POST['criptografia'] != hash('sha256', $_SESSION['chave'].$id_usuario."freitas"))
{
    erro("Erro 4894564165!");
    exit();
}



if($_POST['apresentacao_candidato_om'] == null || $_POST['observacao_om'] == null)
{
    erro("Erro 2135627457! Os dois campos são obrigatórios!");
    exit();
}

$apresentacao_candidato_om = $_POST['apresentacao_candidato_om'];
$observacao_om = $_POST['observacao_om'];

if($id_usuario == "" || $id_usuario == null || $id_usuario == 0)
{
    erro("Erro 3246246456!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_usuario);

if(count($get_candidato) != 1)
{
    erro("Erro 43257357547!");
    exit();
}

if($get_candidato[0]['concorrendo'] != 1)
{
    erro("Erro 3264327567! Candidato não concorrendo no processo seletivo!");
    exit();
}

$candidato_cpf = $get_candidato[0]['cpf'];

// Muda Status isento

$resultado_isento = $conexao->om_informacoes_atualiza($id_usuario,$apresentacao_candidato_om,$observacao_om);
$alteracoes_detalhadas =  print_r($resultado_isento, true);
if($resultado_isento)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "161505", "usuario", "Update", "Alterou as informações de apresentação do candidado $candidato_cpf para $apresentacao_candidato_om ", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 125326436! Atualização não efetuada!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario");
exit();

?>



