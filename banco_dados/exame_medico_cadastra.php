<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

if(!$_POST)
{
    erro_mensagem("Erro 5623444!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 403924! Não foi possível cadastrar um novo exame médico");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro 47862924! Não foi possível cadastrar um novo exame médico");
    exit();
}

if( $_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    $conexao = null;
    erro("Erro 474! Não foi possível cadastrar um novo exame médico");
    exit();
}

$id_selecao = trim($_POST['cod']);

if($id_selecao != $_SESSION['selecao'])
{
    $conexao = null;
    erro("Erro 403924! Não foi possível cadastrar um novo exame médico");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    $conexao = null;
    erro("Erro 40323423924! Não foi possível cadastrar um novo exame médico");
    exit();
}

$sessao = trim($_POST['sessao']);
$data = trim($_POST['data']);
$cidade = trim($_POST['cidade']);
$presidente = trim($_POST['presidente']);
$membro_1 = trim($_POST['membro_1']);
$membro_2 = trim($_POST['membro_2']);

if(!valida_data($data))
{
    $conexao = null;
    erro("Erro 425624! Data inválida!");
    exit();    
}

$data = reverte_data($data);

$get_exames_saude = $conexao->get_exames_medico();
foreach($get_exames_saude as &$linha)
{
    if($linha['dia_exame'] == $data)
    {
        $conexao = null;
        erro("Erro 2346347! Já existe o dia ".$_POST['data']." cadastrado! ");
        exit();
    }
}





$resultado_selecao = $conexao->get_selecao_id();
$nome_selecao = $resultado_selecao[0]['nome'] . " - " . $resultado_selecao[0]['ano'];

if($_POST)
    $resultado = $conexao->insere_exame_medico($sessao,$data, $cidade, $presidente,$membro_1,$membro_2);
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "14126", "exame_medico", "Insert", "Inseriu um exame médico na configuração da seleção, Sessão: $sessao, Data: $data e Cidade: $cidade", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 45645645 Cadastro não realizado!");
    exit();
}

header("Location: ../sistema/configuracao_selecao.php?exame_medico=1#exame_medico");
exit();

?>



