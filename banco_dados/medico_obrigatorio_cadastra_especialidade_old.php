<?php
include_once '../sistema/funcoes.php';
include_once 'conexao.php';
session_start();
$datetime = date('Y-m-d H:i:s');

$especialidade=null;

if(!$_POST)
{
    erro_mensagem("Erro 456347347!");
    exit();
}

if ($_POST['especialidade'] == null || $_POST['especialidade'] == "")
{
    erro("Erro 2314525! O campo especialidade é obrigatório!");
    exit();
}
    
 if($_POST['especialidade'] != "")
    $especialidade = htmlspecialchars($_POST['especialidade']);
 
$cpf_medico = $_POST['c_p_f_medico'];   
$id_medico = $_POST['id_medico'];

if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    erro("Erro 347347437! Não foi possível fazer o cadastro!"); 
    exit(); 
}
    
if($especialidade == null || $especialidade == '')
{
    erro("Erro 34264367! Nenhuma especialidade selecionada!");
    exit();
}

if(!isset($_SESSION['selecao']))
{
    erro("Erro 5367547547! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if(!isset($_SESSION['chave']))
{
    erro("Erro 34573756! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 234235235! Não é possivel fazer o cadastro!"); 
    exit();
}

$conexao = new Conexao();


$verifica_candidato_especialidade = $conexao->verifica_especialidade_candidato($id_medico, $especialidade);
if(count($verifica_candidato_especialidade) > 0)
{
    erro("Erro 4856! Médico já cadastrado na especialidade");
    exit();
}    

$get_especialidade = $conexao->get_especialidade_id($especialidade);


$get_candidato = $conexao->get_usuario_id($id_medico);

if($get_candidato[0]['cpf'] != $cpf_medico)
{
    erro("Erro 532453245! Não foi possível fazer o cadastro");
    exit();
}    

if($_POST)
    $resultado = $conexao->insere_especialidade_candidato($id_medico,$especialidade,null);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "14109", "candidato_x_especialidade", "Insert", "Operador ".$_SESSION['cpf']." cadastrou a especialidade ".$get_especialidade[0]['nome'] . " para o médico obrigatório $cpf_medico", $alteracoes_detalhadas);

$conexao = null;

header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=especialidade#especialidade");

        

?>