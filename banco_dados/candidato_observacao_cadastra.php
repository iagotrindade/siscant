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

if($_SESSION['perfil'] == 'consulta' || $_SESSION['perfil'] == 'ouvidor')
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
$observacao = $_POST['observacao'];

if($id_usuario == "" || $id_usuario == null || $observacao == "" || $observacao == null)
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
    $resultado = $conexao->cadastra_observacao_candidato($id_usuario,$observacao,0);
$id_adicionado = (int)$resultado['id_adicionado'];

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], "$id_adicionado", "14120", "observacao", "Insert", "Inseriu uma observação no candidato: ".$get_candidato[0]['cpf'], "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4582345344 Observação não cadastrada!");
    exit();
}

if(isset($_POST['medico_obrigatorio']))
    header("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_usuario#observacoes");
else
    header("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario#observacoes");
exit();

?>



