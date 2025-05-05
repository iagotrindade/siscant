<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();



if(!$_POST)
{
    erro_mensagem("Erro 2346456!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 2436456!");
    exit();
}

if(($_SESSION['perfil'] != 'admin'))
{
    erro("Erro 2345!");
    exit();
}

$get_selecao = $conexao->get_selecao_id();
$etapa_selecao = $get_selecao[0]['etapa'];


$id_usuario = (int)$_POST['id_usuario'];

if( $_POST['criptografia'] != hash('sha256', $_SESSION['chave'].$id_usuario."freitas"))
{
    erro("Erro 3467347357!");
    exit();
}

$etapa = 0;
$etapa = (int)$_POST['etapa_candidato'];

if($_POST['etapa_candidato'] == '' || $_POST['etapa_candidato'] == null || $etapa == 0)
{
    erro("Erro 234235! Opção não selecionada!");
    exit();
}


if ($_SESSION['eipot'] != 1) {
    
    if ($etapa > $etapa_selecao) {
        erro("Erro 345636457! A etapa selecionada é maior do que a etapa da seleção!");
        exit();
    }
}


if($id_usuario == "" || $id_usuario == null || $id_usuario == 0)
{
    erro("Erro 3246246456!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_usuario);

if(count($get_candidato) != 1)
{
    erro("Erro 4563464356!");
    exit();
}

if($get_candidato[0]['concorrendo'] != 1)
{
    erro("Erro 3456365! O candidato deve estar concorrendo no processo seletivo para mudar ele de etapa!");
    exit();
}

$candidato_etapa = $get_candidato[0]['etapa'];
$candidato_cpf = $get_candidato[0]['cpf'];

// Muda Status isento

$resultado_isento = $conexao->altera_etapa_candidato($id_usuario,$etapa);
$alteracoes_detalhadas =  print_r($resultado_isento, true);
if($resultado_isento)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16127", "usuario", "Update", "Alterou a etapa do candidado $candidato_cpf de ETAPA: $candidato_etapa para ETAPA: $etapa", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 3456346456! A Etapa não mudou!");
    exit();
}

$observacao = " Cod 24578 - Alterou a etapa de $candidato_etapa para $etapa!";
$resultado = $conexao->cadastra_observacao_candidato($id_usuario,$observacao,1);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "14120", "observacao", "Insert", "Inseriu uma observação no candidato: ".$get_candidato[0]['cpf'], "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 3467347654 Observação não cadastrada!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#concorrendo");
exit();

?>



