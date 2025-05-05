<?php
include_once '../sistema/funcoes.php';

if(!$_POST)
{
    erro("Erro 23423644!");
    exit();
}

// <editor-fold defaultstate="collapsed" desc="GET VARIÁVEIS E VALIDAÇÕES">

$nome_completo=null;
$identidade=null;
$nome_social=null;
$dependentes=null;
$filiacao_pai=null;
$estado_civil=null;
$sexo=null;
$nascionalidade=null;
$naturalidade=null;
$filiacao_mae=null;
$data_nascimento=null;
$uf=null;
$bairro=null;
$cidade=null;
$cep=null;
$rua=null;
$telefone=null;
$celular=null;
$mail=null;
$tempo_sv_pub=null;
$tempo_sv_pub_anos=null;
$tempo_sv_pub_meses=null;
$tempo_sv_pub_dias=null;
$tempo_sv_mil=null;
$tempo_sv_mil_anos=null;
$tempo_sv_mil_meses=null;
$tempo_sv_mil_dias=null;
$civil_militar=null;
$certificado=null;
$ativa_reserva=null;
$posto_grad=null;
$forca=null;
$arma=null;
$documento=null;
$data_expedicao=null;
$incorporacao=null;
$licenciamento=null;
$ano_selecao_medico_obrigatorio=null;
$conselho=null;

$nome_ie = null;
$uf_ie = null;
$ano_formacao = null;
$cidade_ie = null;

