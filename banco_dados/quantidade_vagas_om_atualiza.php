<?php

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 236234643643!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 234624364645! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 274373467! Você não tem permissão!");
    exit();
}


$criptografia = $_POST['crip'];

if($criptografia != hash('sha256', $_SESSION['chave']."vagas"))
{
    erro("Erro 6426457457! Não foi possível fazer a atualização!"); 
    exit(); 
}

$id_especialidade = (int)$_POST['id_especialidade'];
$id_om = (int)$_POST['om'];
$id_selecao = $_SESSION['selecao'];
$quantidade_vagas = (int)$_POST['quantidade_vagas'];


if($id_especialidade == 0)
{
    erro("Erro 473475756! Não foi possível fazer a atualização!"); 
    exit(); 
}
if($id_om == 0)
{
    erro("Erro 473475475! OM não selecionada!"); 
    exit(); 
}

include_once 'conexao.php';
$conexao = new Conexao();

$get_om_especialidade = $conexao->get_nome_especialidade_por_id_selecao($id_selecao);
$nome_especialidade = $get_om_especialidade[0]['nome'];

$consulta = $conexao->consulta_especialidade_tabela_om_x_especialidade($id_om, $id_especialidade);

if($consulta === true) 
{
    $resultado = $conexao->numero_vagas_om_especialidade_atualiza($id_especialidade, $id_om, $quantidade_vagas);
 //   var_dump($resultado); exit;
    $alteracoes_detalhadas =  print_r($resultado, true); 
    if($resultado)
    {
      /*  $insere_log = $conexao->insere_log($_SESSION['id_usuario'], 
                                            $_SESSION['cpf'], 
                                            $_SESSION['id_usuario'], 
                                            "16143", 
                                            "om_x_especialidade", 
                                            "Update", 
                                            "Alterou o nº de vagas para $quantidade_vagas da especialidade $nome_especialidade", 
                                            $alteracoes_detalhadas); */
        header ("Location: ../sistema/especialidade_eipot_editar.php?id_especialidade=$id_especialidade");
        exit();
    }
    else 
    {
        erro("Erro 4573858757! Atualização não realizada"); 
        exit();
    }
}

if($consulta === false) 
{    
    
    $resultado = $conexao->numero_vagas_om_especialidade_insere($id_especialidade, $id_om, $quantidade_vagas);
    $alteracoes_detalhadas =  print_r($resultado, true); 
    if($resultado)
    {
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], 
                                            $_SESSION['cpf'], 
                                            $_SESSION['id_usuario'], 
                                            "16143", 
                                            "om_x_especialidade", 
                                            "Insert", 
                                            "Inseriu o nº de vagas para $quantidade_vagas da especialidade $nome_especialidade", 
                                            $alteracoes_detalhadas);
        header ("Location: ../sistema/especialidade_eipot_editar.php?id_especialidade=$id_especialidade");
        exit();
    }
    else 
    {
        erro("Erro 65489762316! Inserção não realizada"); 
        exit();
    }

}




?>