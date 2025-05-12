<?php 
include_once '../../banco_dados/conexao.php';
include_once '../../sistema/funcoes.php';

$id_candidato = $_GET['id_candidato'];
session_start();

if(!isset($_SESSION['id_usuario']))
{
    erro_relatorio("Erro 823494! A sua sessão expirou! Faça o login no sistema para gerar o relatório");
    exit();
}

if($_SESSION['perfil'] == 'candidato' || $_SESSION['candidato'] == 1)
{
   $id_candidato = $_SESSION['id_usuario']; 
   if($_GET['codigo'] != hash('sha256', $_SESSION['chave']))
   {
        erro_gerar_relatorio_cadastro_candidato("Erro 8145345346 ao gerar relatório!");
        exit();
   }
}

$conexao = new Conexao();
$candidato_relatorio = $conexao->get_usuario_id($id_candidato);

if (count($candidato_relatorio) != 1) 
{ 
    erro_gerar_relatorio_cadastro_candidato("Erro 81456 ao gerar relatório! Candidato não encontrado!");
    exit();
}

$id_criptografado = hash('sha256', $candidato_relatorio[0]['id']);

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
$certificado = strtoupper($candidato_relatorio[0]['certificado']);
$num_ducumento = $candidato_relatorio[0]['num_ducumento'];
$data_expedicao = $candidato_relatorio[0]['data_expedicao'];
$civil_militar = $candidato_relatorio[0]['civil_militar'];
$ativa_reserva = $candidato_relatorio[0]['ativa_reserva'];
$forca = $candidato_relatorio[0]['forca'];
$ano_incorporacao = $candidato_relatorio[0]['ano_incorporacao'];
$posto_grad = $candidato_relatorio[0]['posto_grad'];
$arma_quadro_servico = $candidato_relatorio[0]['arma_quadro_servico'];
$licenciamento = $candidato_relatorio[0]['licenciamento'];

$voluntario_sv_militar = $candidato_relatorio[0]['voluntario_sv_militar']; 
if($voluntario_sv_militar === '0') $voluntario_sv_militar = "Não";
if($voluntario_sv_militar === '1') $voluntario_sv_militar = "Sim";

$arrimo = $candidato_relatorio[0]['arrimo'];
if($arrimo === '0') $arrimo = "Não";
if($arrimo === '1') $arrimo = "Sim";

$obrigatorio = $candidato_relatorio[0]['obrigatorio'];
$situacao_militar = $candidato_relatorio[0]['situacao_militar'];
if($situacao_militar === '0') $situacao_militar = "Não";
if($situacao_militar === '1') $situacao_militar = "Sim";

$antecedentes = $candidato_relatorio[0]['antecedentes'];
if($antecedentes === '0') $antecedentes = "Não";
if($antecedentes === '1') $antecedentes = "Sim";

$forum_civil = $candidato_relatorio[0]['forum_civil'];
if($forum_civil === '0') $forum_civil = "Não";
if($forum_civil === '1') $forum_civil = "Sim";

$forum_criminal = $candidato_relatorio[0]['forum_criminal'];
if($forum_criminal === '0') $forum_criminal = "Não";
if($forum_criminal === '1') $forum_criminal = "Sim";

$solicitou_adiamento = $candidato_relatorio[0]['solicitou_adiamento'];
if($solicitou_adiamento === '0') $solicitou_adiamento = "Não";
if($solicitou_adiamento === '1') $solicitou_adiamento = "Sim";

$data_inicio_adiamento = $candidato_relatorio[0]['data_inicio_adiamento'];
if($data_inicio_adiamento != null) $data_inicio_adiamento = trata_data ($data_inicio_adiamento);

$data_fim_adiamento = $candidato_relatorio[0]['data_fim_adiamento'];
if($data_fim_adiamento != null) $data_fim_adiamento = trata_data ($data_fim_adiamento);

$especialidade_adiamento = $candidato_relatorio[0]['especialidade_adiamento'];

