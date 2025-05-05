<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 23423644!");
        exit();
    }

    $voluntario_12rm=null;
    if($_POST['voluntario_12rm'] != "")
        $voluntario_12rm = htmlspecialchars(trim($_POST['voluntario_12rm']));
    $voluntario_sv_militar=null;
    if($_POST['voluntario_sv_militar'] != "")
        $voluntario_sv_militar = htmlspecialchars(trim($_POST['voluntario_sv_militar']));
    $prioridade_forca=null;
    if($_POST['prioridade_forca'] != "")
        $prioridade_forca = htmlspecialchars(trim($_POST['prioridade_forca']));
    $obrigatorio=null;
    if($_POST['obrigatorio'] != "")
        $obrigatorio = htmlspecialchars(trim($_POST['obrigatorio']));
    $situacao_militar=null;
    if($_POST['situacao_militar'] != "")
        $situacao_militar = htmlspecialchars(trim($_POST['situacao_militar']));
    $antecedentes=null;
    if($_POST['antecedentes'] != "")
        $antecedentes = htmlspecialchars(trim($_POST['antecedentes']));
    $forum_civil=null;
    if($_POST['forum_civil'] != "")
        $forum_civil = htmlspecialchars(trim($_POST['forum_civil']));
    $forum_criminal=null;
    if($_POST['forum_criminal'] != "")
        $forum_criminal = htmlspecialchars(trim($_POST['forum_criminal']));
    $arrimo=null;
    if($_POST['arrimo'] != "")
        $arrimo = htmlspecialchars(trim($_POST['arrimo']));
    
    $cpf_medico = $_POST['c_p_f_medico'];   
    $id_medico = $_POST['id_medico'];  
    
    
    
    session_start();
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 46346345! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 4236435! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 46435! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 47688568! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_medico_obrigatorio = $conexao->get_usuario_id($id_medico);
    
    if($get_medico_obrigatorio[0]['cpf'] != $cpf_medico)
    {
        erro("Erro 45634346! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    if($get_medico_obrigatorio[0]['medico_obrigatorio'] != 1)
    {
        erro("Erro 4575675632! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->edita_medico_obrigatorio_outras_info(
                                                $id_medico,
                                                $voluntario_12rm,
                                                $voluntario_sv_militar,
                                                $prioridade_forca,
                                                $obrigatorio,
                                                $situacao_militar,
                                                $antecedentes,
                                                $forum_civil,
                                                $forum_criminal,
                                                $arrimo);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "16131", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou outras informações do médico obrigatório $cpf_medico", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=outras_info#outras_info");

        

?>