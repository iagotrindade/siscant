<?php	

include_once '../sistema/funcoes.php';
session_start();
include_once 'conexao.php';
$conexao = new Conexao();

/*
if(!$_POST)
{
    erro_mensagem("Erro 326264567!");
    exit();
}
    */
    

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    $conexao = null;
    erro("Erro 2346456456! Não foi possível salvar a avaliação");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro 3464457423! Não foi possível salvar a avaliação");
    exit();
}

if( $_POST['criptografia_eipot'] != hash('sha256', $_SESSION['chave']."freitas".$_POST['id_usuario_eipot']))
{
    $conexao = null;
    erro("Erro 213535345! Não foi possível salvar a avaliação");
    exit();
}

$usuario_logado = $conexao->get_usuario_id($_SESSION['id_usuario']);    

if($usuario_logado[0]['assinatura_sistema'] != $_SESSION['assinatura_sistema'])
{
    $conexao = null;
    erro("Erro 2346236457! Não foi possível salvar a avaliação");
    exit();
}

$id_usuario_eipot = htmlspecialchars(trim($_POST['id_usuario_eipot']));
$cpf_candidato = htmlspecialchars(trim($_POST['c_p_f_candidato']));
$quantidade_flexao_braco = (int)htmlspecialchars(trim($_POST['qtd_flexao_braco']));
$quantidade_abdominal = (int)htmlspecialchars(trim($_POST['qtd_abdominal']));
$quantidade_barra = (int)htmlspecialchars(trim($_POST['qtd_barra']));
$distancia_corrida = (int)htmlspecialchars(trim($_POST['dist_corrida']));
$nota_ofor = htmlspecialchars(trim($_POST['notafinal_ofor']));
$ano_formacao = (int)htmlspecialchars(trim($_POST['nota_ano_formacao']));
$testa_usuario = $conexao->get_usuario_id($id_usuario_eipot);

if($testa_usuario[0]['cpf'] != $cpf_candidato)
{
    $conexao = null;
    erro("Erro 2321348888! Não foi possível salvar a avaliação");
    exit();
}
$nota_ofor = (float)str_replace(",", ".", $nota_ofor);
if($nota_ofor > 10)
{
    $conexao = null;
    erro("Erro 34636346! A nota final do curso OFOT não pode ser maior que 10");
    exit();
}

if($_POST)
    $resultado = $conexao->atualiza_notas_eipot($id_usuario_eipot,$quantidade_flexao_braco,$quantidade_abdominal,$quantidade_barra,$distancia_corrida,$ano_formacao,$nota_ofor);
    $alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $_SESSION['selecao'], "161507", "usuario", "Update", "Atualizou as notas EIPOT do usuário ID: $id_usuario_eipot de CPF: $cpf_candidato", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 24764777 Avaliação não realizada!");
    exit();
}

header ("Location: ../sistema/usuario_visualiza.php?id_usuario=".$id_usuario_eipot."#eipot");
exit();

?>



