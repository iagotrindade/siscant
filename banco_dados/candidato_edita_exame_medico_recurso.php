<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 56754674!");
        exit();
    }

    $apto_saude = null;
    if($_POST['apto_saude_recurso'] != "")
        $apto_saude = htmlspecialchars(trim($_POST['apto_saude_recurso']));
    $grupo_saude = null;
    if($_POST['grupo_saude_recurso'] != "")
        $grupo_saude = htmlspecialchars(trim($_POST['grupo_saude_recurso']));
    $data_exame_saude = null;
    if($_POST['data_exame_saude_recurso'] != "")
        $data_exame_saude = htmlspecialchars(reverte_data(trim($_POST['data_exame_saude_recurso'])));
    $cid_saude = null;
    if($_POST['cid_saude_recurso'] != "")
        $cid_saude = htmlspecialchars(trim($_POST['cid_saude_recurso']));
    $obs_saude = null;
    if($_POST['observacao_exame_saude_recurso'] != "")
        $obs_saude = htmlspecialchars(trim($_POST['observacao_exame_saude_recurso']));
    
    if($_POST['data_exame_saude_recurso'] != null && !valida_data($_POST['data_exame_saude_recurso']))
    {
        erro("Erro 23464236436! Data do exame inválida!");
        exit();
    }
    
    if(strlen($obs_saude) > 2000)
    {
        erro_mensagem("Erro 23464356436! Observação muito grande! Não foi possível fazer o cadastro do RECURSO Exame médico!");
        exit();
    }
    if(strlen($cid_saude) > 2000)
    {
        erro_mensagem("Erro 23464356436! CID muito grande! Não foi possível fazer o cadastro do RECURSO Exame médico!");
        exit();
    }
    
    $cpf_candidato = $_POST['c_p_f_candidato'];   
    $id_candidato = $_POST['id_candidato'];  
    
    session_start();
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 463475467! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 45745547! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['perfil'] != 'jise' && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 3426346346! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 3567457547! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_candidato = $conexao->get_usuario_id($id_candidato);
    if($get_candidato[0]['id_selecao'] != $_SESSION['selecao'])
    {
        if($get_candidato[0]['medico_obrigatorio'] != 1)
        {
            erro("Erro 74564568! Não foi possível fazer a atualização dos dados !"); 
            exit();
        }
    }
    if($get_candidato[0]['cpf'] != $cpf_candidato)
    {
        erro("Erro 3574574567! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->candidato_edita_exame_medico_recurso(
                                                $id_candidato, 
                                                $apto_saude,
                                                $grupo_saude,
                                                $data_exame_saude,
                                                $cid_saude,                                                
                                                $obs_saude);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "16136", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou o recurso exame médico do candidato $cpf_candidato", $alteracoes_detalhadas);
    
    $conexao = null;
    
    if($_POST['medico_obrigatorio'] == 'sim')
        header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_candidato#exame_medico");
    if($_POST['medico_obrigatorio'] == 'nao')
        header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#exame_medico");
    
    echo "TUDO NA BOA!";
        

?>