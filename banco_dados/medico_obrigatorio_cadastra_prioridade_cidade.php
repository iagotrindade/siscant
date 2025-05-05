<?php	

include_once '../sistema/funcoes.php';
session_start();

if(!$_POST)
{
    erro_mensagem("Erro 5645645644!");
    exit();
}

if(!isset($_SESSION['chave']) || !isset($_SESSION['selecao']))
{
    header("Location: ../index.php?erro=346154");
    exit();
}

if($_SESSION['perfil'] != 'admin' || $_SESSION['candidato'] == 1)
{
    $conexao = null;
    erro("Erro: 436347547! Sem permissão!");
    exit(); 
}


if( $_POST['crip'] != hash('sha256', $_SESSION['id_usuario'].$_SESSION['chave']))
{
    $conexao = null;
    erro("Erro: 346436! Não foi possível cadastrar a prioridade!");
    exit();  
}

include_once 'conexao.php';
$conexao = new Conexao();



$id_cidade = null;
$prioridade = null;
$id_especialidade = null;

$id_usuario_medico = $_POST['id_medico'];
$cpf_medico = $_POST['c_p_f_medico'];

if($cpf_medico == null || $cpf_medico == '' || $id_usuario_medico == null || $id_usuario_medico == '')
{
    erro("Erro 23462457643! Não foi possível fazer o cadastro!");
    exit();
}

$id_cidade = $_POST['id_cidade'];
$prioridade = $_POST['prioridade'];
$id_especialidade = $_POST['esp'];

if(!isset($_POST['id_cidade'])  || !isset($_POST['esp'])
|| !isset($_POST['prioridade']) || $prioridade == "" || $id_cidade == "" || $id_especialidade == "")
{
    erro("O campo CIDADE e PRIORIDADE são obrigatórios");
    exit();
}

$get_especialidade_id = $conexao->get_especialidade_id($id_especialidade);
$nome_especialidade = $get_especialidade_id[0]['nome'];

if($nome_especialidade == null || $nome_especialidade == "")
{
    $conexao = null;
    erro("Erro: 411222354! Não foi possível cadastrar!");
    exit();    
}

$get_id_candidato_x_especialidade = $conexao->get_id_candidato_x_especialidade($id_usuario_medico,$id_especialidade);

if(count($get_id_candidato_x_especialidade) == 0)
{
    $conexao = null;
    erro("Erro: 458568657! Não foi possível cadastrar a especialidade!");
    exit();
}

$id_candidato_x_especialidade = $get_id_candidato_x_especialidade[0]['id'];

if($id_candidato_x_especialidade == null || $id_candidato_x_especialidade <= 0)
{
    $conexao = null;
    erro("Erro: 411224! Não foi possível cadastrar a especialidade!");
    exit();
}

$get_prioridade_especialidade_candidato = $conexao->get_prioridade_especialidade_candidato($id_candidato_x_especialidade);

$contador = 0;

foreach ($get_prioridade_especialidade_candidato as &$linha) 
{
    if($linha['prioridade'] == $prioridade)
    {
        $conexao = null;
        erro("Erro: 4123445624! Não foi possível cadastrar a especialidade!");
        exit();
    }
    if($linha['id_cidade'] == $id_cidade)
    {
        $conexao = null;
        erro("Erro: 457457! Não foi possível cadastrar a especialidade!");
        exit();
    }
    $contador++;
}

if($prioridade !=  $contador+1)
{
    $conexao = null;
    erro("Erro: 99686786! Não foi possível cadastrar a especialidade!");
    exit();
}

$quantidade_cidades = $conexao->get_quantidade_cidades_especialidade($id_especialidade);  
$quantidade_cidades = $quantidade_cidades[0]['quantidade'];

if($prioridade > $quantidade_cidades)
{
    $conexao = null;
    erro("Erro: 2436347! Não foi possível cadastrar!");
    exit();
}

$get_cidades_especialidade = $conexao->get_cidades_especialidade($id_especialidade);
$cidade_nao_encontrada_na_especialidade = true;
foreach ($get_cidades_especialidade as &$linha) 
{
    if($linha['id'] == $id_cidade)
    {
        $cidade_nao_encontrada_na_especialidade = false;
        break;
    }
}
if($cidade_nao_encontrada_na_especialidade)
{
    $conexao = null;
    erro("Erro: 546868! Não foi possível cadastrar!");
    exit();
}


if($_POST)
    $resultado = $conexao->cadastra_prioridade($id_candidato_x_especialidade,$prioridade,$id_cidade);

$id_prioridade_adicionada = (int)$resultado['id_adicionado'];

if($id_prioridade_adicionada == null || $id_prioridade_adicionada == "")
{
    $conexao = null;
    erro("Erro: 411! Não foi possível cadastrar a prioridade!");
    exit();
}

$alteracoes_detalhadas =  print_r($resultado, true);

if($resultado)
    $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $cpf_medico, "$id_prioridade_adicionada", "14127", "prioridade_cidade", "Insert", "Cadastrou prioridade $prioridade na Especialização $nome_especialidade para o médico obrigatório: $cpf_medico", "$alteracoes_detalhadas");
else
{
    $conexao = null;
    erro("Erro 4545584 Cidade não cadastrado!");
    exit();
}

header("Location: ../sistema/edita_medico_obrigatorio.php?id_usuario=$id_usuario_medico&salvo=especialidade#especialidade");


?>



