<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';

session_start();

if(!isset($_SESSION['id_usuario']))
{
    erro_mensagem("Erro 567967! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}

if($_SESSION['perfil'] != 'admin')
{
    erro_mensagem("Erro 94679567456! Não foi possível gerar o ofício!");
    exit();
}

$id_recurso = null;
if(!isset($_GET['id_recurso']))
{ 
    erro_mensagem("Erro 2353467457! Não foi possível gerar o ofício!");
    exit();
}

$crip = null;
if(!isset($_GET['crip']))
{ 
    erro_mensagem("Erro 457457435! Não foi possível gerar o ofício!");
    exit();
}

$id_recurso = (int)htmlspecialchars($_GET['id_recurso']);
$crip = htmlspecialchars($_GET['crip']);

if($crip != hash('sha256', $id_recurso))
{
    erro_mensagem("Erro 3474564565! Não foi possível gerar o ofício!");
    exit();
}


$conexao = new Conexao();
$get_recurso_id = $conexao->get_recurso_id($id_recurso);

if(count($get_recurso_id) != 1)
{ 
    erro_mensagem("Erro 3474564565! Não foi possível gerar o ofício!");
    exit();
}


$candidato_relatorio = $conexao->get_usuario_id($get_recurso_id[0]['id_candidato']);


$id_selecao = $candidato_relatorio[0]['id_selecao'];
$nome_completo = $candidato_relatorio[0]['nome_completo'];
$sexo = $candidato_relatorio[0]['sexo'];
$uf = $candidato_relatorio[0]['uf'];
$cep = $candidato_relatorio[0]['cep'];
$cidade = $candidato_relatorio[0]['nome_cidade'];
$rua_num_complemento = $candidato_relatorio[0]['rua_num_complemento'];
$bairro = $candidato_relatorio[0]['bairro'];
$assinatura_sistema = $candidato_relatorio[0]['assinatura_sistema'];
$apagado = $candidato_relatorio[0]['apagado'];
$nome_selecao =  $candidato_relatorio[0]['nome_selecao'] . " / " . $candidato_relatorio[0]['ano_selecao'];

if($apagado == '1')
{
    erro_mensagem("Erro 42357347547! Não foi possível gerar o ofício! Candidato APAGADO!");
    exit();
}
$nome_especialidade = null;
if($get_recurso_id[0]['id_especialidade'] != null)
{
    $get_especialidade = $conexao->get_especialidade_id($get_recurso_id[0]['id_especialidade']);
    $nome_especialidade = $get_especialidade[0]['nome'];
}

$reverencia = null;
if($sexo == 'masculino') $reverencia = 'Senhor';
if($sexo == 'feminino')  $reverencia = 'Senhora';

$get_selecao = $conexao->get_selecao_id();

$codigo = substr($assinatura_sistema, 0,16);

$datetime = date('d_m_Y_H_i_s');

$data_aberura = trata_data($get_recurso_id[0]['data_abertura']);

$status = null;
if($get_recurso_id[0]['status_final'] != null && $get_recurso_id[0]['status_final'] == 'deferido') $status = 'DEFERIMENTO';
if($get_recurso_id[0]['status_final'] != null && $get_recurso_id[0]['status_final'] == 'deferido_parcialmente') $status = 'DEFERIMENTO PARCIAL';
if($get_recurso_id[0]['status_final'] != null && $get_recurso_id[0]['status_final'] == 'indeferido') $status = 'INDEFERIMENTO';
 
$html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
</p>
     
 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong>".$nome_selecao."</strong></th>
  </tr>
  <tr>
    <th align='center'><strong>OFÍCIO RESPOSTA DE RECURSO</strong></th>
  </tr>
</table> 

<p align='left'> 
    <b>Ofício Nº: </b>".$id_recurso."<br>
    <b>EB:</b> ".$id_recurso.'_'.$codigo.'_'.$datetime."
</p>
<p align='right'>".$get_recurso_id[0]['cidade_data']."</p> 



Sua Senhoria 
<br>
$nome_completo
<br>
$nome_especialidade
<br>
$rua_num_complemento, $bairro - $cidade / $uf
<br>
<br>
Assunto: ".$status." de recurso

<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
$reverencia,

<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Em atenção ao recurso, datado de $data_aberura, apresentado por V.Sa, informo a solução dada ao mesmo, de acordo com as razões abaixo descritas:

<br>
<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
".$get_recurso_id[0]['paragrafo1']." 

<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
".$get_recurso_id[0]['paragrafo2']." 


<br>
<br>
<br>
<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Atenciosamente,

<p class='center'>
______________________________________
<br>

".$get_recurso_id[0]['presidente']."
<br>
Presidente da Comissão de Seleção Especial
</p>
";
//<p class='direita' style='font-size: 12px;'>Relatório gerado em $datetime</p>

$id_candidato = $get_recurso_id[0]['id_candidato'];

include("mpdf60/mpdf.php");
 
$mpdf=new mPDF(); 
//$mpdf->SetDisplayMode('fullwidth');
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML($html);

$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], $id_candidato, "20106", "recurso", "Relatório", "Operador ".$_SESSION['cpf']." gerou um ofício de resposta do recurso $id_recurso", null);
if($insere_log)
    $mpdf->Output();

 exit();