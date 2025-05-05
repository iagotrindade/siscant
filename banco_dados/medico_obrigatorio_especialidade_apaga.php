<?php
include_once '../sistema/funcoes.php';
session_start();

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 32536457! Somente o administrador pode realizar essa edição!");
    exit();
}

if($_POST['crip'] !=  hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    erro("Erro 848456! Não foi possível apagar a especialidade!"); 
    exit(); 
}



$cpf = $_SESSION['cpf'];
    
$cpf_medico = $_POST['c_p_f_medico'];
$id_medico = $_POST['id_medico'];
$nome_especialidade = $_POST['nome_esp_medico'];
$id_especialidade = $_POST['id_especialidade'];

include_once 'conexao.php';
$conexao = new Conexao();


$get_medico_obrigatorio = $conexao->get_usuario_id($id_medico);

if($get_medico_obrigatorio[0]['cpf'] != $cpf_medico)
{
    erro("Erro 3473474357! Não foi possível fazer a atualização dos dados !"); 
    exit(); 
}
if($get_medico_obrigatorio[0]['medico_obrigatorio'] != 1)
{
    erro("Erro 347347437! Não foi possível fazer a atualização dos dados !"); 
    exit(); 
}

$resultado = $conexao->apaga_especialidade_candidato($id_especialidade,$id_medico);
$alteracoes_detalhadas = print_r($resultado, true);
if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_medico, $id_especialidade, "15104", "candidato_x_especialidade", "Delete", "Usuário ".$_SESSION['cpf']." apagou a especialidade $nome_especialidade do médico obrigatório $cpf_medico", $alteracoes_detalhadas);

$conexao = null;
header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=especialidade#especialidade");

?>