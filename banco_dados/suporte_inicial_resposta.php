<?php

session_start();

if(!$_POST)
{
    erro_mensagem("Erro 6876587644!");
    exit();
}

if($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'ouvidor')
{
    erro("Erro 456432414! Você não tem permissão!");
    exit();
}

include_once '../sistema/funcoes.php';

$resposta=null;
$id_suporte=null;

$nome_completo_requerente=null;
$cpf_requerente=null;


if ($_POST['resposta'] == "")
{
    erro("O campo resposta é obrigatório!");
    exit();
}
if ($_POST['id_suporte'] == "")
{
    erro("Erro: 456465 Algo errado não está certo!");
    exit();
}
    
 if($_POST['resposta'] != "")
    $resposta = htmlspecialchars(trim($_POST['resposta']));
 
  if($_POST['id_suporte'] != "")
    $id_suporte = $_POST['id_suporte'];

$criptografia = $_POST['criptografia'];

if($criptografia != hash('sha256', $id_suporte))
{
    erro("Erro: 453465 Algo errado não está certo!");
    exit();    
}

if($resposta == "" || $resposta == null)
{
    erro("Erro: 3254! O campo resposta é obrigatório");
    exit();    
}

  
include_once 'conexao.php';

$datetime = date('Y-m-d H:i:s');

$conexao = new Conexao();

$get_suporte_inicial_id = $conexao->get_suporte_inicial_id($id_suporte);  

if($get_suporte_inicial_id == null )
{
    erro("Erro: 455 Algo errado não está certo!");
    exit();
}

$nome_completo_requerente = $get_suporte_inicial_id[0]['nome_completo'];  
$cpf_requerente = $get_suporte_inicial_id[0]['cpf'];  
$mensagem_requerente = $get_suporte_inicial_id[0]['mensagem'];  
$data_enviado_requerente = $get_suporte_inicial_id[0]['data_enviado'];  
$mail = $get_suporte_inicial_id[0]['mail'];  

if(!isset($_SESSION['id_usuario']))
{
    erro("Erro 15235! A sua sessão expirou! Faça novamente o registro");
    exit();
}
if(!isset($_SESSION['cpf']))
{
    erro("Erro 15123645! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}
if($_SESSION['candidato'] == 1)
{
    erro("Erro 6554656! A resposta não pode ser enviada!");
    exit();
}

if($id_suporte != null && $resposta != null)
{
    if($_POST)
        $resultado = $conexao->insere_resposta_inicial($id_suporte,$resposta);
    $alteracoes_detalhadas =  print_r($resultado, true);
    if($resultado)
    {
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_suporte, "16105", "suporte", "Update", "Respondeu suporte inicial do CPF $cpf_requerente", $alteracoes_detalhadas);
        if($mail != null)
            include_once './mail_resposta_suporte.php';
    }
    
    $conexao = null;
    header ("Location: ../sistema/suporte_inicial_visualiza.php?criptografia=$criptografia&id_suporte=$id_suporte");
}
        

?>