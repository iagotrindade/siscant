<?php
    include_once '../sistema/funcoes.php';

    if(!$_POST)
    {
        erro("Erro 2346346!");
        exit();
    }

    
    $refratario_impedido=null;
    if($_POST['refratario_impedido'] != "")
        $refratario_impedido = htmlspecialchars(trim($_POST['refratario_impedido']));
    
    $historico_judicial=null;
    if($_POST['historico_judicial'] != "")
        $historico_judicial = htmlspecialchars(trim($_POST['historico_judicial']));
    
    $numero_acao=null;
    if($_POST['numero_acao'] != "")
        $numero_acao = htmlspecialchars(trim($_POST['numero_acao']));
    
    $data_liminar=null;
    if($_POST['data_liminar'] != "")
        $data_liminar = htmlspecialchars(reverte_data(trim($_POST['data_liminar'])));
    
    $transitou_julgado=null;
    if($_POST['transitou_julgado'] != "")
        $transitou_julgado = htmlspecialchars(trim($_POST['transitou_julgado']));
    
    $favoravel_desfavoravel=null;
    if($_POST['favoravel_desfavoravel'] != "")
        $favoravel_desfavoravel = htmlspecialchars(trim($_POST['favoravel_desfavoravel']));
    
    $convocado=null;
    if($_POST['convocado'] != "")
        $convocado = htmlspecialchars(trim($_POST['convocado']));
    
    $publicacao_bar_reg=null;
    if($_POST['publicacao_bar_reg'] != "")
        $publicacao_bar_reg = htmlspecialchars(trim($_POST['publicacao_bar_reg']));
    
    
    if($data_liminar != null && !valida_data($_POST['data_liminar']))
    {
        erro("Erro 4563463466! Data da Liminar!");
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
        erro("Erro 34573467423! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 345734737! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 4327347! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 34573457357! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_medico_obrigatorio = $conexao->get_usuario_id($id_medico);
    
    if($get_medico_obrigatorio[0]['cpf'] != $cpf_medico)
    {
        erro("Erro 3473457! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    if($get_medico_obrigatorio[0]['medico_obrigatorio'] != 1)
    {
        erro("Erro 3473457! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }

    if($_POST)
        $resultado = $conexao->edita_medico_obrigatorio_refratario_impedido(
                                                $id_medico, 
                                                $refratario_impedido,
                                                $historico_judicial,
                                                $numero_acao,
                                                $data_liminar,
                                                $transitou_julgado,
                                                $favoravel_desfavoravel,
                                                $convocado,
                                                $publicacao_bar_reg);
    
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "16135", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou Refratario/Impedimento Judicial do médico obrigatório $cpf_medico", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_medico&salvo=refratario_impedimento_judicial#refratario_impedimento_judicial");

        

?>