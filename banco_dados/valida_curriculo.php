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
    erro("Erro 2456534263!");
    exit();
}

if($_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 457235345!");
    exit();
}


if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
    erro("Erro 5344564263!");
    exit();
}

if( $_POST['criptografia'] !=  hash('sha256', $_SESSION['chave']."freitas".$_POST['id_especialidade_curriculo']))
{
    erro("Erro 234263!");
    exit();
}

if(!avaliacao() && $_SESSION['perfil'] != 'admin')
{
    erro("Erro 234233! Você está fora do periodo definido para avaliação de currículo");
    exit();
}

$id_especialidade_curriculo = $_POST['id_especialidade_curriculo'];
$valido = (int)$_POST['valido'];
$id_usuario = $_POST['id_usuario'];
$id_especialidade = $_POST['id_especialidade'];
$id_curriculo = (int)$_POST['id_curriculo'];

$justificativa = trim($_POST['justificativa']);

$multiplicador = 1;
if(isset($_POST['multiplicador']))
    $multiplicador = (int)$_POST['multiplicador'];


$candiato = $conexao->get_usuario_id($id_usuario);
$cpf_candidato = $candiato[0]['cpf'];

$curriculo = $conexao->get_especialidade_curriculo_id($id_especialidade_curriculo);
$nome_curriculo = $curriculo[0]['label'];

$especialidade = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $especialidade[0]['nome'];

$curriculo_mult = $conexao->get_curriculo_id($id_curriculo);
$curriculo_multiplicacao = $curriculo_mult[0]['multiplicacao'];

if($valido == 0 && $justificativa == null)
{
    erro("Erro 263467! A justificativa é obrigatória para invalidar um documento!");
    exit();
}

if($multiplicador > 1 && ($curriculo_multiplicacao == '0' || $curriculo_multiplicacao == null))
{
    erro("Erro 2347634737! Tentativa de multiplicar por $multiplicador. Este currículo não pode ser multiplicado! ID Especialidade $id_especialidade, ID Usuário: $id_usuario, Válido: $valido, ID Especialidade Curriculo: $id_especialidade_curriculo");
    exit();
}

$maximo_multiplicacao = 1;
if((int)$curriculo_mult[0]['quantidade_multiplicacao'] > 1)
    $maximo_multiplicacao = (int)$curriculo_mult[0]['quantidade_multiplicacao'];

if($valido == 1 && ($multiplicador < 1 || $multiplicador > $maximo_multiplicacao))
{
    
    erro("Erro 32453467! Tentativa de multiplicar por $multiplicador. O multiplicador deve ser no máximo $maximo_multiplicacao! ID Especialidade $id_especialidade, ID Usuário: $id_usuario, Válido: $valido, ID Especialidade Curriculo: $id_especialidade_curriculo");
    exit();
}

if($multiplicador > 1 && $_SESSION['perfil'] != 'admin' && $_SESSION['perfil'] != 'avaliador')
{
    erro("Erro 2347647888! Somente o Administrador e o avaliador podem multiplicar pode fazer multiplicação de currículo");
    exit();
}

/*
if($valido == 1 && $multiplicador > 1 && ($curriculo[0]['id_curriculo'] != 19 && $curriculo[0]['id_curriculo'] != 18)) 
{
    erro("Erro 78967543! Tentativa de multiplicar por $multiplicador!  Currículo $nome_curriculo não pode ser multiplicado! ID Especialidade $id_especialidade, ID Usuário: $id_usuario, Válido: $valido, ID Especialidade Curriculo: $id_especialidade_curriculo");
    exit();
}
*/

$get_candidato_esp_id = $conexao->get_candidato_x_especialidade_id($curriculo[0]['id_candidato_x_especialidade']);

if($get_candidato_esp_id[0]['id_candidato'] != $id_usuario)
{
    //erro("Erro 78239749!");
    exit();
}

if($get_candidato_esp_id[0]['id_especialidade'] != $id_especialidade)
{
    erro("Erro 78564349!");
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

$valido_invalido = null;

if($valido == 1)
    $valido_invalido = "VALIDOU";
if($valido == 0)
    $valido_invalido = "INVALIDOU";


$resultado = $conexao->avalia_curriculo($id_especialidade_curriculo, $valido, $justificativa, $multiplicador);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "16117", "especialidade_curriculo", "Update", "$valido_invalido o currículo $nome_curriculo da especialização $nome_especialidade do candidato: ".$cpf_candidato, "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4456436!");
    exit();
}

header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#avaliacao_id_$id_especialidade");
exit();

?>