$apto_saude = $candidato_relatorio[0]['apto_saude'];
if($apto_saude === '0') $apto_saude = "INAPTO";
if($apto_saude === '1') $apto_saude = "APTO";

$grupo_saude = strtoupper($candidato_relatorio[0]['grupo_saude']);
$data_exame_saude = $candidato_relatorio[0]['data_exame_saude'];
if($data_exame_saude != null) $data_exame_saude = trata_data ($data_exame_saude);

$cid_saude = $candidato_relatorio[0]['cid_saude'];
$obs_saude = $candidato_relatorio[0]['observacao_exame_saude'];

$transferencia_fisemi = $candidato_relatorio[0]['transferencia_fisemi'];
if($transferencia_fisemi === '0') $transferencia_fisemi = "Não";
if($transferencia_fisemi === '1') $transferencia_fisemi = "Sim";

$fisemi_rm_origem = $candidato_relatorio[0]['fisemi_rm_origem'];
if($fisemi_rm_origem != null) $fisemi_rm_origem = $fisemi_rm_origem . "ª RM";

$fisemi_rm_destino = $candidato_relatorio[0]['fisemi_rm_destino'];
if($fisemi_rm_destino != null) $fisemi_rm_destino = $fisemi_rm_destino . "ª RM";

$refratario_impedido = $candidato_relatorio[0]['refratario_impedido'];
$historico_judicial = $candidato_relatorio[0]['historico_judicial'];
if($historico_judicial === '0') $historico_judicial = "Não";
if($historico_judicial === '1') $historico_judicial = "Sim";


$numero_acao = $candidato_relatorio[0]['numero_acao'];
$data_liminar = $candidato_relatorio[0]['data_liminar'];
if($data_liminar != null) $data_liminar = trata_data ($data_liminar);

$transitou_julgado = $candidato_relatorio[0]['transitou_julgado'];
if($transitou_julgado === '0') $transitou_julgado = "Não";
if($transitou_julgado === '1') $transitou_julgado = "Sim";


$favoravel_desfavoravel = $candidato_relatorio[0]['favoravel_desfavoravel'];
$convocado = $candidato_relatorio[0]['convocado'];
if($convocado === '0') $convocado = "Não";
if($convocado === '1') $convocado = "Sim";

$publicacao_bar_reg = $candidato_relatorio[0]['publicacao_bar_reg'];

$voluntario_12rm = $candidato_relatorio[0]['voluntario_12rm'];
if($voluntario_12rm === '0') $voluntario_12rm = "Não";
if($voluntario_12rm === '1') $voluntario_12rm = "Sim";

$prioridade_forca = $candidato_relatorio[0]['prioridade_forca'];
$dependente = $candidato_relatorio[0]['dependente'];

$medico_obrigatorio = $candidato_relatorio[0]['medico_obrigatorio'];
$instituto_ensino = $candidato_relatorio[0]['instituto_ensino'];
$uf_instituto_ensino = $candidato_relatorio[0]['uf_instituto_ensino'];
$cidade_instituto_ensino = $candidato_relatorio[0]['cidade_instituto_ensino'];
$ano_formacao = $candidato_relatorio[0]['ano_formacao'];

$assinatura_sistema = $candidato_relatorio[0]['assinatura_sistema'];
$apagado = $candidato_relatorio[0]['apagado'];
$nome_selecao =  $candidato_relatorio[0]['nome_selecao'] . " / " . $candidato_relatorio[0]['ano_selecao'];
$codigo_selecao =  $candidato_relatorio[0]['codigo_selecao'];

if($tempo_sv_pub_anos === '0')
    $tempo_sv_pub_anos = "-0-";
if($tempo_sv_pub_meses === '0')
    $tempo_sv_pub_meses = "-0-";
if($tempo_sv_pub_dias === '0')
    $tempo_sv_pub_dias = "-0-";

if($tempo_sv_mil_anos === '0')
    $tempo_sv_mil_anos = "-0-";
if($tempo_sv_mil_meses === '0')
    $tempo_sv_mil_meses = "-0-";
