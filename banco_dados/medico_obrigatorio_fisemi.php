<?php
    
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 2436456!");
        exit();
    }

    $transferencia_fisemi=null;
    if($_POST['transferencia_fisemi'] != "")
        $transferencia_fisemi = htmlspecialchars(trim($_POST['transferencia_fisemi']));
    
    $fisemi_rm_origem=null;
    if($_POST['fisemi_rm_origem'] != "")
        $fisemi_rm_origem = htmlspecialchars(trim($_POST['fisemi_rm_origem']));
    
    $fisemi_rm_destino=null;
    if($_POST['fisemi_rm_destino'] != "")
        $fisemi_rm_destino = htmlspecialchars(trim($_POST['fisemi_rm_destino']));
   
    $cpf_medico = $_POST['c_p_f_medico'];   
    $id_medico = $_POST['id_medico'];  
    
    session_start();
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 246436! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 456435! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 345437! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 4374357! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_medico_obrigatorio = $conexao->get_usuario_id($id_medico);
    
    if($get_medico_obrigatorio[0]['cpf'] != $cpf_medico)
    {
        erro("Erro 3473474357! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    if($get_medico_obrigatorio[0]['medico_obrigatorio'] != 1)
    {
        erro("Erro 347347437! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->edita_medico_obrigatorio_fisemi(
                                                $id_medico, 
                                                $transferencia_fisemi,
                                                $fisemi_rm_origem,
                                                $fisemi_rm_destino);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "16134", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou transfêrencia de FISEMI do médico obrigatório $cpf_medico", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=fisemi#fisemi");

        

?>