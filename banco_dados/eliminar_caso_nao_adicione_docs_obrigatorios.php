<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 26747437!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 2457347357! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 3473457457! Você não tem permissão!");
    exit();
}

$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 34573457357! Não foi possível alterar a configuração!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$eliminar_docs_obrigatorios = (int)$get_selecao[0]['eliminar_docs_obrigatorios'];

if($eliminar_docs_obrigatorios == 1 && isset($_POST['eliminar']))
{
    erro("Erro 23623462346! Status não alterado"); 
    exit();
}
if($eliminar_docs_obrigatorios == 0 && !isset($_POST['eliminar']))
{
    erro("Erro 2674274567! Status não alterado"); 
    exit();
}

if(isset($_POST['eliminar']))
    $elimina = 1;
else
    $elimina = 0;


$alteracao = "";
if($eliminar_docs_obrigatorios && !isset($_POST['eliminar']))
    $alteracao = "NÃO eliminnar";
if(!$eliminar_docs_obrigatorios && isset($_POST['eliminar']))
    $alteracao = "ELIMINAR";

$resultado = $conexao->elimina_caso_nao_colocou_todos_docs_obrigatorios($elimina);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16145", "selecao", "Update", "Alterou a eliminalção do candidato caso ele não tenha colocado todos os documentos obrigatórios para $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=avaliacao_curricular");
    exit();
}
else 
{
    erro("Erro 73473475! Status não alterado"); 
    exit();
}





?>