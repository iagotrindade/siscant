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

$get_selecao = $conexao->get_selecao_id();
$liberacao_avaliacao_curricular = 0;
if(count($get_selecao) > 0) $liberacao_avaliacao_curricular = $get_selecao[0]['liberacao_avaliacao_curricular'];

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
$companheiro = $candidato_relatorio[0]['companheiro'];
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
$autodeclaracao = $candidato_relatorio[0]['autodeclaracao'];//ASPSILVA 19MAIO25
$vaga_reservada = $candidato_relatorio[0]['vaga_reservada'];


 if ($vaga_reservada == 0 || ($vaga_reservada == null))  $vaga_reservada = "Não";
 if ($vaga_reservada == 1) $vaga_reservada = "Sim";
                                


//EIPOT
$curso_graduacao = $candidato_relatorio[0]['curso_graduacao'];
$ano_formacao_ofor = $candidato_relatorio[0]['ano_formacao_ofor'];
$nota_ofor = $candidato_relatorio[0]['nota_ofor'];
$arma_eipot = $candidato_relatorio[0]['arma_eipot'];

$cidade_etapas_presenciais = $candidato_relatorio[0]['cidade_etapas_presenciais'];

if($companheiro != null) $companheiro = " com " . $companheiro;

$inscricao = "Em análise";

if($candidato_relatorio[0]['etapa'] > 1)
    $inscricao = "Inscrição realizada com sucesso";
if($candidato_relatorio[0]['medico_obrigatorio'] == 1)
    $inscricao = "Médico Obrigatório";

$voluntario_12rm = $candidato_relatorio[0]['voluntario_12rm'];
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
    $dependente = "-0-";    

