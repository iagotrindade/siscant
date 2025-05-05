<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 236784!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 24578963!");
    exit();
}

if(inscricao())
{
    erro("Erro 42356734757! Inscrições em andamento");
    exit();
}

if(($_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != "avaliador") || $_SESSION['candidato'] == '1')
{
    erro("Erro 235475673!");
    exit();
}

$id_usuario = $_POST['id_usuario'];
$id_candidato_x_especialidade = $_POST['id_candidato_x_especialidade'];
$justificativa = trim($_POST['justificativa']);
$id_especialidade = $_POST['id_especialidade'];
$concorrendo = 0;

if( $_POST['criptografia'] != hash('sha256', $_SESSION['chave'].$id_usuario."freitas"))
{
    erro("Erro 2344263!");
    exit();
}

if(isset($_POST['concorrendo']))
    $concorrendo = 1;

if($justificativa == "" || $justificativa == null)
{
    erro("Erro 2323454234! A Justificativa é obrigatória!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_usuario);

if(count($get_candidato) != 1)
{
    erro("Erro 64263!");
    exit();
}

$usuario_concorrendo = false;
if($get_candidato[0]['concorrendo'] == 1)
    $usuario_concorrendo = true;

$get_candidato_x_especialidade_id = $conexao->get_candidato_x_especialidade_id($id_candidato_x_especialidade);

if($get_candidato_x_especialidade_id[0]['concorrendo'] == $concorrendo)
{
    erro("Erro 346456! O status não foi alterado!");
    exit();
}
if($get_candidato_x_especialidade_id[0]['id_candidato'] != $id_usuario)
{
    erro("Erro 34634534456! O status não foi alterado!");
    exit();
}
if($get_candidato_x_especialidade_id[0]['id_especialidade'] != $id_especialidade)
{
    erro("Erro 34634534456! O status não foi alterado!");
    exit();
}


if($_SESSION['perfil'] == 'avaliador')
{
    $lista_especialidade_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);
    $avaliador_pode_avaliar_id_especialidade = false;
    
    foreach ($lista_especialidade_avaliador as &$linha_avaliador) 
    {
        if($linha_avaliador['id_especialidade'] == $id_especialidade)
            $avaliador_pode_avaliar_id_especialidade = true;
    }
    if(!$avaliador_pode_avaliar_id_especialidade)
    {
        erro("Erro 48923543! Você não tem permissão para avaliar essa especialidade!");
        exit();
    }
}

$candidato_concorrendo = "CONCORRENDO";
if($concorrendo == 0)
    $candidato_concorrendo = "DESCLASSIFICADO";

// Muda Status concorrendo

$nome_especialidade = "";
$get_especialidade_id = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $get_especialidade_id[0]['nome'];

$resultado_concorrendo = $conexao->status_concorrendo_especialidade($id_candidato_x_especialidade, $concorrendo, $justificativa);
$alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
if($resultado_concorrendo)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16120", "candidato_x_especialidade", "Update", "Alterou o status do candidato ".$get_candidato[0]['cpf']." para $candidato_concorrendo na especialidade $nome_especialidade! Justificativa: $justificativa", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4575634 O status não mudou!");
    exit();
}


$get_especialidade_candidato = $conexao->get_especialidade_candidato($id_usuario);

$esta_concorrendo_em_outa_especialidade = false;
foreach ($get_especialidade_candidato as &$especialidade)
{
    if($especialidade['concorrendo'] === '1')
    {
        $esta_concorrendo_em_outa_especialidade = true;
        break;
    }
}

if($esta_concorrendo_em_outa_especialidade == false)
{
    $observacao = "Não está concorrendo em nenhuma especialidade! Justificativa: $justificativa";
    $resultado_concorrendo = $conexao->status_concorrendo($id_usuario,0, $observacao);
    $alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
    if($resultado_concorrendo)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16116", "usuario", "Update", "Alterou o status do candidato ".$get_candidato[0]['cpf']." para DESCLASSIFICADO no processo seletivo! Justificativa: $observacao", "$alteracoes_detalhadas");
    else
    {
        $conexao = null;
        erro("Erro 423345634 Não mudou o status!");
        exit();
    }
}

if($usuario_concorrendo == false && $esta_concorrendo_em_outa_especialidade == true)
{
    $observacao = "Voltou a concorrer na especialidade $nome_especialidade! Justificativa: $justificativa";
    $resultado_concorrendo = $conexao->status_concorrendo($id_usuario,1, $observacao);
    $alteracoes_detalhadas =  print_r($resultado_concorrendo, true);
    if($resultado_concorrendo)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16116", "usuario", "Update", "Alterou o status do candidato ".$get_candidato[0]['cpf']." para CONCORRENDO no processo seletivo! Justificativa: $observacao", "$alteracoes_detalhadas");
    else
    {
        $conexao = null;
        erro("Erro 423345634 Não mudou o status!");
        exit();
    }
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#concorrendo_especialidade_id_$id_especialidade");
exit();

?>



