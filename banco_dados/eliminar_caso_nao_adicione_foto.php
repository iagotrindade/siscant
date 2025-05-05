<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 623462346!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 234632462346! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 274256742364! Você não tem permissão!");
    exit();
}

$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 236326346! Não foi possível alterar a configuração!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_selecao = $conexao->get_selecao_id();
$eliminar_caso_nao_adicione_foto = (int)$get_selecao[0]['eliminar_caso_nao_adicione_foto'];

if($eliminar_caso_nao_adicione_foto == 1 && isset($_POST['eliminar']))
{
    erro("Erro 234643646! Status não alterado"); 
    exit();
}
if($eliminar_caso_nao_adicione_foto == 0 && !isset($_POST['eliminar']))
{
    erro("Erro 34757858! Status não alterado"); 
    exit();
}

if(isset($_POST['eliminar']))
    $elimina = 1;
else
    $elimina = 0;


$alteracao = "";
if($eliminar_caso_nao_adicione_foto && !isset($_POST['eliminar']))
    $alteracao = "NÃO eliminnar";
if(!$eliminar_caso_nao_adicione_foto && isset($_POST['eliminar']))
    $alteracao = "ELIMINAR";

$resultado = $conexao->elimina_caso_nao_colocou_foto($elimina);
$alteracoes_detalhadas =  print_r($resultado, true);
if($resultado)
{
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['id_usuario'], "16144", "selecao", "Update", "Alterou a eliminalção do candidato caso ele não tenha colocado foto para $alteracao", $alteracoes_detalhadas);
    header ("Location: ../sistema/configuracao_selecao.php?sucesso=avaliacao_curricular");
    exit();
}
else 
{
    erro("Erro 436347547! Status não alterado"); 
    exit();
}





?>