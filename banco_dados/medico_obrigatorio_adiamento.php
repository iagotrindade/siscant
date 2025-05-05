<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 23423644!");
        exit();
    }
    
    $solicitou_adiamento=null;
    if($_POST['solicitou_adiamento'] != "")
        $solicitou_adiamento = htmlspecialchars(trim($_POST['solicitou_adiamento']));
    $data_inicio_adiamento=null;
    if($_POST['data_inicio_adiamento'] != "")
        $data_inicio_adiamento = htmlspecialchars(reverte_data(trim($_POST['data_inicio_adiamento'])));
    $data_fim_adiamento=null;
    if($_POST['data_fim_adiamento'] != "")
        $data_fim_adiamento = htmlspecialchars(reverte_data(trim($_POST['data_fim_adiamento'])));
    $especialidade_adiamento=null;
    if($_POST['especialidade_adiamento'] != "")
        $especialidade_adiamento = htmlspecialchars(trim($_POST['especialidade_adiamento']));
   
    if($_POST['data_inicio_adiamento'] != null && !valida_data($_POST['data_inicio_adiamento']))
    {
        erro("Erro 3463634! Data de Início inválida!");
        exit();
    }
    if($_POST['data_fim_adiamento'] != null && !valida_data($_POST['data_fim_adiamento']))
    {
        erro("Erro 346365! Data de Fim inválida!");
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
        erro("Erro 4575675632! Não foi possível fazer a atualização dos dados!"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->edita_medico_obrigatorio_adiamento(
                                                $id_medico, 
                                                $solicitou_adiamento,
                                                $data_inicio_adiamento,
                                                $data_fim_adiamento,
                                                $especialidade_adiamento);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "16132", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou adiamento do médico obrigatório $cpf_medico", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=adiamento#adiamento");

        

?>