$cpf_medico = $_POST['c_p_f_medico'];   
$id_medico = $_POST['id_medico'];   
    
    $ativa_reserva = null;
    
    if($_POST['ja_foi_militar'] == 'sim')
        $ativa_reserva = "ja_foi_militar";
    
    if($_POST['ja_foi_militar'] == 'nao')
        $ativa_reserva = "nunca_foi_militar";
    
    if($_POST['civil_militar'] == 'militar')
        $ativa_reserva = 'militar_ativa';
    
    
    if($_POST['nome_completo'] != "")
        $nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
    if($_POST['identidade'] != "")
        $identidade = htmlspecialchars(trim($_POST['identidade']));
    if($_POST['data_nascimento'] != "")
        $data_nascimento = htmlspecialchars(reverte_data(trim($_POST['data_nascimento'])));
    if($_POST['nome_social'] != "")
        $nome_social = htmlspecialchars(trim($_POST['nome_social']));
    if($_POST['estado_civil'] != "")
        $estado_civil = htmlspecialchars(trim($_POST['estado_civil']));
    if($_POST['filiacao_pai'] != "")
        $filiacao_pai = htmlspecialchars(trim($_POST['filiacao_pai']));
    if($_POST['sexo'] != "")
        $sexo = htmlspecialchars(trim($_POST['sexo']));
    if($_POST['nascionalidade'] != "")
        $nascionalidade = htmlspecialchars(trim($_POST['nascionalidade']));
    if($_POST['naturalidade'] != "")
        $naturalidade = htmlspecialchars(trim($_POST['naturalidade']));
    if($_POST['filiacao_mae'] != "")
        $filiacao_mae = htmlspecialchars(trim($_POST['filiacao_mae']));

    if($_POST['uf'] != "")
        $uf = htmlspecialchars(trim($_POST['uf']));
    if($_POST['bairro'] != "")
        $bairro = htmlspecialchars(trim($_POST['bairro']));
    if($_POST['cidade'] != "")
        $cidade = htmlspecialchars(trim($_POST['cidade']));
    if($_POST['cep'] != "")
        $cep = htmlspecialchars(trim($_POST['cep']));
    if($_POST['rua'] != "")
        $rua = htmlspecialchars(trim($_POST['rua']));
    if($_POST['telefone'] != "")
        $telefone = htmlspecialchars(trim($_POST['telefone']));
    if($_POST['celular'] != "")
        $celular = htmlspecialchars(trim($_POST['celular']));
    if($_POST['mail'] != "")
        $mail = htmlspecialchars(trim($_POST['mail']));
    if($_POST['conselho'] != "")
        $conselho = htmlspecialchars(trim($_POST['conselho']));
    
    if($_POST['nome_ie'] != "")
        $nome_ie = htmlspecialchars(trim($_POST['nome_ie']));
    if($_POST['ano_formacao'] != "")
        $ano_formacao = htmlspecialchars(trim($_POST['ano_formacao']));
    if($_POST['uf_ie'] != "")
        $uf_ie = htmlspecialchars(trim($_POST['uf_ie']));
    if($_POST['cidade_ie'] != "")
        $cidade_ie = htmlspecialchars(trim($_POST['cidade_ie']));
    
    if($_POST['ano_selecao_medico_obrigatorio'] != "")
        $ano_selecao_medico_obrigatorio = htmlspecialchars(trim($_POST['ano_selecao_medico_obrigatorio']));
    
    if($_POST['tempo_sv_pub'] == '1')
    {
        if($_POST['tempo_sv_pub_anos'] == null ||
        $_POST['tempo_sv_pub_meses'] == null ||
        $_POST['tempo_sv_pub_dias'] == null)
        {
            erro("Erro 49023823! Se informado que o médico possui tempo de serviço público, o tempo deve ser informado!");
            exit();
        }
    }
    if($_POST['tempo_sv_pub'] == '0')
    {
        if($_POST['tempo_sv_pub_anos'] != null ||
        $_POST['tempo_sv_pub_meses'] != null ||
        $_POST['tempo_sv_pub_dias'] != null)
        {
            erro("Erro 324235! Você deve selecionar que o médico possui tempo de serviço público!");
            exit();
        }
    }
    if($_POST['tempo_sv_mil'] == '1')
    {
        if($_POST['tempo_sv_mil_anos'] == null ||
        $_POST['tempo_sv_mil_meses'] == null ||
        $_POST['tempo_sv_mil_dias'] == null)
        {
            erro("Erro 67867523! Se informado que o médico possui tempo de serviço militar, os campos do tempo de se serviço militar devem ser preenchidos!");
            exit();
        }
    }
    
    if($_POST['tempo_sv_mil'] == '0')
    {
        if($_POST['tempo_sv_mil_anos'] != null ||
        $_POST['tempo_sv_mil_meses'] != null ||
        $_POST['tempo_sv_mil_dias'] != null)
        {
            erro("Erro 235346346! Se informado que o médico possui NÃO tempo de serviço militar, os campos do tempo de se serviço militar NÃO PODEM estar ser preenchidos!");
            exit();
        }
    }

    if($_POST['tempo_sv_pub'] != "")
        $tempo_sv_pub = htmlspecialchars($_POST['tempo_sv_pub']);
    if($_POST['tempo_sv_pub_anos'] != "")
        $tempo_sv_pub_anos = htmlspecialchars($_POST['tempo_sv_pub_anos']);
    if($_POST['tempo_sv_pub_meses'] != "")
        $tempo_sv_pub_meses = htmlspecialchars($_POST['tempo_sv_pub_meses']);
    if($_POST['tempo_sv_pub_dias'] != "")
        $tempo_sv_pub_dias = htmlspecialchars($_POST['tempo_sv_pub_dias']);

    if($_POST['tempo_sv_mil'] != "")
        $tempo_sv_mil = htmlspecialchars($_POST['tempo_sv_mil']);
    if($_POST['tempo_sv_mil_anos'] != "")
        $tempo_sv_mil_anos = htmlspecialchars($_POST['tempo_sv_mil_anos']);
    if($_POST['tempo_sv_mil_meses'] != "")
        $tempo_sv_mil_meses = htmlspecialchars($_POST['tempo_sv_mil_meses']);
    if($_POST['tempo_sv_mil_dias'] != "")
        $tempo_sv_mil_dias = htmlspecialchars($_POST['tempo_sv_mil_dias']);

    if($_POST['civil_militar'] != "")    
        $civil_militar = htmlspecialchars($_POST['civil_militar']);
    if(isset($_POST['certificado']) && $_POST['certificado'] != "")
        $certificado = htmlspecialchars(trim($_POST['certificado']));
    if($_POST['documento'] != "")
        $documento = htmlspecialchars(trim($_POST['documento']));
    if($_POST['data_expedicao'] != "")
        $data_expedicao = htmlspecialchars(reverte_data(trim($_POST['data_expedicao'])));

    if($_POST['posto_grad'] != "")
        $posto_grad = htmlspecialchars($_POST['posto_grad']);
    if($_POST['forca'] != "")
        $forca = htmlspecialchars($_POST['forca']);
    if($_POST['arma'] != "")
        $arma = htmlspecialchars(trim($_POST['arma']));
    if($_POST['incorporacao'] != "")
        $incorporacao = htmlspecialchars(trim($_POST['incorporacao']));
    if($_POST['licenciamento'] != "")
        $licenciamento = htmlspecialchars(trim($_POST['licenciamento']));
    
    if(!valida_data($_POST['data_nascimento']))
    {
        erro("Erro 324623462436! Data de nascimento inválida!");
        exit();
    }
    if(trim($_POST['data_expedicao']) != null && !valida_data($_POST['data_expedicao']))
    {
        erro("Erro 342642366! Data de expedição inválida!");
        exit();
    }
    
    $num_dependentes  = null;
    $prioridade_forca = null;
    $voluntario_12rm  = null;
    
    session_start();
    
    
        
        if($_POST['num_dependentes'] != "")
            $num_dependentes = htmlspecialchars(trim($_POST['num_dependentes']));
        
        /*
        if($_POST['prioridade_forca'] != "")
            $prioridade_forca = htmlspecialchars(trim($_POST['prioridade_forca']));
        if($_POST['voluntario_12rm'] != "")
            $voluntario_12rm = htmlspecialchars(trim($_POST['voluntario_12rm']));
        */
       
    // </editor-fold>
    
    include_once 'conexao.php';
    $datetime = date('Y-m-d H:i:s');
    
    $conexao = new Conexao();
    
    
    
    if(!isset($_SESSION['selecao']))
    {
        erro("Erro 2462467457! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if(!isset($_SESSION['chave']))
    {
        erro("Erro 2336346! A sua sessão expirou! Faça novamente o cadastro");
        exit();
    }
    
    if($_SESSION['medico_obrigatorio'] != 1 && $_SESSION['perfil'] != 'admin')
    {
        erro("Erro 3426346346! Não é possivel fazer essa edição!"); 
        exit();
    }
    
    
    if($_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
    {
        erro("Erro 2467347437! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    
    $get_medico_obrigatorio = $conexao->get_usuario_id($id_medico);
    
    if($get_medico_obrigatorio[0]['cpf'] != $cpf_medico)
    {
        erro("Erro 4575675632! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    if($get_medico_obrigatorio[0]['medico_obrigatorio'] != 1)
    {
        erro("Erro 457457547! Não foi possível fazer a atualização dos dados !"); 
        exit(); 
    }
    

    if($_POST)
        $resultado = $conexao->edita_medico_obrigatorio( $id_medico,
                                                $nome_completo,
                                                $identidade ,
                                                $data_nascimento ,
                                                $nome_social ,
                                                $estado_civil ,
                                                $dependentes ,
                                                $filiacao_pai ,
                                                $sexo ,
                                                $nascionalidade ,
                                                $naturalidade ,
                                                $filiacao_mae ,
                                                $uf ,
                                                $bairro ,
                                                $cidade ,
                                                $cep ,
                                                $rua ,
                                                $telefone ,
                                                $celular ,
                                                $mail ,
                                                $tempo_sv_pub ,
                                                $tempo_sv_pub_anos ,
                                                $tempo_sv_pub_meses ,
                                                $tempo_sv_pub_dias ,
                                                $tempo_sv_mil ,
                                                $tempo_sv_mil_anos ,
                                                $tempo_sv_mil_meses ,
                                                $tempo_sv_mil_dias ,
                                                $civil_militar ,
                                                $certificado ,
                                                $documento ,
                                                $data_expedicao ,
                                                $ativa_reserva ,
                                                $posto_grad ,
                                                $forca ,
                                                $arma ,
                                                $incorporacao ,
                                                $licenciamento,
                                                $num_dependentes,
                                                $nome_ie,
                                                $uf_ie,
                                                $ano_formacao,
                                                $cidade_ie,
                                                $ano_selecao_medico_obrigatorio,
                                                $conselho,
                                                $datetime);
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_medico, "16130", "usuario", "Update", "Operador ".$_SESSION['cpf']." atualizou os dados do médico obrigatório $cpf_medico", $alteracoes_detalhadas);
    
    $conexao = null;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$id_medico");

        

?>