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
$certificado = mb_strtoupper($candidato_relatorio[0]['certificado']);
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
if($apto_saude === '0') $apto_saude = "Não";
if($apto_saude === '1') $apto_saude = "Sim";

$grupo_saude = mb_strtoupper($candidato_relatorio[0]['grupo_saude']);
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
 

 $html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
         " .$_SESSION['cabecalho_relatorio']. " 
    
</p>";

 $html = $html. "
     
 <table border='0' style='width:100%; font-size: 11px;'>
  <tr>
    <th align='center'><strong>".mb_strtoupper("MÉDICO OBRIGATÓRIO","UTF-8")." Nº: ".$id_candidato."</strong></th>
  </tr>
</table> 


<table border='0'  style='font-size: 10px; font-family: Times New Roman; width:100%' >

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>IDENTIFICAÇÃO</b></center></td>
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
    <td><b>Nome do pai: </b> $pai </td>
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
    <td><b>Telefone de Recados: </b> $tel_residencial </td>
    <td><b>Telefone de Contato: </b> $tel_celular </td>
</tr>

<tr>
   <td><b>Dependentes: </b> $dependente</td>
   <td><b>Voluntário para 12ª RM: </b>$voluntario_12rm </td>
   
</tr>

<tr>
    <td><b>Prioridade de Força: </b> $prioridade_forca </td>
    <td><b>Civil/Militar: </b>$civil_militar</td>
</tr>

<tr>
    <td><b>Voluntário para o SV Militar: </b>$voluntario_sv_militar</td>
    <td><b>Arrimo: </b>$arrimo</td>
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

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>ENSINO</b></center></td>
</tr>

<tr>
    <td><b>Nome do Instituto de Ensino: </b>$instituto_ensino</td>
    <td><b>Ano de Formação: </b>$ano_formacao</td>
</tr>

<tr>
    <td><b>Cidade Instituto de Ensino: </b>$cidade_instituto_ensino</td>        
    <td><b>UF Instituto de Ensino: </b>$uf_instituto_ensino</td>
</tr>

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
    <td colspan=\"2\"> <center><b>SAÚDE</b></center></td>
</tr>

<tr>
    <td><b>Apto exame médico: </b>$apto_saude</td>
    <td><b>Grupo do ex med: </b>$grupo_saude</td>
</tr>

<tr>
    <td><b>CID: </b>$cid_saude </td>
    <td><b>Data da realização do ex med: </b>$data_exame_saude</td>
</tr>

<tr>
    <td colspan=\"2\"><b>Observação: </b>$obs_saude </td>
</tr>";



/*

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>ADIAMENTO</b></center></td>
</tr>

<tr>
    <td><b>Solicitou adiamento: </b>$solicitou_adiamento</td>
    <td><b>Especialidade do adiamento: </b>$especialidade_adiamento</td>
</tr>

<tr>
    <td><b>Início do adiamento: </b>$data_inicio_adiamento</td>
    <td><b>Fim do adiamento: </b>$data_fim_adiamento</td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"2\"> <center><b>JUSTIÇA</b></center></td>
</tr>

<tr>
    <td><b>Cert Neg de Fórum Civil: </b>$forum_civil</td>
    <td><b>Cert Neg de Fórum Criminal: </b>$forum_criminal</td>
</tr>

<tr>
    <td><b>Refratário/Impedimento Judicial: </b>$refratario_impedido</td>
    <td><b>Histórico Judicial: </b>$historico_judicial</td>
</tr>

<tr>
    <td><b>Data da Liminar: </b>$data_liminar</td>
    <td><b>Número da ação: </b>$numero_acao </td>
</tr>

<tr>
    <td><b>Transitou em Julgado: </b>$transitou_julgado</td>
    <td><b>Convocado: </b>$convocado</td>
</tr>

<tr>
    <td><b>Favorável/Desfavorável: </b>$favoravel_desfavoravel </td>
    <td><b>Publicação em BAR Reg, Nº e Data: </b>$publicacao_bar_reg </td>
</tr>

<tr>
    <td colspan=\"2\"><b>Atestado de antecedentes: </b>$antecedentes</td>
</tr>

</table> 

<table border='0'  style='font-size: 10px; font-family: Times New Roman; width:100%' >

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>FISEMI</b></center></td>
</tr>


<tr>
    <td><b>Transferência da FISEMI: </b>$transferencia_fisemi</td>
    <td><b>RM de ORIGEM: </b>$fisemi_rm_origem</td>
    <td><b>RM de DESTINO: </b>$fisemi_rm_destino </td>
</tr>

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>TEMPO DE SERVIÇO</b></center></td>
</tr>

";

if($tempo_sv_pub == "0") 
{
    $html = $html. " 
    <tr>
        <td><b>Serviço Público: </b> Não possui </td>
    </tr>";
}
else
{
    $html = $html. " 
    <tr>
       <td><b>Serviço Público Anos: </b> $tempo_sv_pub_anos </td>
       <td><b>Serviço Público Meses: </b>$tempo_sv_pub_meses </td>
       <td><b>Serviço Público Dias: </b> $tempo_sv_pub_dias </td>
   </tr>";
}

if($tempo_sv_mil == "0") 
{
    $html = $html. " 
    <tr>
        <td><b>Serviço Militar: </b> Não possui </td>
    </tr>";
}
else
{
    $html = $html. " 
    <tr>
        <td><b>Serviço Militar Anos: </b>$tempo_sv_mil_anos </td>
        <td><b>Serviço Militar Meses: </b>$tempo_sv_mil_meses </td>
        <td><b>Serviço Militar Dias: </b>$tempo_sv_mil_dias </td>
    </tr>";
}
 

    
*/

