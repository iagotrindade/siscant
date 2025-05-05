<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 234642364326!");
        exit();
    }

    
    
    
    $apto_saude = null;
    if($_POST['apto_saude'] != "")
        $apto_saude = htmlspecialchars(trim($_POST['apto_saude']));
    $grupo_saude = null;
    if($_POST['grupo_saude'] != "")
        $grupo_saude = htmlspecialchars(trim($_POST['grupo_saude']));
    $data_exame_saude = null;
    if($_POST['data_exame_saude'] != "")
        $data_exame_saude = htmlspecialchars(reverte_data(trim($_POST['data_exame_saude'])));
    $cid_saude = null;
    if($_POST['cid_saude'] != "")
        $cid_saude = htmlspecialchars(trim($_POST['cid_saude']));
    
    if($_POST['data_exame_saude'] != null && !valida_data($_POST['data_exame_saude']))
    {
        erro("Erro 23464236436! Data do exame inválida!");
        exit();
    }
    
    $cpf_medico = $_POST['c_p_f_medico'];   
    $id_medico = $_POST['id_medico'];  
    
    session_start();
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 456845745! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 45745547! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 3473757! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 3567457547! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_medico_obrigatorio = $conexao->get_usuario_id($id_medico);
    
    if($get_medico_obrigatorio[0]['cpf'] != $cpf_medico)
    {
        erro("Erro 3574574567! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    if($get_medico_obrigatorio[0]['medico_obrigatorio'] != 1)
    {
        erro("Erro 57845674567! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->edita_medico_obrigatorio_exame_medico(
                                                $id_medico, 
                                                $apto_saude,
                                                $grupo_saude,
                                                $data_exame_saude,
                                                $cid_saude);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "16133", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou exame médico do médico obrigatório $cpf_medico", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=exame_medico#exame_medico");

        

?>