if($tempo_sv_mil_dias === '0')
    $tempo_sv_mil_dias = "-0-";

if($dependente === '0')
    $dependente = " Nenhum ";    

if($voluntario_12rm != null)
{
    if($voluntario_12rm === '1')
        $voluntario_12rm = "Sim";
    else if($voluntario_12rm === '0')
        $voluntario_12rm = "Não";
}

if($data_expedicao != null)
    $data_expedicao = trata_data($data_expedicao);
if($data_nascimento != null)
    $data_nascimento = trata_data($data_nascimento);
    
 $datetime = date('d/m/Y H:i:s');
 
$foto = "red_user.jpeg";
                            
$get_foto = $conexao->get_foto_usuario($id_candidato);  
if(count($get_foto) > 0)
    $foto = $get_foto[0]['nome'];

if($nome_social == null)
    $nome_social = "";

$arquivo_pagamento = $resultado = $conexao->get_arquivo_pagamento($id_candidato);

if(count($arquivo_pagamento) > 0)
    $pagamento = " adicionado <img src='../imagens/ok.png' width='20px'>";
else
    $pagamento = " <font color='red'>NÃO ADICIONADO</font>";
 
$data_hoje = date('d/m/Y');

$selecao = $resultado = $conexao->get_selecao_id();

$ano_selecao = (int)$selecao[0]['ano'];
$ano_selecao_mais_um = $ano_selecao+1;
$codigo = strtoupper($selecao[0]['codigo']);

$eas_ebst = "";

if($codigo == "OTT_STT") $eas_ebst = "EST/EBST";
if($codigo == "MFDV") $eas_ebst = "EAS";

if($codigo == "OTT_STT") $codigo = "OTT/STT";

$get_exames_saude = $conexao->get_exames_medico();  
$sessao = "";
$dia_exame = "";
$cidade = "";
$presidente = "";
$membro_1 = "";
$membro_2 = "";

$data_hoje_testar = date("Y-m-d");

foreach($get_exames_saude as $linha)
{
    if(($linha['dia_exame'] == $data_hoje_testar) || $linha['dia_exame'] == $candidato_relatorio[0]['data_exame_saude'] )
    {
        $sessao = $linha['sessao'];
        $dia_exame = $linha['dia_exame'];
        $cidade = $linha['cidade'];
        $presidente = $linha['presidente'];
        $membro_1 = $linha['membro_1'];
        $membro_2 = $linha['membro_2'];
    }
}

if($cidade == "") $cidade = "Porto Alegre";


$html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>
<br>
<table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong>Sessão $sessao - CSE - $codigo - $eas_ebst - $ano_selecao/$ano_selecao_mais_um</strong>
  </tr>
  <tr>
    <th align='center'><strong>JISE - $codigo - $eas_ebst - $ano_selecao/$ano_selecao_mais_um</strong>
  </tr>
</table> 
<br>
<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   A Junta de Inspeção de Saúde Especial inspecionou na presente sessão, o abaixo declarado, para fins de incorporação no ano de $ano_selecao_mais_um, e sobre seu estado de saúde, proferiu o parecer abaixo:
</p>
<br>
";

 $html = $html. "
     
<table border='0'  style='font-size: 10px; font-family: Times New Roman; width:100%' >

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>IDENTIFICAÇÃO</b></center></td>
</tr>

<tr>
    <td><b>Nome Completo: </b> $nome_completo </td>
    <td><b>CPF: </b> $cpf</td>
</tr>
<tr>
    <td><b>Naturalidade: </b> $naturalidade </td>
    <td><b>Data de Nascimento: </b> $data_nascimento </td>
</tr>";
 
 if($codigo == "MFDV")
 {
      $html = $html. "<tr style='background-color: #D8D8D8'>
        <td colspan=\"2\"> <center><b>INSTITUIÇÃO DE ENSINO</b></center></td>
        </tr>

        <tr>
            <td><b>Nome do Instituto de Ensino: </b>$instituto_ensino</td>
            <td><b>Ano de Formação: </b>$ano_formacao</td>
        </tr>

        <tr>
            <td><b>Cidade Instituto de Ensino: </b>$cidade_instituto_ensino</td>        
            <td><b>UF Instituto de Ensino: </b>$uf_instituto_ensino</td>
        </tr>";
 }


 $html = $html. "
