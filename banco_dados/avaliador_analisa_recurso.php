<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 24362464236!");
        exit();
    }
    
    $id_recurso=null;
    if($_POST['id_recurso'] != "")
        $id_recurso = (int)htmlspecialchars(trim($_POST['id_recurso']));
    
    $cpf_candidato=null;
    if($_POST['cpf_candidato'] != "")
        $cpf_candidato = htmlspecialchars(trim($_POST['cpf_candidato']));
    
    $cpf_candidato = $cpf_candidato = str_replace('.','',$cpf_candidato);
    $cpf_candidato = $cpf_candidato = str_replace('-','',$cpf_candidato);
    
    $id_candidato=null;
    if($_POST['id_candidato'] != "")
        $id_candidato = (int)htmlspecialchars(trim($_POST['id_candidato']));
    
    $analise=null;
    if($_POST['analise'] != "")
        $analise = htmlspecialchars(trim($_POST['analise']));
    
    $status=null;
    if($_POST['status'] != "")
        $status = htmlspecialchars(trim($_POST['status']));
    
    if($analise == null || $status == null || $analise == '' || $status == '')
    {
        erro("Erro 34735757! O Status e a análise são obrigatórios!");
        exit();
    }
    
    session_start();
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 234623462346! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 23462346346! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['perfil'] != 'avaliador' && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 2362362456! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    $get_candidato = $conexao->get_usuario_id($id_candidato);
    if($get_candidato[0]['id_selecao'] != $_SESSION['selecao'])
    {
        erro("Erro 24672454356! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    if($get_candidato[0]['cpf'] != $cpf_candidato)
    {
        erro("Erro 26426426! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    if( $_POST['crip'] != hash('sha256', $id_recurso))
    {
        erro("Erro 236234634633! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_recurso_id = $conexao->get_recurso_id($id_recurso);
    if($get_recurso_id[0]['id_candidato'] != $id_candidato)
    {
        erro("Erro 2473478! Não foi possível fazer a atualização dos dados!"); 
        exit();
    }
    
    $especialidades_avaliador = $conexao->get_especialidades_usuario_avaliador($_SESSION['id_usuario']);  
    $permitido = false;
    foreach($especialidades_avaliador as $especialidade)
    {
        if($get_recurso_id[0]['id_especialidade'] == $especialidade['id_especialidade'])
            $permitido = true;
    }
    if($_SESSION['perfil'] == 'admin') $permitido = true;
    
    if(!$permitido)
    {
        erro("Erro 2362745756757! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    $resultado = $conexao->avaliador_analisa_recurso(
                                            $id_recurso, 
                                            $status,
                                            $analise);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "16148", "recurso", "Update", "Operador ".$_SESSION['cpf']." Analisou o recurso: $id_recurso do candidato $cpf_candidato", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#recursos");

        

?>