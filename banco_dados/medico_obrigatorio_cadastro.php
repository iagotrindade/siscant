<?php
include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 57547346!");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 235325! Você não tem permissão!");
    exit();
}

// <editor-fold defaultstate="collapsed" desc="GET VARIÁVEIS E VALIDAÇÕES">

$nome_completo=null;
$cpf=null;
$nome_mae=null;
$ra = null;
$data_nascimento = null;
$ano_formacao = null;
$nome_instituto_ensino = null;
$uf_instituto_ensino = null;
$municipio_instituto_ensino = null;
$conselho = null;

if($_SESSION['perfil'] != 'admin')
{
    erro("Erro 3453245! Você não tem permissão!");
    exit();
}

if(!isset($_SESSION['selecao']) || !isset($_SESSION['chave']))
{
    erro("Erro 43774357! A sua sessão expirou! Faça novamente o cadastro");
    exit();
}

$selecao = $_SESSION['selecao'];

if ($_POST['cpf'] == null || $_POST['cpf'] == '')
    
    {
        erro("O campo CPF é obrigatório!");
        exit();
    }
 
    if($_POST['nome_completo'] != "")
        $nome_completo = htmlspecialchars(trim($_POST['nome_completo']));
    if($_POST['cpf'] != "")
        $cpf = trim($_POST['cpf']);
    if($_POST['ra'] != "")
        $ra = htmlspecialchars(trim($_POST['ra']));
    if($_POST['nome_mae'] != "")
        $nome_mae = htmlspecialchars(trim($_POST['nome_mae']));
    if($_POST['ano_formacao'] != "")
        $ano_formacao = htmlspecialchars(trim($_POST['ano_formacao']));
    if($_POST['data_nascimento'] != "")
        $data_nascimento = htmlspecialchars(reverte_data(trim($_POST['data_nascimento'])));
    if($_POST['nome_instituto_ensino'] != "")
        $nome_instituto_ensino = htmlspecialchars(trim($_POST['nome_instituto_ensino']));
    if($_POST['uf'] != "")
        $uf_instituto_ensino = htmlspecialchars(trim($_POST['uf']));
    if($_POST['cidade'] != "")
        $municipio_instituto_ensino = htmlspecialchars(trim($_POST['cidade']));
    if($_POST['conselho'] != "")
        $conselho = htmlspecialchars(trim($_POST['conselho']));
    
    $cpf = $cpf = str_replace('.','',$cpf);
    $cpf = $cpf = str_replace('-','',$cpf);
    
    if(!valida_cpf($cpf))
    {
        erro("CPF Inválido!");
        exit();
    }
    $cpf_formatado = retorna_campo_formatado($cpf);
    
    if($_POST['ano_formacao'] != null && !filter_var($_POST['ano_formacao'], FILTER_VALIDATE_INT))
    {
        erro("Ano de formação Inválido: ".$_POST['ano_formacao']."! O ano de formação deve ser um número!");
        exit();
    }
    if($_POST['cidade'] != null && !filter_var($_POST['cidade'], FILTER_VALIDATE_INT))
    {
        erro("Cidade inválida!");
        exit();
    }
    
    // </editor-fold>
    
    include_once 'conexao.php';
    
    $conexao = new Conexao();
    
    $chave = $_SESSION['chave'];
    
    $datetime = date('Y-m-d H:i:s');
    
    $senha =  hash('sha256', $cpf,$datetime.$chave);
    
    $assinatura =   hash('sha256', $senha);
    
    $assinatura = mb_strtoupper($assinatura, 'UTF-8');
    $assinatura = str_replace('/','',$assinatura);
    $assinatura = str_replace('-','',$assinatura);
    $assinatura = str_replace('"','',$assinatura);
    $assinatura = str_replace(' ','',$assinatura);

    //////////////////////////////////////////////////////
    // Verifica se existe usuário na seleção
    //////////////////////////////////////////////////////
    
    $resultado = $conexao->get_usuario_cpf($cpf);
    if(count($resultado) > 0)
    {
        $apagado = $resultado[0]['apagado'];
        if($apagado == 0) $apagado = "ATIVO";
        if($apagado == 1) $apagado = "DESATIVADO";
        erro("Usuário encontrado $apagado! ".$resultado[0]['nome_completo']." | CPF:".$resultado[0]['cpf']);
        exit();
    }
    
    if($data_nascimento != null && !valida_data($data_nascimento))
    {
        if(!valida_data($_POST['data_nascimento']))
        {
            erro_mensagem("Erro 4848949! Data de nascimento inválida!");
            exit();
        }
    }
    
    ////////////////////////////////////////////////////////
    // Insere candidato no sistemas
    //////////////////////////////////////////////////////
    
    if($_POST)
        $resultado = $conexao->insere_medico_obrigatorio(
                                                $cpf ,
                                                $nome_completo,
                                                $nome_mae,
                                                $ra,
                                                $data_nascimento,
                                                $ano_formacao,
                                                $nome_instituto_ensino ,
                                                $uf_instituto_ensino ,
                                                $municipio_instituto_ensino,
                                                $conselho,
                                                $assinatura             
                                                );
    
       
    $alteracoes_detalhadas =  print_r($resultado, true);
    
    $last_id = (int)$resultado['id_adicionado'];
    if($resultado)
        $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $last_id, "14124", "usuario", "Insert", "Cadastrou o médico obrigatório $cpf", $alteracoes_detalhadas);
    
    $conexao = null;
    echo $last_id;
    header ("Location: ../sistema/usuario_visualiza.php?id_usuario=$last_id");

?>