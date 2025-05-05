<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 3265324643!");
        exit();
    }

    $numero_distribuicao = null;
    if($_POST['numero_distribuicao'] != "")
        $numero_distribuicao = htmlspecialchars(trim($_POST['numero_distribuicao']));
    $forca_distribuicao = null;
    if($_POST['forca_distribuicao'] != "")
        $forca_distribuicao = htmlspecialchars(trim($_POST['forca_distribuicao']));
    $om_distribuicao = null;
    if($_POST['om_distribuicao'] != "")
        $om_distribuicao = htmlspecialchars(trim($_POST['om_distribuicao']));
    $uf_distribuicao = null;
    if($_POST['uf'] != "")
        $uf_distribuicao = htmlspecialchars(trim($_POST['uf']));
    $id_cidade_distribuicao = null;
    if($_POST['cidade_distribuicao'] != "")
        $id_cidade_distribuicao = htmlspecialchars(trim($_POST['cidade_distribuicao']));
    $titular_reserva_distribuicao = null;
    if($_POST['titular_reserva'] != "")
        $titular_reserva_distribuicao = htmlspecialchars(trim($_POST['titular_reserva']));
    $observacao_distribuicao = null;
    if($_POST['aditamento_convocacao'] != "")
        $aditamento_convocacao = htmlspecialchars(trim($_POST['aditamento_convocacao']));
    if($_POST['observacao_distribuicao'] != "")
        $observacao_distribuicao = htmlspecialchars(trim($_POST['observacao_distribuicao']));
    
    $incorporado = null;
    if($_POST['incorporado'] != "")
        $incorporado = (int)htmlspecialchars(trim($_POST['incorporado']));
    
    $uf2 = null;
    if($_POST['uf2'] != "")
        $uf2 = htmlspecialchars(trim($_POST['uf2']));
    
    $cidade2 = null;
    if($_POST['cidade2'] != "")
        $cidade2 = htmlspecialchars(trim($_POST['cidade2']));
    
    $om_distribuicao_1_fase = null;
    if($_POST['om_distribuicao_1_fase'] != "")
        $om_distribuicao_1_fase = htmlspecialchars(trim($_POST['om_distribuicao_1_fase']));
    
    $cpf_candidato = $_POST['c_p_f_candidato'];   
    $id_candidato = $_POST['id_candidato']; 
    
    
    $especialidade_incorporou = null;
    if($_POST['especialidade_incorporou'] != "") 
        $especialidade_incorporou = (int)htmlspecialchars(trim($_POST['especialidade_incorporou']));
    
    if($especialidade_incorporou != null && $especialidade_incorporou == 0)
    {
        erro("Erro 595679679! Especialidade de incorporação inválida");
        exit();
    }
    
    $data_incorporacao=null;
    if($_POST['data_incorporacao'] != "")
        $data_incorporacao = htmlspecialchars(reverte_data(trim($_POST['data_incorporacao'])));
    
    if($data_incorporacao != null && !valida_data($_POST['data_incorporacao']))
    {
        erro("Erro 56854865! Data da incorporação inválida!");
        exit();
    }
    
    
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
    
    if($_SESSION['perfil'] != 'admin')
    {
        erro("Erro 3426347457! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 436437457! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_candidato = $conexao->get_usuario_id($id_candidato);
    if($get_candidato[0]['id_selecao'] != $_SESSION['selecao'])
    {
        if($get_candidato[0]['medico_obrigatorio'] == null)
        {
            erro("Erro 324623462! Não foi possível fazer a atualização dos dados !"); 
            exit(); 
        }
    }
    if($get_candidato[0]['cpf'] != $cpf_candidato)
    {
        erro("Erro 3475754756! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->candidato_edita_distribuicao(
                                                $id_candidato,
                                                $incorporado,
                                                $numero_distribuicao,
                                                $forca_distribuicao,
                                                $om_distribuicao,
                                                $uf_distribuicao,                                                
                                                $titular_reserva_distribuicao,
                                                $id_cidade_distribuicao,
                                                $uf2,
                                                $cidade2,
                                                $om_distribuicao_1_fase,
                                                $data_incorporacao,
                                                $especialidade_incorporou,
                                                $aditamento_convocacao,
                                                $observacao_distribuicao
                                                );
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "16137", "usuario", "Update", "Usuario ".$_SESSION['cpf']." atualizou a distribuição do candidato $cpf_candidato", $alteracoes_detalhadas);
    
    $conexao = null;
    
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#distribuicao");
        

?>