if($voluntario_12rm != null)
{
    if($voluntario_12rm == 1)
        $voluntario_12rm = "Sim";
    else if($voluntario_12rm == 0)
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
 
/*
 $html = "

<p class='center' style='font-size: 10px;'>

    <img src='../imagens/brasao.png' width='70px'><br>
        MINISTÉRIO DA DEFESA<br>
        EXÉRCITO BRASILEIRO<br>
        COMANDO MILITAR DO SUL<br>
        COMANDO DA 3ª REGIÃO MILITAR<br>
        (Gov das Armas Prov do RS/1821)<br>
        REGIÃO DOM DIOGO DE SOUZA<br>
    
</p>
*/
 $html = "
     
 <table border='0' style='width:100%'>
  <tr>
    <th align='center'><strong><u>".mb_strtoupper($nome_selecao,"UTF-8")."</u></strong></th>
  </tr>
</table> 

<br>

 <table border='0' style='width:100%'>
  <tr>
    <th align='left'><strong>Relatório do Candidado <u>Nº: ".$id_candidato."</u></strong>
    <br>
    <p  style='font-size: 12px;'><b>Status da inscrição</b>: $inscricao</p>
    ";
 
    if($_SESSION['selecao_pagamento'] == true)
        $html = $html . 
        "
            <br><p style='font-size: 11px; font-family: Times New Roman; width:100%'>Arquivo de pagamento/isenção $pagamento  </p>
        ";
    
     $html = $html . "    
    </th>
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
    <td><b>Estado Civil: </b> $estado_civil $companheiro </td>
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

";

 if($_SESSION['selecao_codigo'] == 'mfdv')
{
    $html = $html . 
    "<tr>
        <td colspan='3'><b>Nome do Instituto de Ensino: </b>$instituto_ensino</td>
    </tr>
    <tr>
        <td><b>UF Instituto de Ensino: </b>$uf_instituto_ensino</td>
        <td><b>Cidade Instituto de Ensino: </b>$cidade_instituto_ensino</td>
        <td><b>Ano de Formação: </b>$ano_formacao</td>
    </tr>
    <tr>
       <td><b>Dependentes: </b> $dependente</td>
   </tr>";
    
}

if(isset($_SESSION['eipot']))
{
    $html = $html . 
    "
    <tr>
        <td colspan='2'><b>Curso de Graduação: </b>$curso_graduacao</td>
        <td><b>Arma EIPOT: </b>$arma_eipot</td>
    </tr>
    <tr>
        <td colspan='2'><b>Ano de Formação OFOR: </b>$ano_formacao_ofor</td>
        <td><b>Nota OFOR: </b>$nota_ofor</td>
    </tr>";
}

 $html = $html . "
 

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
 
if(isset($_SESSION['6_regiao']) || isset($_SESSION['12_regiao']))
{
    $html = $html . 
    "<tr>
        <td colspan='3'><b> Cidade de Apresentação nas Etapas Presenciais: </b>$cidade_etapas_presenciais</td>
    </tr>";
}
    
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
$html = $html ."
<tr>
      <td><b>Auto Declaração: </b> $autodeclaracao </td>
      <td><b>Concorrendo vaga reservada: </b> $vaga_reservada</td>
  </tr>";

$html = $html . "</table> 
        <br>";




///////////////////////////////////////////////
// DOCUMENTOS OBRIGATÓRIOS
///////////////////////////////////////////////
$lista_docs_obrigatorios_adicionados = $conexao->get_docs_obrigatorios_inseridos_candidato($id_candidato);  
$lista_docs_obrigatorios_sobrando_candidato = $conexao->get_documentos_obrigatorios_sobrando_candidato($id_candidato);  
$get_candidato = $conexao->get_usuario_id($id_candidato);
$lista_docs_obrigatorios_sobrando = retorna_docs_obrigatorios_sobrando_candidato($get_candidato,$lista_docs_obrigatorios_sobrando_candidato);

$quantidade_docs_faltantes = count($lista_docs_obrigatorios_sobrando);

$html = $html . "
        <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >

        <tr style='background-color: #D8D8D8'>
            <td colspan=\"3\"> <center><b> DOCUMENTOS DE INSCRIÇÃO CONFORME O AVISO DE CONVOCAÇÃO</b></center></td>
        </tr>

        <tr>
            <td>
                <b>Adicionado(s): </b> <br> ";
                foreach($lista_docs_obrigatorios_adicionados as &$doc_acicionado)
                {
                    if((int)$_SESSION['selecao_regiao'] == 7)
                    {
                        $avaliacao = "";
                        if($doc_acicionado['valido'] == 1) $avaliacao = "<font color = 'green'>Válido</font>";
                        if($doc_acicionado['valido'] == 0) $avaliacao = "<font color = 'red'>Inválido</font>";
                        if($doc_acicionado['justificativa'] != null) $avaliacao = $avaliacao . " - " . $doc_acicionado['justificativa'];
                        $html = $html . $doc_acicionado['label'] . " | ".$avaliacao ."<br> ";
                    }
                    else
                    {
                        $html = $html . $doc_acicionado['label'] . " <br> ";
                    }
                }
                $html = $html . "
            </td>
        </tr>";

                
    if($quantidade_docs_faltantes > 0)
    {
        
        $html = $html . "
        <tr>
            <td style='color:red'> 
             Inscrição INCOMPLETA! Fins ser classificado para a ETAPA II, conclua sua inscrição dentro do prazo previsto no Anexo A!  ";
            $html = $html . "</td>
        </tr>";
        
        $html = $html . "
        <tr>
            <td style='color:red'> 
            <b> ".$quantidade_docs_faltantes." Documento(s) Faltando: </b> <br> ";
            foreach($lista_docs_obrigatorios_sobrando as &$doc)
            {
                $html = $html . $doc['nome'] . " <br> ";
            }
            $html = $html . "</td>
        </tr>";
    }

    $html = $html . "</table> ";

///////////////////////////////////////////////
// ESPECIALIZAÇÕES CADASTRADAS
///////////////////////////////////////////////

if(!isset($_SESSION['eipot']))  
{    
    $html = $html. " <br>

    <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >
        <tr style='background-color: #D8D8D8'>
            <td colspan=\"3\"> <center><b>ESPECIALIDADE(S) CADASTRADA(S)</b></center></td>
        </tr>
    </table> 
    ";

    $inscricoes = $conexao->get_especialidade_candidato($id_candidato);

    foreach ($inscricoes as $valor)
    {
        $lista_docs_obrigatorios = $conexao->get_curriculos_inseridos_candidato($id_candidato,$valor['id_especialidade']);  

        $get_prioridade_cidades = $conexao->get_prioridade_especialidade_candidato($valor['id_candidato_x_especialidade']);

        $concorrendo_na_especialidade = "";
        if($valor['concorrendo'] == 0)
            $concorrendo_na_especialidade = " <font color='red'> - DESCLASSIFICADO </font>";

        // ESPECIALIDADE SELECIONADA
        $html = $html. " 
        <table border='0' style='font-size: 12px; font-family: Times New Roman; width:100%' >
            <tr>
                <td>
                    <b><u>".mb_strtoupper($valor['ott_stt'], "UTF-8")." - ".$valor['especialidade'] ." $concorrendo_na_especialidade</u></b> 
                </td>
            </tr>";

        $html = $html." 
                <tr>
                    <td> <b>Documentos adicionados:</b> ";
                        $pontuacao_total = null;
                        if($liberacao_avaliacao_curricular == 1) $pontuacao_total = 0;
                        foreach ($lista_docs_obrigatorios as &$docs_obrigatorios)
                        {

                            if((int)$_SESSION['selecao_regiao'] == 7 && $liberacao_avaliacao_curricular == 1)
                            {
                                $avaliacao = "";

                                if($docs_obrigatorios['valido'] == '1')
                                {
                                    $pontuacao = $docs_obrigatorios['pontuacao'] / 1000;
                                    
                                    $multi = (int)$docs_obrigatorios['multiplicador'];
                                    if($multi > 1)
                                        $pontuacao = $pontuacao * $multi;
                                    
                                    $pontuacao_total = $pontuacao_total + $pontuacao;
                                }

                                if((int)$docs_obrigatorios['quantidade_multiplicacao'] > 0 && (int)$docs_obrigatorios['pontuacao'] > 0) $pontuacao = $pontuacao + (((int)$docs_obrigatorios['pontuacao'] * (int)$docs_obrigatorios['quantidade_multiplicacao'])/1000);
                                else if((int)$docs_obrigatorios['pontuacao'] > 0) $pontuacao = $pontuacao + (int)$docs_obrigatorios['pontuacao']/1000;
                                
                                if($docs_obrigatorios['valido'] == 1) $avaliacao = "<font color = 'green'>Válido</font>";
                                if($docs_obrigatorios['valido'] == 0) $avaliacao = "<font color = 'red'>Inválido</font>";
                                $avaliacao =  $avaliacao . " " . $docs_obrigatorios['justificativa'];
                                $html = $html. $docs_obrigatorios['label'] . " ".$avaliacao." | " ;
                            }
                            else
                            {
                                $html = $html. $docs_obrigatorios['label'] . " | " ;
                            }

                        }
                        if((int)$_SESSION['selecao_regiao'] == 7) $html = $html. " TOTAL DE PONTOS: $pontuacao_total ";
                        $html = $html. "</td>
                </tr>";


        // CIDADES SELECIONADAS
        /*
        $html = $html."
        <tr>
            <td>";
        $html = $html . "<b>Prioridades de cidades:</b> ";

        if(count($get_prioridade_cidades) == 0)
        {
            $html = $html. "<font color='red'> * Nenhuma cidade selecionada </font>";
        }
        else
        {
            foreach ($get_prioridade_cidades as $valor_cidades)
            {
                $html = $html. $valor_cidades['prioridade'] . "ª " .$valor_cidades['nome'] . " | ";
            }
        }
        $html = $html."
        </td>
            </tr>";
         */

        /*
        // CIDADES NÃO SELECIONADAS
        $lista_cidades_nao_selecionadas = $conexao->get_cidades_especialidade_candidato($valor['id_especialidade'], $valor['id_candidato_x_especialidade']);  
        foreach ($lista_cidades_nao_selecionadas as $valor_cidades_nao_selecionadas) 
        {
            $html = $html. "<font color='red'> *".$valor_cidades_nao_selecionadas['nome']." | </font>";
        }

        */

    $html = $html."
        </table> <br>";
    }

    if(count($inscricoes) == 0)
    {
        $html = $html. " <br><u><font color='red'>
            Inscrição INCOMPLETA! Fins ser classificado para a ETAPA II, conclua sua inscrição dentro do prazo previsto no Anexo A!
            <br>Nenhuma Especialidade Cadastrada! 
            <br> Nenhum Currículo Adicionado!
            <br> Evite a sua DESCLASSIFICAÇÃO! Leia novamente o Aviso de Convocação!

            </font></u>";
    }

}



if($candidato_relatorio[0]['etapa'] == 1)
$html = $html. "        
        
<p  style='font-size: 12px;'>
    <i><b>Observação</b>: Este relatório é de carater informativo apenas para conferencia de dados cadastrados. A sua inscrição será validada após o término do período de inscrição.</i>
</p>
<br>";

$html = $html. "  
<p class='center' style='font-size: 12px;'>Relatório gerado em $datetime</p>
";
//<p class='direita' style='font-size: 12px;'>Relatório gerado em $datetime</p>
 
include("mpdf60/mpdf.php");
 

 $mpdf=new mPDF(); 
 //$mpdf->SetDisplayMode('fullwidth');
 $mpdf->SetDisplayMode('fullpage');
 $css = file_get_contents("css/estilo.css");
 $mpdf->WriteHTML($css,1);
 $mpdf->WriteHTML($html);
 
 $insere_log = $conexao->insere_log($_SESSION['id_usuario'], $_SESSION['cpf'], null, "20108", "relatorio", "create", "Gerou um relatório de informações do candidato CPF $cpf de ID $id_candidato", null);
 
 if($insere_log)
    $mpdf->Output("relatorio_candidato_$cpf.pdf",'D');

 exit;