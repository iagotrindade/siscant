<?php
include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 8790879044!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 15617684! Você não tem permissão!");
    exit();
}

// <editor-fold defaultstate="collapsed" desc="GET VARIÁVEIS E VALIDAÇÕES">

$nome_completo=null;
$telefone=null;
$mail=null;
$nome_guerra = null;
$posto_grad = null;
$om = null;
$perfil = null;
$senha =  '123@siscant';

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 15645! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 15645! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

$selecao = $_SESSION['selecao'];
if (
        $_POST['nome_completo'] == null ||
        $_POST['nome_guerra'] == null ||
        $_POST['telefone'] == null ||
        $_POST['mail'] == null ||
        $_POST['posto_grad'] == null ||
        $_POST['perfil'] == null ||
        $_POST['om'] == null
    )
    
    {
        erro("Todos os campos são obrigatorios!");
        exit();
    }
 
    if($_POST['nome_completo'] != "")
        $nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
    if($_POST['telefone'] != "")
        $telefone = htmlspecialchars(trim($_POST['telefone']));
    if($_POST['mail'] != "")
        $mail = htmlspecialchars(trim($_POST['mail']));
    if($_POST['posto_grad'] != "")
        $posto_grad = htmlspecialchars(trim($_POST['posto_grad']));
    if($_POST['om'] != "")
        $om = htmlspecialchars(trim($_POST['om']));
    if($_POST['nome_guerra'] != "")
        $nome_guerra = htmlspecialchars(trim($_POST['nome_guerra']));
    if($_POST['perfil'] != "")
        $perfil = htmlspecialchars(trim($_POST['perfil']));
    
    if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
    {
        erro("E-Mail Inválido!");
        exit();
    }
    
    $lista_especialidades_editar = array();
    if(isset($_POST['especialidades']))
        $lista_especialidades_editar = $_POST['especialidades'];
    
    
    // </editor-fold>
    
    include_once 'conexao.php';
    
    $conexao = new Conexao();
    
    $crip = $_POST['crip'];
    $id_usuario = $_POST['id_usuario'];
    
    $usuario_editar = $conexao->get_usuario_id($id_usuario);
    
    if(hash('sha256', $usuario_editar[0]['assinatura_sistema']) != $crip)
    {
        erro("Erro 232348! Usuário não alterado!");
        exit();
    }
    
    $lista_especialidades = $conexao->get_especialidades_usuario_avaliador($id_usuario);
    
    $nova_lista = array();
    foreach ($lista_especialidades as &$valor)
    {
        array_push($nova_lista,$valor['id_especialidade']);
    }
    
    $arrayDiferenca = array_diff($lista_especialidades_editar,$nova_lista);
    $arrayDiferenca2 = array_diff($nova_lista,$lista_especialidades_editar);
    
    //////////////////////////////////////////////////////
    // Verifica se existe usuário na seleção
    //////////////////////////////////////////////////////
    
    if($usuario_editar[0]['apagado'] == 1)
    {
        erro("Erro 89234! O usuário esta apagado! ");
        exit();
    }
    
    ////////////////////////////////////////////////////////
    // Edita o Usuário
    //////////////////////////////////////////////////////
    
    if($_POST)
        $resultado = $conexao->edita_usuario(   $id_usuario,
                                                $perfil,
                                                $nome_completo,
                                                $nome_guerra,
                                                $om,
                                                $posto_grad,
                                                $telefone ,
                                                $mail
                                                );
    
    
    
    if($resultado)
    {
        $alteracoes_detalhadas =  print_r($resultado, true);
        
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_usuario, "16110", "usuario", "Update", "Editou o usuário ".$usuario_editar[0]['cpf'], $alteracoes_detalhadas);
        
        /*
        if($perfil != 'avaliador')
        {
            $apaga_especialidades = $conexao->apaga_especialidade_usuario($id_usuario);
        }
        */
        
        if((count($arrayDiferenca) > 0 || count($arrayDiferenca2) > 0) && $perfil == 'avaliador')
        {
            $apaga_especialidades = $conexao->apaga_especialidade_usuario($id_usuario);
            
            $resultado2 = $conexao->insere_avaliador($id_usuario, $lista_especialidades_editar);
            $alteracoes_detalhadas2 =  print_r($resultado2, true);
            
            $nome_especialidades = "";
            foreach ($lista_especialidades_editar as &$id_especialidade) 
            {
                $get_nome_especialidade = $conexao->get_especialidade_id($id_especialidade);
                $nome_especialidades = $nome_especialidades . " | " . $get_nome_especialidade[0]['nome'];
            }
            
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_usuario, "14119", "avaliador", "Update", "Editou especialidade(s) $nome_especialidades para o usuário ".$usuario_editar[0]['cpf']." avaliar", $alteracoes_detalhadas2);
        }
    }
    
    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_usuario");

        

?>