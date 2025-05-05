<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 890784!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 8908963!");
    exit();
}

if(($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'documentos') || $_SESSION['candidato'] == '1')
{
    erro("Erro 67873465673!");
    exit();
}

if( $_POST['criptografia'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 7895663!");
    exit();
}

$id_usuario = $_POST['id_usuario'];
$isento = 0;

if($_POST['isento'] == '' || $_POST['isento'] == null)
{
    erro("Erro 763! Opção não selecionada!");
    exit();
}

if($_POST['isento'] == '1')
    $isento = 1;

if($id_usuario == "" || $id_usuario == null)
{
    erro("Erro 46345!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_usuario);

if(count($get_candidato) != 1)
{
    erro("Erro 64263!");
    exit();
}

if($get_candidato[0]['isento_pagamento'] != null && $get_candidato[0]['isento_pagamento'] == $isento)
{
    erro("Erro 346456! O status não foi alterado!");
    exit();
}

$candidato_isento = "ISENTO DE PAGAMENTO";
if($isento == 0)
    $candidato_isento = "NÃO ISENTO DE PAGAMENTO";

// Muda Status isento

$resultado_isento = $conexao->status_isento_pagamento($id_usuario,$isento);
$alteracoes_detalhadas =  print_r($resultado_isento, true);
if($resultado_isento)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16122", "usuario", "Update", "Alterou o status de isento para $candidato_isento do candidato: ".$get_candidato[0]['cpf'], "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 423634! O status não mudou!");
    exit();
}

$observacao = " Cod 74114 - Alterou o status de isento de pagamento para $candidato_isento!";
$resultado = $conexao->cadastra_observacao_candidato($id_usuario,$observacao,1);
$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "14120", "observacao", "Insert", "Inseriu uma observação no candidato: ".$get_candidato[0]['cpf'], "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 42433 Observação não cadastrada!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#isento");
exit();

?>



