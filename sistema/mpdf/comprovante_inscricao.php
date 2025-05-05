<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';

session_start();

if(!isset($_SESSION['id_usuario']))
{
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}

if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
    $id_candidato = $_SESSION['id_usuario'];
}
else
{
    $id_candidato = (int)$_GET['id_candidato'];
}

$conexao = new Conexao();
$candidato_relatorio = $conexao->get_usuario_id($id_candidato);

if (count($candidato_relatorio) != 1) 
{ 
    erro_gerar_relatorio_cadastro_candidato("Erro 81456 ao gerar o comprovante! Candidato não encontrado!");
    exit();
}

$chave = hash('sha256', $id_candidato.$_SESSION['chave']);

if(!isset($_GET['crip']))
{ 
    erro_gerar_relatorio_cadastro_candidato("Erro 81423423456 ao gerar o comprovante!");
    exit();
}

if($_GET['crip'] != $chave)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 8456 ao gerar o comprovante!");
    exit();
}

$id_selecao = $candidato_relatorio[0]['id_selecao'];
$cpf = $candidato_relatorio[0]['cpf'];
$perfil = $candidato_relatorio[0]['perfil'];
$candidato = $candidato_relatorio[0]['candidato'];
$nome_completo = $candidato_relatorio[0]['nome_completo'];
$trocar_senha = $candidato_relatorio[0]['trocar_senha'];
$concorrendo = $candidato_relatorio[0]['concorrendo'];
$senha = $candidato_relatorio[0]['senha'];
$desistencia = $candidato_relatorio[0]['desistencia'];
$estado_civil = $candidato_relatorio[0]['estado_civil'];
$sexo = $candidato_relatorio[0]['sexo'];
$nome_social = $candidato_relatorio[0]['nome_social'];
$pai = $candidato_relatorio[0]['pai'];
$mae = $candidato_relatorio[0]['mae'];
$identidade = $candidato_relatorio[0]['identidade'];
$nacionalidade = $candidato_relatorio[0]['nacionalidade'];
$naturalidade = $candidato_relatorio[0]['naturalidade'];
$dependente = $candidato_relatorio[0]['dependente'];
$data_nascimento = $candidato_relatorio[0]['data_nascimento'];
$uf = $candidato_relatorio[0]['uf'];
$cep = $candidato_relatorio[0]['cep'];
$cidade = $candidato_relatorio[0]['nome_cidade'];
$rua_num_complemento = $candidato_relatorio[0]['rua_num_complemento'];
$bairro = $candidato_relatorio[0]['bairro'];
$tel_residencial = $candidato_relatorio[0]['tel_residencial'];
$tel_celular = $candidato_relatorio[0]['tel_celular'];
$mail = $candidato_relatorio[0]['mail'];
$tempo_sv_pub = $candidato_relatorio[0]['tempo_sv_pub'];
$tempo_sv_pub_anos = $candidato_relatorio[0]['tempo_sv_pub_anos'];
$tempo_sv_pub_meses = $candidato_relatorio[0]['tempo_sv_pub_meses'];
$tempo_sv_pub_dias = $candidato_relatorio[0]['tempo_sv_pub_dias'];
$tempo_sv_mil = $candidato_relatorio[0]['tempo_sv_mil'];
$tempo_sv_mil_anos = $candidato_relatorio[0]['tempo_sv_mil_anos'];
$tempo_sv_mil_meses = $candidato_relatorio[0]['tempo_sv_mil_meses'];
$tempo_sv_mil_dias = $candidato_relatorio[0]['tempo_sv_mil_dias'];
$certificado = $candidato_relatorio[0]['certificado'];
$num_ducumento = $candidato_relatorio[0]['num_ducumento'];
$data_expedicao = $candidato_relatorio[0]['data_expedicao'];
$civil_militar = $candidato_relatorio[0]['civil_militar'];
$ativa_reserva = $candidato_relatorio[0]['ativa_reserva'];
$forca = $candidato_relatorio[0]['forca'];
$ano_incorporacao = $candidato_relatorio[0]['ano_incorporacao'];
$posto_grad = $candidato_relatorio[0]['posto_grad'];
$arma_quadro_servico = $candidato_relatorio[0]['arma_quadro_servico'];
$licenciamento = $candidato_relatorio[0]['licenciamento'];
$assinatura_sistema = $candidato_relatorio[0]['assinatura_sistema'];
$apagado = $candidato_relatorio[0]['apagado'];
$etapa = $candidato_relatorio[0]['etapa'];
$nome_selecao =  $candidato_relatorio[0]['nome_selecao'] . " / " . $candidato_relatorio[0]['ano_selecao'];

