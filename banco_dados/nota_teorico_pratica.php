<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 65896597979!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 780678658678!");
    exit();
}

if($_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 709789768978!");
    exit();
}

if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
    erro("Erro 068787809!");
    exit();
}
/*
if( $_POST['criptografia'] !=  hash('sha256', $_SESSION['chave']."freitas".$_POST['id_especialidade_curriculo']))
{
    erro("Erro 47468569679!");
    exit();
}
*/
if(inscricao())
{
    erro("Erro 70780809!As Inscrições ainda estão abertas");
    exit();
}

if(!avaliacao())
{
    erro("Erro 56797808934! Não foi aberto para avaliação ainda");
    exit();
}

$id_especialidade_curriculo = $_POST['id_especialidade_curriculo'];
$id_candidato_x_especialidade = $_POST['id_candidato_x_especialidade'];
$id_usuario = $_POST['id_usuario'];
$id_especialidade = $_POST['id_especialidade'];

$pontuacao_teorico_pratica = $_POST['pontuacao_teorico_pratica'];

if($pontuacao_teorico_pratica == "" || $pontuacao_teorico_pratica == null || $pontuacao_teorico_pratica == '00.00')
    $pontuacao_pratica = 0;

$candiato = $conexao->get_usuario_id($id_usuario);
$cpf_candidato = $candiato[0]['cpf'];

$candidato_x_especialidade = $conexao->get_candidato_x_especialidade_id($id_candidato_x_especialidade);

$especialidade = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $especialidade[0]['nome'];

if($candidato_x_especialidade[0]['id_candidato'] != $id_usuario)
{
    erro("Erro 679679789!");
    exit();
}

if($candidato_x_especialidade[0]['id_especialidade'] != $id_especialidade)
{
    erro("Erro 78097809870!");
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
        erro("Erro 579768087975! Você não tem permissão para avaliar essa especialidade!");
        exit();
    }
}

$resultado = $conexao->adiciona_nota_teorico_pratico($id_candidato_x_especialidade, $pontuacao_teorico_pratica);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "161504", "candidato_x_especialidade", "Update", "Adicionou nota para prova teórico prática $pontuacao_teorico_pratica especialização $nome_especialidade do candidato: ".$cpf_candidato, "$alteracoes_detalhadas");
else
{
    $conexao = null;
    //erro("Erro 4545736!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#avaliacao_id_$id_especialidade");
exit();

?>



