<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 753457435745!");
        exit();
    }

    
    $etapa = null;
    if($_POST['etapa'] != "")
        $etapa = (int)htmlspecialchars(trim($_POST['etapa']));
    
    $data_abertura=null;
    if($_POST['data_abertura'] != "")
        $data_abertura = htmlspecialchars(reverte_data(trim($_POST['data_abertura'])));
    
    $avaliador=null;
    if($_POST['avaliador'] != "")
        $avaliador = htmlspecialchars(trim($_POST['avaliador']));
    
    $status=null;
    if($_POST['status'] != "")
        $status = htmlspecialchars(trim($_POST['status']));
    
    $analise=null;
    if($_POST['analise'] != "")
        $analise = htmlspecialchars(trim($_POST['analise']));
    
    $id_especialidade=null;
    if($_POST['especialidade'] != "")
        $id_especialidade = (int)htmlspecialchars(trim($_POST['especialidade']));
    
    if($data_abertura != null && !valida_data($_POST['data_abertura']))
    {
        erro("Erro 888546331! Data de abertura inválida!");
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
        erro("Erro 234623463467! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 23623462346! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['perfil'] != 'admin')
    {
        erro("Erro 2346324634! Não é possivel o cadastro!"); 
        exit();
    }
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 324634634! Não foi possível fazer o cadastro !"); 
        exit(); 
    }
    
    $get_candidato = $conexao->get_usuario_id($id_candidato);
    if($get_candidato[0]['id_selecao'] != $_SESSION['selecao'])
    {
        erro("Erro 2346346346! Não foi possível fazer o cadastro !"); 
        exit(); 
    }
    if($get_candidato[0]['cpf'] != $cpf_candidato)
    {
        erro("Erro 26346346! Não foi possível fazer o cadastro !"); 
        exit(); 
    }

    if (!isset($_SESSION['eipot']) == 1 ) {

     if($etapa == null || $data_abertura == null || $avaliador == null)
        {
            erro("Erro 679687! <br>Os campos Etapa, data de abertura e se é para o avaliador realizar a análise, são obrigatórios!"); 
            exit();
        }
    }
    
    if($id_especialidade == null && $avaliador == '1')
    {
        erro("Erro 42373457547! <br>A especialidade é obrigatória para direcionar ao avaliador!"); 
        exit();
    }
    
    if($avaliador == '1' && ($status != '' || $analise != null))
    {
        erro("Erro 3475856868! <br>Você selecionou que é para o avaliador realizar a análise, logo não pode alterar o status nem preencher a análise!"); 
        exit();
    }

    /*
    if($avaliador == '0' && ($status == '' || $analise == null))
    {
        erro("Erro 45734574357! <br>O recurso não pode ser cadastrado sem o status ou sem a análise!"); 
        exit();
    } */
    
    $datetime = date('Y-m-d H:i:s');
    $id_usuario_analise = null;
    $data_analise = null;
    
    if($avaliador == 0 && $analise != null)
    {
        $id_usuario_analise = $_SESSION['id_usuario'];
        $data_analise = $datetime = date('Y-m-d H:i:s');
    }
    $resultado = $conexao->cadastra_recurso(
                                            $id_candidato, 
                                            $id_especialidade,
                                            $etapa,
                                            $data_abertura,
                                            $avaliador,
                                            $status,
                                            $id_usuario_analise,
                                            $data_analise,
                                            $analise);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "14130", "recurso", "Insert", "Operador ".$_SESSION['cpf']." Cadastrou um recurso para o candidato $cpf_candidato", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_candidato#recursos");

        

?>