<?php
include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 2353254!");
    exit();
}

if($_SESSION['perfil'] != 'candidato')
{
    erro("Erro 15614! Não foi possível realizara troca da senha!");
    exit();
}

if($_POST['crip'] != hash('sha256', $_SESSION['chave']."freitas"))
{
    erro("Erro 7713474! Não foi possível atualizar a sua senha!"); 
    exit(); 
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 1564531! A sua sessão expirou!");
    exit();
}

$senha1 = $_POST['senha1'];
$senha2 = $_POST['senha2'];

if($senha1 != $senha2)
{
    erro("Os dois campos devem ser iguais!");
    exit();
}

if(strlen($senha1) < 8)
{
    erro("A senha deve ter pelo menos 8 caracteres!");
    exit();    
}
if(!preg_match('/[A-Z]/', $senha1)) 
{
    erro("A senha deve ter pelo menos uma letra MAIÚSCULA!");
    exit();    
}
if(!preg_match('/[0-9]/', $senha1)) 
{
    erro("A senha deve ter pelo menos uma letra um NÚMERO!");
    exit();    
}
if(!preg_match('/[$*&@#]/', $senha1)) 
{
    erro("A senha deve ter pelo menos um caracter especial!");
    exit();    
}

/*    
    function senhaValida($senha) 
    {
         return preg_match('/[a-z]/', $senha) // tem pelo menos uma letra minúscula
         && preg_match('/[A-Z]/', $senha) // tem pelo menos uma letra maiúscula
         && preg_match('/[0-9]/', $senha) // tem pelo menos um número
         && preg_match('/^[\w$@]{6,}$/', $senha); // tem 6 ou mais caracteres
    }

        var_dump(senhaValida('aB1@xy$z')); // true
        var_dump(senhaValida('aB1')); // false, não tem 6 caracteres
        var_dump(senhaValida('AB1@XYZ')); // false, não tem letra minúscula
        var_dump(senhaValida('ab1@xyz')); // false, não tem letra maiúscula
        var_dump(senhaValida('ABc@xyz')); // false, não tem número
   */ 

$cpf = $_SESSION['cpf'];
    
$id_usuario = $_SESSION['id_usuario'];
$cpf_usuario = $_SESSION['cpf'];

include_once 'conexao.php';
$conexao = new Conexao();

if($id_usuario != null)
{
    $resultado = $conexao->usuario_reseta_senha($id_usuario,$senha1);
    $alteracoes_detalhadas =  print_r($resultado, true);

    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf, $id_usuario, "16106", "usuario", "Update", "Candidato resetou a sua senha", $alteracoes_detalhadas);

    $conexao = null;
    header ("Location: ../sistema/candidato_altera_senha.php?senha_alterada=1");
}
?>