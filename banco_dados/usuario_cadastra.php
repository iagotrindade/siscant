<?php
include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 78978944!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 15614! Você não tem permissão!");
    exit();
}

// <editor-fold defaultstate="collapsed" desc="GET VARIÁVEIS E VALIDAÇÕES">

$nome_completo=null;
$cpf=null;
$telefone=null;
$mail=null;
$nome_guerra = null;
$posto_grad = null;
$om = null;
$perfil = null;
$senha =  '123@siscant';
$datetime = date('Y-m-d H:i:s');

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
        $_POST['cpf'] == null ||
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
    if($_POST['cpf'] != "")
        $cpf = trim($_POST['cpf']);
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
    $cpf = $cpf = str_replace('.','',$cpf);
    $cpf = $cpf = str_replace('-','',$cpf);
    
    if(!valida_cpf($cpf))
    {
        erro("CPF Inválido!");
        exit();
    }
    $cpf_formatado = retorna_campo_formatado($cpf);
    
    if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
    {
        erro("E-Mail Inválido!");
        exit();
    }
    
    $lista_especialidades = null;
    if(isset($_POST['especialidades']))
        $lista_especialidades = $_POST['especialidades'];
    if(isset($_POST['especialidades']) == 0)
    {$lista_especialidades = null;}
    
    // </editor-fold>
    
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    
    $chave = $_SESSION['chave'];
    
    $senha =  hash('sha256', "123@siscant");
    
    $assinatura =   "2506"
                    ."F"
                    .date('dmY')
                    ."R"
                    .$_POST['perfil']
                    ."E"
                    .$_POST['nome_guerra']
                    ."I"
                    .substr($cpf ,0,9)
                    ."T"
                    .$chave
                    ."A"
                    .substr($_POST['om'] ,0,5)
                    ."S"
                    ."1988";
    
    $assinatura = mb_strtoupper($assinatura, 'UTF-8');
    $assinatura = str_replace('/','',$assinatura);
    $assinatura = str_replace('-','',$assinatura);
    $assinatura = str_replace('"','',$assinatura);
    $assinatura = str_replace(' ','',$assinatura);

    //////////////////////////////////////////////////////
    // Verifica se existe usuário na seleção
    //////////////////////////////////////////////////////
    
    $resultado = $conexao->get_usuario_cpf($cpf);
    if(count($resultado) > 0)
    {
        $apagado = $resultado[0]['apagado'];
        if($apagado == 0) $apagado = "ATIVO";
        if($apagado == 1) $apagado = "DESATIVADO";
        erro("Usuários encontrado $apagado! ".$resultado[0]['posto_grad']." ".$resultado[0]['nome_guerra']." | CPF:".$resultado[0]['cpf']);
        exit();
    }
    
    ////////////////////////////////////////////////////////
    // Insere candidato no sistemas
    //////////////////////////////////////////////////////
    
    
    
    if($_POST)
        $resultado = $conexao->insere_usuario(
                                                $_SESSION['selecao'],
                                                $cpf ,
                                                $perfil,
                                                $nome_completo,
                                                $senha,
                                                $nome_guerra,
                                                $om,
                                                $posto_grad,
                                                $telefone ,
                                                $mail ,
                                                $assinatura,
                                                $datetime
                                                );
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    $last_id = (int)$resultado['id_adicionado'];
    if($resultado)
    {
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14108", "usuario", "Insert", "Cadastrou o usuário $cpf", $alteracoes_detalhadas);
        
        if($lista_especialidades && count($lista_especialidades) > 0 && $perfil == 'avaliador')
        {
            $resultado2 = $conexao->insere_avaliador($last_id, $lista_especialidades);
            $alteracoes_detalhadas2 =  print_r($resultado2, true);
            
            $nome_especialidades = "";
            foreach ($lista_especialidades as &$id_especialidade) 
            {
                $get_nome_especialidade = $conexao->get_especialidade_id($id_especialidade);
                $nome_especialidades = $nome_especialidades . " | " . $get_nome_especialidade[0]['nome'];
            }
            $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14119", "avaliador", "Insert", "Cadastrou a(s) especialidade(s) $nome_especialidades para o usuário $cpf avaliar", $alteracoes_detalhadas2);
        }
        
    }
    
    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$last_id");

        

?>