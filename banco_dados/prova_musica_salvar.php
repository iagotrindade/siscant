<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 234234644!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 24567865783!");
    exit();
}

if($_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 454353455!");
    exit();
}

if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
    erro("Erro 5348676563!");
    exit();
}

if($_POST['criptografia'] !=  hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 234274575463!");
    exit();
}

if(inscricao())
{
    erro("Erro 23546757!As Inscrições ainda estão abertas");
    exit();
}

if(!avaliacao())
{
    erro("Erro 2354! Não foi aberto para avaliação ainda");
    exit();
}

$id_especialidade_curriculo = $_POST['id_especialidade_curriculo'];
$id_candidato_x_especialidade = $_POST['id_candidato_x_especialidade'];
$id_usuario = $_POST['id_usuario'];
$id_especialidade = $_POST['id_especialidade'];

$pontuacao_pratica = $_POST['pontuacao_pratica'];
$pontuacao_oral = $_POST['pontuacao_oral'];
$pontuacao_teorica = $_POST['pontuacao_teorica'];

if($pontuacao_pratica == "" || $pontuacao_pratica == null || $pontuacao_pratica == '00.00')
    $pontuacao_pratica = 0;

if($pontuacao_oral == "" || $pontuacao_oral == null || $pontuacao_oral == '00.00')
    $pontuacao_oral = 0;

if($pontuacao_teorica == "" || $pontuacao_teorica == null || $pontuacao_teorica == '00.00')
    $pontuacao_teorica = 0;


$candiato = $conexao->get_usuario_id($id_usuario);
$cpf_candidato = $candiato[0]['cpf'];

$candidato_x_especialidade = $conexao->get_candidato_x_especialidade_id($id_candidato_x_especialidade);

$especialidade = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $especialidade[0]['nome'];

if($candidato_x_especialidade[0]['id_candidato'] != $id_usuario)
{
    erro("Erro 78239749!");
    exit();
}

if($candidato_x_especialidade[0]['id_especialidade'] != $id_especialidade)
{
    erro("Erro 3463456!");
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
        erro("Erro 48890890543! Você não tem permissão para avaliar essa especialidade!");
        exit();
    }
}

$pontuacao_provas = null;

if($pontuacao_oral == '00.00')    $pontuacao_oral = 0;
if($pontuacao_teorica == '00.00') $pontuacao_oral = 0;
if($pontuacao_pratica == '00.00') $pontuacao_oral = 0;

$pontuacao_provas = $pontuacao_provas. " Pontuação Oral: $pontuacao_oral " ;
$pontuacao_provas = $pontuacao_provas. " Pontuação Teórica: $pontuacao_teorica " ;
$pontuacao_provas = $pontuacao_provas. " Pontuação Prática: $pontuacao_pratica " ;


$resultado = $conexao->avalia_provas_musica($id_candidato_x_especialidade, $pontuacao_oral, $pontuacao_teorica, $pontuacao_pratica);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16118", "candidato_x_especialidade", "Update", "Avaliou prova de música $pontuacao_provas especialização $nome_especialidade do candidato: ".$cpf_candidato, "$alteracoes_detalhadas");
else
{
    $conexao = null;
    //erro("Erro 4545736!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#avaliacao_id_$id_especialidade");
exit();

?>