$get_selecao = $conexao->get_selecao_id();

///////////////////////////////////////
//////////////
///////////// VALIDAÇÕES
////////////
///////////////////////////////////////

if(inscricao())
{
    erro_gerar_relatorio_cadastro_candidato("Erro 272344! Não foi possível gerar o comprovante de inscrição! Inscrição em andamento!");
    exit();
}


if((int)$etapa < 2)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 236437457! Não foi possível gerar o comprovante de inscrição!");
    exit();
}


if($get_selecao[0]['eliminar_docs_obrigatorios'] == '1') 
{
    $lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($id_candidato);
    $get_candidato = $conexao->get_usuario_id($_SESSION['id_usuario']);
    $lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato,$lista_docs_obrigatorios_sobrando_candidato);

    $quantidade_docs_faltantes = count($lista_docs_obrigatorios_sobrando);

    if($quantidade_docs_faltantes > 0)
    {
        erro_gerar_relatorio_cadastro_candidato("Erro 235345! $quantidade_docs_faltantes Documentos de Inscrição faltando! Não foi possível gerar o comprovante de inscrição!");
        exit();
    }
}

$inscricoes = $conexao->get_especialidade_candidato($id_candidato);
if(count($inscricoes) == 0)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 14234235! Nenhuma inscrição realizada! Não foi possível gerar o comprovante de inscrição!");
    exit();
}


$foto = "red_user.jpeg";
                            
$get_foto = $conexao->get_foto_usuario($id_candidato);  
if(count($get_foto) > 0)
    $foto = $get_foto[0]['nome'];
/*
else
{
    erro_gerar_relatorio_cadastro_candidato("Erro 6585! Nenhuma foto adicionada! Não foi possível gerar o comprovante de inscrição!");
    exit();
}

$arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($id_candidato);

if(count($arquivo_pagamento) == 0)
{
    erro_gerar_relatorio_cadastro_candidato("Erro 6780585! Arquivo de pagamento/isenção não adicionado! Não foi possível gerar o comprovante de inscrição!");
    exit();
}
*/
///////////////////////////////////////
//////////////
///////////// PASSOU NAS VALIDAÇÕES
////////////
///////////////////////////////////////

$insere_log = $conexao->insere_log($id_candidato, $cpf, null, "18101", "Inscrição", "Relatório", "Candidato $cpf gerou o relatório de inscrição", null);


if($tempo_sv_pub_anos == 0)
    $tempo_sv_pub_anos = "-0-";
if($tempo_sv_pub_meses == 0)
    $tempo_sv_pub_meses = "-0-";
if($tempo_sv_pub_dias == 0)
    $tempo_sv_pub_dias = "-0-";

if($tempo_sv_mil_anos == 0)
    $tempo_sv_mil_anos = "-0-";
if($tempo_sv_mil_meses == 0)
    $tempo_sv_mil_meses = "-0-";
if($tempo_sv_mil_dias == 0)
    $tempo_sv_mil_dias = "-0-";

if($dependente == 0)
    $dependente = "Não possui";    

if($data_expedicao != null)
    $data_expedicao = trata_data($data_expedicao);
if($data_nascimento != null)
    $data_nascimento = trata_data($data_nascimento);
    
 $datetime = date('d/m/Y H:i:s');

if($nome_social == null)
    $nome_social = "Não possui";


 $html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>
     
 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong><u>COMPROVANTE DE INSCRIÇÃO</u></strong></th>
  </tr>
  <tr>
    <th align='center'><strong>".$nome_selecao."</strong></th>
  </tr>
</table> 

<br>

 <table border='0' style='width:100%'>
  <tr>
    <th align='left'><strong>Inscrição do Candidado <u>Nº: ".$id_candidato."</u></strong></th>
    <th align='right'><img src='../fotos/$foto'  height='70px'> <strong><u>   </strong></th>
  </tr>
</table> 



<table border='0'  style='font-size: 12px; font-family: Times New Roman; width:100%' >

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>IDENTIFICAÇÃO DO CANDIDATO </b></center></td>
</tr>

<tr>
      <td><b>Nome Completo: </b> $nome_completo </td>
      <td><b>CPF: </b> $cpf</td>
  </tr>
    
<tr>
    <td><b>Estado Civil: </b> $estado_civil </td>
    <td><b>Nome Social: </b> $nome_social </td>
</tr>
    
<tr>
    <td><b>Identidade: </b> $identidade </td>
    <td><b>Data de Nascimento: </b> $data_nascimento </td>
</tr>

