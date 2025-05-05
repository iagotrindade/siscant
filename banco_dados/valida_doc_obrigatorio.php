<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 5674644!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 4567263!");
    exit();
}

if($_SESSION['perfil'] != 'documentos' && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 53457!");
    exit();
}

if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
    erro("Erro 45633!");
    exit();
}

if( $_POST['criptografia'] !=  hash('sha256', $_SESSION['chave']."freitas".$_POST['id_documento_obrigatorio']))
{
    erro("Erro 3453453!");
    exit();
}

if(!avaliacao() && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 4568456! Você está fora do periodo definido para avaliação de currículo");
    exit();
}

/*
if(inscricao())
{
    erro("Erro 456456! As Inscrições ainda estão abertas");
    exit();
}
*/

$id_documentacao_obrigatoria = $_POST['id_documento_obrigatorio'];
$valido = (int)$_POST['valido'];
$id_usuario = $_POST['id_candidato'];
$justificativa = trim($_POST['justificativa']);

$candiato = $conexao->get_usuario_id($id_usuario);
$cpf_candidato = $candiato[0]['cpf'];

$documento_obrigatorio = $conexao->get_documentos_obrigatorios_id($id_documentacao_obrigatoria);
$nome_documento_obrigatorio = $documento_obrigatorio[0]['label'];

if($valido == 0 && $justificativa == null)
{
    erro("Erro 23423! A justificativa é obrigatória para invalidar um documento!");
    exit();
}
else if($valido == 1 && $justificativa != null)
{
    erro("Erro 23423! Não pode existir uma justificativa para documentos válidos!");
    exit();
}

$id_usuario_docs = $documento_obrigatorio[0]['id_candidato'];

if($id_usuario_docs != $id_usuario)
{
    erro("Erro 457455457!");
    exit();
}
if($documento_obrigatorio[0]['apagado'] == 1)
{
    erro("Erro 644567!");
    exit();
}

if($valido == 1)
    $valido_invalido = "VALIDOU";
if($valido == 0)
    $valido_invalido = "INVALIDOU";

$resultado = $conexao->avalia_doc_obrigatorio($id_documentacao_obrigatoria, $valido, $justificativa);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16123", "documento_obrigatorio", "Update", "$valido_invalido o doc obrigatório $nome_documento_obrigatorio do candidato: ".$cpf_candidato, "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 423576436!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#avaliacao_doc_obrigatorio");
exit();

?>



