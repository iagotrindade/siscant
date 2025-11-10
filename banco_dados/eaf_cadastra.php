<?php	

// 14 MAIO 2024 

include_once 'conexao.php';
include_once '../sistema/funcoes.php';

session_start();

$conexao = new Conexao();

if(!$_POST || $_POST['resultado'] == '') 
{
    erro_mensagem("Erro 234234644!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    erro("Erro 2456534263!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 23445263!");
    exit();
}

if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
    erro("Erro 5345263!");
    exit();
}

if( $_POST['criptografia'] != hash('sha256', $_SESSION['assinatura_sistema']))
{
    erro("Erro 234263!");
    exit();
}

$id_usuario = $_POST['id_usuario'];
$resultado = $_POST['resultado'];
$id_admin = $_SESSION['id_usuario'];


if($id_usuario == "" || $id_usuario == null || $resultado == "" || $resultado == null)
{
    erro("Erro 64263!");
    exit();
}

$get_candidato = $conexao->get_usuario_id($id_usuario);

if(count($get_candidato) != 1)
{
    erro("Erro wrew64263!");
    exit();
}

if($_POST)
    $resultado = $conexao->altera_eaf_candidato($id_usuario, $resultado);

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_usuario", "161512", "candidato", "Update", "Alterou o EAF do candidato ".$get_candidato[0]['cpf']." para ".$resultado, "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4582345344 Email não alterado!");
    exit();
}

if(isset($_POST['medico_obrigatorio']))
    header("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_usuario#alterar_eaf");
else
    header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#alterar_eaf");
exit();

?>