<tr>
    <td><b>Nome da pai: </b> $pai </td>
    <td><b>Sexo: </b> $sexo </td>    
</tr>
    
<tr>
    <td><b>Nome da mãe: </b> $mae </td>
    <td><b>E-Mail: </b> $mail </td>
</tr>

<tr>
    <td><b>Nacionalidade (País): </b> $nacionalidade </td>
    <td><b>Naturalidade (Cidade): </b> $naturalidade </td>
</tr>

<tr>
    <td><b>UF: </b> $uf </td>
    <td><b>Cep: </b> $cep </td>
</tr>

<tr>
    <td><b>Bairro: </b> $bairro </td>
    <td><b>Cidade: </b> $cidade </td>
</tr>

<tr>
    <td colspan=\"2\"><b>Endereço Completo: </b> $rua_num_complemento </td>
</tr>

<tr>
    <td><b>Telefone de Recados: </b> $tel_residencial </td>
    <td colspan=\"2\"><b>Telefone de Contato: </b> $tel_celular </td>
</tr>

</table> 

<table border='0'  style='font-size: 12px; font-family: Times New Roman; width:100%' >

<tr>
    <td><b>Serviço Militar Anos: </b>$tempo_sv_mil_anos </td>
    <td><b>Serviço Militar Meses: </b>$tempo_sv_mil_meses </td>
    <td><b>Serviço Militar Dias: </b>$tempo_sv_mil_dias </td>
</tr>
</table>

<table border='0'  style='font-size: 12px; font-family: Times New Roman; width:100%' >

<tr>
    <td colspan='3'><b>Civil/Militar: </b>$civil_militar</td>
</tr>
" ;
    
                            $html = $html . "<tr>";
if($certificado != null)    $html = $html . "<td><b>Certificado: </b>$certificado </td>";
if($num_ducumento != null)  $html = $html . "<td><b>Nº do Documento: </b>$num_ducumento </td>";
if($data_expedicao != null) $html = $html . "<td><b>Data da Expedição: </b>$data_expedicao</td>";
                            $html = $html . "</tr>";

if($civil_militar == 'militar' || $ativa_reserva == 'ja_foi_militar')
{
    $html = $html . "<tr>";
    $html = $html . "<td><b>Ativa/Reserva: </b>$ativa_reserva</td>";
    $html = $html . "<td><b>Força: </b>$forca </td>";
    $html = $html . "<td><b>Ano de incorporação: </b>$ano_incorporacao</td>";
    $html = $html . "</tr>";

    $html = $html . "<tr>";
        $html = $html . "<td><b>Posto/Graduação: </b>$posto_grad</td>";
        $html = $html . "<td><b>Arma/Quadro/Serviço: </b>$arma_quadro_servico</td>";
    if($licenciamento != null)
        $html = $html . "<td><b>Licenciamento: </b>$licenciamento</td>";
    $html = $html . "</tr>";
}

$html = $html . "</table> 
        <br>";



///////////////////////////////////////////////
// ESPECIALIZAÇÕES CADASTRADAS
///////////////////////////////////////////////

$html = $html. " <br>

<table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >
    
    <tr style='background-color: #D8D8D8'>
        <td colspan=\"3\"> <center><b>ESPECIALIDADE(S)</b></center></td>
    </tr>
</table> 
";


                    
foreach ($inscricoes as $valor)
{
    $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_candidato,$valor['id_especialidade']);  
    
    $get_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($valor['id_candidato_x_especialidade']);
    
    // ESPECIALIDADE SELECIONADA
    $html = $html. " 
    <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >
        <tr>
            <td><b>Especialidade  ".mb_strtoupper($valor['ott_stt'], "UTF-8").": ".$valor['especialidade'] ."</td>
        </tr>
    </table>
";
    
}


$html = $html. "        
        
<br>
<br>
<br>
<p class='center' style='font-size: 12px;'>Relatório gerado em $datetime</p>

<p class='center' style='font-size: 16px;'>$assinatura_sistema</p>



";
//<p class='direita' style='font-size: 12px;'>Relatório gerado em $datetime</p>
 
include("mpdf60/mpdf.php");
 

 $mpdf=new mPDF(); 
 //$mpdf->SetDisplayMode('fullwidth');
 $mpdf->SetDisplayMode('fullpage');
 $css = file_get_contents("css/estilo.css");
 $mpdf->WriteHTML($css,1);
 $mpdf->WriteHTML($html);
 
 $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20109", "relatorio", "create", "Gerou o comprovante de inscrição", null);
 if($insere_log)
    $mpdf->Output();

 exit;