$html = $html. "

<tr style='background-color: #D8D8D8'>
    <td colspan=\"3\"> <center><b>OBSERVAÇÕES</b></center></td>
</tr>

    <tr>";


// OBSERVAÇÕES DO CANDIDATO!

$lista_observacaoes = $conexao->get_observacoes_candidato($id_candidato);

$html = $html. "<td>";

$obs_cadastradas = null; 
foreach ($lista_observacaoes as $observacao)
{
    if($observacao['sistema'] == '0')
       $obs_cadastradas = $obs_cadastradas . " " . $observacao['observacao'] . " | ";
}

$html = $html. "  $obs_cadastradas  </td>
    </tr>
</table> ";


///////////////////////////////////////////////
// ESPECIALIZAÇÕES CADASTRADAS
///////////////////////////////////////////////

$html = $html. " 
<table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%' >
    
    <tr style='background-color: #D8D8D8'>
        <td colspan=\"3\"> <center><b>ESPECIALIDADE(S)</b></center></td>
    </tr>
</table> ";

$inscricoes = $conexao->get_especialidade_candidato($id_candidato);
         
$especialidades = "<b>Especialidade(s):</b> ";

foreach ($inscricoes as $valor)
{
    $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_candidato,$valor['id_especialidade']);  
    
    $get_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($valor['id_candidato_x_especialidade']);
    
    $prioridades = null;
    
    foreach($get_prioridade_cidades as $prioridade)
    {
        $prioridades = $prioridades . " " . $prioridade['prioridade']."ª "." ".$prioridade['nome'] . " | ";
    }
    
    $especialidades = $especialidades . " <u><b>" . $valor['especialidade'] . "</b></u> Prioridades de cidades:  $prioridades <br> ";
    
    // ESPECIALIDADE SELECIONADA
    
}

if(count($inscricoes) == 0) $especialidades = "Nenhuma especialidade cadastrada.";

$html = $html. " 
    <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%' >
        <tr>
            <td>
                ". $especialidades ."
            </td>
        </tr>
    </table> ";
                      


$html = $html. "  
<p class='direita' style='font-size: 10px;'> DATA: $datetime</p>";

$html = $html . " <br>
 <table border='0' style='font-size: 10px; font-family: Times New Roman; width:100%'>
  <tr>
    <th>_________________________________________</th>
    <th>_________________________________________</th>
    <th>_________________________________________</th>
  </tr>
  <tr>
    <th  style='width:33%'>Presidente da CSE</th>
    <th  style='width:33%'>". mb_strtoupper($nome_completo, "UTF-8")."</th>
    <th  style='width:33%'>Avaliador/Entrevistador</th>
  </tr>
</table> ";



//<p class='esquerda' style='font-size: 12px;'>Relatório gerado em $datetime</p>
 
include("mpdf60/mpdf.php");
 

 //$mpdf=new mPDF(); 

$mpdf = new mPDF('',    // mode - default ''
                 '',    // format - A4, for example, default ''
                 0,     // font size - default 0
                 '',    // default font family
                 12,    // margin_left
                 12,    // margin right
                 12,    // margin top
                 12,    // margin bottom
                 9,     // margin header
                 9,     // margin footer
                 'L');  // L - landscape, P - portrait 
 
 
 //$mpdf->SetDisplayMode('fullwidth');
 $mpdf->SetDisplayMode('fullpage');
 $css = file_get_contents("css/estilo.css");
 $mpdf->WriteHTML($css,1);
 $mpdf->WriteHTML($html);
 
 $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20107", "relatorio", "create", "Gerou um relatório de informações do médico obrigatório CPF $cpf de ID $id_candidato", null);
 
 if($insere_log)
    $mpdf->Output("relatorio_candidato_$cpf.pdf",'D');

 exit;