<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>CIVIL/MILTAR</b></center></td>
</tr>

<tr>
    <td><b>Ativa/Reserva: </b>$ativa_reserva</td>
    <td><b>Certificado: </b>$certificado </td>
</tr>

<tr>
    <td><b>Nº do Documento: </b>$num_ducumento </td>
    <td><b>Data da Expedição: </b>$data_expedicao</td>
</tr>

<tr>
    <td><b>Situação Militar: </b>$situacao_militar</td>
    <td><b>Posto/Graduação: </b>$posto_grad</td>
</tr>

<tr>
    <td><b>Força: </b>$forca </td>
    <td><b>Ano de incorporação: </b>$ano_incorporacao</td>
</tr>

<tr>
    <td><b>Arma/Quadro/Serviço: </b>$arma_quadro_servico</td>
    <td><b>Licenciamento: </b>$licenciamento</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>EXAME DE SAÚDE</b></center></td>
</tr>

<tr>
    <td><b>Parecer exame médico: </b>$apto_saude</td>
    <td><b>Grupo do ex med: </b>$grupo_saude</td>
</tr>

<tr>
    <td><b>CID: </b>$cid_saude </td>
    <td><b>Data da realização do ex med: </b>$data_exame_saude</td>
</tr>

<tr>
    <td colspan=\"2\"><b>Observação: </b>$obs_saude </td>
</tr>

</table> ";

                      


$html = $html. "  <br><br>

<table border='0' style='width:100%'>
  <tr>
    <th align='left'><strong> <p style='font-size: 12px; font-family: Times New Roman;'> $cidade - ".$data_hoje."   </p></strong></th>
  </tr>
</table> ";

$html = $html . " <br><br>
 <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%'>
  <tr>
    <th width='33%'>______________________________________</th>
    <th width='33%'>______________________________________</th>
    <th width='33%'>______________________________________</th>
  </tr>
  <tr>
    <th>$presidente</th>
    <th>$membro_1</th>
    <th>$membro_2</th>
  </tr>
</table> ";




$html = $html . " <br>

<p style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;
   Eu, ". mb_strtoupper($nome_completo, "UTF-8").", em _____/_____/20_____, tomei ciência do resultado deste parecer, e caso não  concorde, tenho até 15 dias para requerer Inspeção em grau de recurso.   
</p>

<br><br>

<p align='center' style='font-size: 12px; font-family: Times New Roman; text-align: justify;'>
_________________________________________<br>
". mb_strtoupper($nome_completo, "UTF-8")."
<br>
CPF: ".$cpf." 
</p>
";



//<p class='esquerda' style='font-size: 12px;'>Relatório gerado em $datetime</p>
 
include("mpdf60/mpdf.php");
 

 //$mpdf=new mPDF(); 

$mpdf = new mPDF('',    // mode - default ''
                 '',    // format - A4, for example, default ''
                 0,     // font size - default 0
                 '',    // default font family
                 16,    // margin_left
                 16,    // margin right
                 15,    // margin top
                 15,    // margin bottom
                 9,     // margin header
                 9,     // margin footer
                 'L');  // L - landscape, P - portrait 
 
 
 //$mpdf->SetDisplayMode('fullwidth');
 $mpdf->SetDisplayMode('fullpage');
 $css = file_get_contents("css/estilo.css");
 $mpdf->WriteHTML($css,1);
 $mpdf->WriteHTML($html);
 
$insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20110", "relatorio", "create", "Gerou um relatório de exame médico do candidato CPF $cpf de ID $id_candidato", null);
 
if($insere_log)
    $mpdf->Output("relatorio_exame_medico_candidato_$cpf.pdf",'D');

 exit;